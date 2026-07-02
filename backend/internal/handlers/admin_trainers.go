package handlers

import (
	"net/http"

	"github.com/gin-gonic/gin"
	"gym-backend-go/internal/config"
	"gym-backend-go/internal/models"
)

// StoreAdminTrainer creates a new trainer
func StoreAdminTrainer(c *gin.Context) {
	var req models.Trainer
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	if err := config.DB.Create(&req).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to create trainer"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Trainer berhasil ditambahkan.", "trainer": req})
}

// UpdateAdminTrainer updates an existing trainer
func UpdateAdminTrainer(c *gin.Context) {
	id := c.Param("id")
	var trainer models.Trainer
	if err := config.DB.First(&trainer, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Trainer not found"})
		return
	}

	var req models.Trainer
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	trainer.Name = req.Name
	trainer.Specialty = req.Specialty
	trainer.Bio = req.Bio
	trainer.PricePerSession = req.PricePerSession
	if req.PhotoURL != "" {
		trainer.PhotoURL = req.PhotoURL
	}

	if err := config.DB.Save(&trainer).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to update trainer"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Trainer berhasil diperbarui.", "trainer": trainer})
}

// DeleteAdminTrainer deletes a trainer
func DeleteAdminTrainer(c *gin.Context) {
	id := c.Param("id")
	var trainer models.Trainer
	if err := config.DB.First(&trainer, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Trainer not found"})
		return
	}

	if err := config.DB.Delete(&trainer).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to delete trainer"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Trainer berhasil dihapus."})
}
