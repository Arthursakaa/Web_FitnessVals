package handlers

import (
	"net/http"

	"github.com/gin-gonic/gin"
	"gym-backend-go/internal/config"
	"gym-backend-go/internal/models"
)

// GetTrainers fetches all trainers
func GetTrainers(c *gin.Context) {
	var trainers []models.Trainer

	if err := config.DB.Find(&trainers).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch trainers"})
		return
	}

	c.JSON(http.StatusOK, trainers)
}

// GetMyTrainerBookings fetches trainer sessions booked by the current user
func GetMyTrainerBookings(c *gin.Context) {
	userIDVal, exists := c.Get("user_id")
	if !exists {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
		return
	}
	userID := userIDVal.(uint)

	var bookings []models.TrainerBooking
	if err := config.DB.Preload("Trainer").Where("user_id = ?", userID).Find(&bookings).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch trainer bookings"})
		return
	}

	c.JSON(http.StatusOK, bookings)
}

type BookTrainerRequest struct {
	TrainerID   uint   `json:"trainer_id" binding:"required"`
	BookingDate string `json:"booking_date" binding:"required"`
	BookingTime string `json:"booking_time" binding:"required"`
	SessionType string `json:"session_type" binding:"required"`
	Message     string `json:"message"`
}

// BookTrainer books a trainer
func BookTrainer(c *gin.Context) {
	userIDVal, exists := c.Get("user_id")
	if !exists {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
		return
	}
	userID := userIDVal.(uint)

	var req BookTrainerRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	var trainer models.Trainer
	if err := config.DB.First(&trainer, req.TrainerID).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Trainer not found"})
		return
	}

	booking := models.TrainerBooking{
		UserID:      userID,
		TrainerID:   req.TrainerID,
		BookingDate: req.BookingDate,
		BookingTime: req.BookingTime,
		SessionType: req.SessionType,
		Message:     req.Message,
		Status:      "Menunggu Konfirmasi",
	}

	if err := config.DB.Create(&booking).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to book trainer"})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"message": "Trainer booked successfully",
		"booking": booking,
	})
}
