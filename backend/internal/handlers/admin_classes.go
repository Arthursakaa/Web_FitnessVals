package handlers

import (
	"net/http"

	"github.com/gin-gonic/gin"
	"gym-backend-go/internal/config"
	"gym-backend-go/internal/models"
)

// GetAdminClasses returns a list of classes for admin
func GetAdminClasses(c *gin.Context) {
	var classes []models.GymClass
	query := config.DB.Model(&models.GymClass{})

	if search := c.Query("search"); search != "" {
		query = query.Where("name LIKE ?", "%"+search+"%")
	}
	if classType := c.Query("type"); classType != "" {
		query = query.Where("type = ?", classType)
	}

	// We need to count schedules per class, but for simplicity we'll just fetch all classes
	// Laravel uses withCount('schedules'), we can do it by preloading and counting, or raw SQL.
	// For now just Preload schedules so the frontend can count them
	query.Preload("Schedules").Find(&classes)

	var schedules []models.ClassSchedule
	config.DB.Preload("GymClass").Order("start_time asc").Find(&schedules)

	var trainers []models.Trainer
	config.DB.Find(&trainers)

	c.JSON(http.StatusOK, gin.H{
		"classes":   classes,
		"schedules": schedules,
		"trainers":  trainers,
	})
}

// StoreAdminClass creates a new gym class
func StoreAdminClass(c *gin.Context) {
	var req models.GymClass
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	if err := config.DB.Create(&req).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to create class"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Kelas berhasil ditambahkan.", "class": req})
}

// UpdateAdminClass updates an existing gym class
func UpdateAdminClass(c *gin.Context) {
	id := c.Param("id")
	var class models.GymClass
	if err := config.DB.First(&class, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Class not found"})
		return
	}

	var req models.GymClass
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	class.Name = req.Name
	class.MaxCapacity = req.MaxCapacity
	class.Description = req.Description
	class.DurationMinutes = req.DurationMinutes
	class.Type = req.Type

	if err := config.DB.Save(&class).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to update class"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Kelas berhasil diperbarui.", "class": class})
}

// DeleteAdminClass deletes a gym class
func DeleteAdminClass(c *gin.Context) {
	id := c.Param("id")
	var class models.GymClass
	if err := config.DB.First(&class, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Class not found"})
		return
	}

	tx := config.DB.Begin()

	// Delete schedules associated with this class
	if err := tx.Where("gym_class_id = ?", class.ID).Delete(&models.ClassSchedule{}).Error; err != nil {
		tx.Rollback()
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to delete schedules"})
		return
	}

	if err := tx.Delete(&class).Error; err != nil {
		tx.Rollback()
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to delete class"})
		return
	}

	tx.Commit()

	c.JSON(http.StatusOK, gin.H{"message": "Kelas berhasil dihapus."})
}
