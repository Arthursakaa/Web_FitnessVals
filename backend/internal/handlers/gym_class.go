package handlers

import (
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
	"gym-backend-go/internal/config"
	"gym-backend-go/internal/models"
)

// GetSchedules fetches upcoming class schedules
func GetSchedules(c *gin.Context) {
	var schedules []models.ClassSchedule
	
	query := config.DB.Preload("GymClass")
	
	startDate := c.Query("start_date")
	endDate := c.Query("end_date")
	
	if startDate != "" && endDate != "" {
		query = query.Where("start_time >= ? AND start_time <= ?", startDate, endDate)
	} else {
		query = query.Where("start_time >= ?", time.Now().Truncate(24*time.Hour))
	}

	if err := query.Order("start_time asc").Find(&schedules).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch schedules"})
		return
	}

	c.JSON(http.StatusOK, schedules)
}

// GetMyClassBookings fetches classes booked by the current user
func GetMyClassBookings(c *gin.Context) {
	userIDVal, exists := c.Get("user_id")
	if !exists {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
		return
	}
	userID := userIDVal.(uint)

	var bookings []models.ClassBooking
	if err := config.DB.Preload("ClassSchedule").Preload("ClassSchedule.GymClass").Where("user_id = ?", userID).Find(&bookings).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch bookings"})
		return
	}

	c.JSON(http.StatusOK, bookings)
}

// BookClassRequest
type BookClassRequest struct {
	ScheduleID uint `json:"schedule_id" binding:"required"`
}

// BookClass books a gym class schedule for the user
func BookClass(c *gin.Context) {
	userIDVal, exists := c.Get("user_id")
	if !exists {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
		return
	}
	userID := userIDVal.(uint)

	var req BookClassRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	var schedule models.ClassSchedule
	if err := config.DB.Preload("GymClass").First(&schedule, req.ScheduleID).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Schedule not found"})
		return
	}

	if schedule.CurrentBookings >= schedule.GymClass.MaxCapacity {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Class is fully booked"})
		return
	}

	// Check if already booked
	var existing models.ClassBooking
	if err := config.DB.Where("user_id = ? AND class_schedule_id = ?", userID, req.ScheduleID).First(&existing).Error; err == nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "You have already booked this class"})
		return
	}

	// Create booking
	booking := models.ClassBooking{
		UserID:          userID,
		ClassScheduleID: req.ScheduleID,
		Status:          "booked",
	}

	tx := config.DB.Begin()

	if err := tx.Create(&booking).Error; err != nil {
		tx.Rollback()
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to create booking"})
		return
	}

	// Increment bookings count
	schedule.CurrentBookings++
	if err := tx.Save(&schedule).Error; err != nil {
		tx.Rollback()
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to update schedule"})
		return
	}

	tx.Commit()

	// Broadcast the update via Pusher
	data := map[string]interface{}{
		"scheduleId":      schedule.ID,
		"currentBookings": schedule.CurrentBookings,
		"maxCapacity":     schedule.GymClass.MaxCapacity,
	}
	if config.PusherClient.Host != "" {
		config.PusherClient.Trigger("gym-classes", "App\\Events\\ClassBooked", data)
	}

	c.JSON(http.StatusOK, gin.H{
		"message": "Class booked successfully",
		"booking": booking,
	})
}

// CancelClassBooking cancels a user's class booking
func CancelClassBooking(c *gin.Context) {
	userIDVal, exists := c.Get("user_id")
	if !exists {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
		return
	}
	userID := userIDVal.(uint)

	var req BookClassRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	var existing models.ClassBooking
	if err := config.DB.Where("user_id = ? AND class_schedule_id = ?", userID, req.ScheduleID).First(&existing).Error; err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "You are not booked for this class"})
		return
	}

	tx := config.DB.Begin()

	if err := tx.Delete(&existing).Error; err != nil {
		tx.Rollback()
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to cancel booking"})
		return
	}

	var schedule models.ClassSchedule
	if err := tx.First(&schedule, req.ScheduleID).Error; err == nil {
		if schedule.CurrentBookings > 0 {
			schedule.CurrentBookings--
			tx.Save(&schedule)
		}
	}

	tx.Commit()

	// Broadcast the update via Pusher
	data := map[string]interface{}{
		"scheduleId":      schedule.ID,
		"currentBookings": schedule.CurrentBookings,
		"maxCapacity":     schedule.GymClass.MaxCapacity,
	}
	if config.PusherClient.Host != "" {
		config.PusherClient.Trigger("gym-classes", "App\\Events\\ClassBooked", data)
	}

	c.JSON(http.StatusOK, gin.H{
		"message": "Booking cancelled successfully",
	})
}
