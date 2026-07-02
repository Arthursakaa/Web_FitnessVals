package handlers

import (
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
	"gym-backend-go/internal/config"
	"gym-backend-go/internal/models"
)

type StoreIntakeRequest struct {
	Name     string `json:"name" binding:"required"`
	MealType string `json:"meal_type" binding:"required"`
	Calories int    `json:"calories"`
	ProteinG int    `json:"protein_g"`
	CarbsG   int    `json:"carbs_g"`
	FatG     int    `json:"fat_g"`
	LogDate  string `json:"log_date"`
}

// StoreIntakeLog handles POST /api/intake
func StoreIntakeLog(c *gin.Context) {
	userIDVal, exists := c.Get("user_id")
	if !exists {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
		return
	}
	userID := userIDVal.(uint)

	var req StoreIntakeRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	logDate := req.LogDate
	if logDate == "" {
		logDate = time.Now().Format("2006-01-02")
	}
	mealType := req.MealType

	logEntry := models.IntakeLog{
		UserID:   userID,
		Name:     req.Name,
		MealType: &mealType,
		Calories: req.Calories,
		ProteinG: req.ProteinG,
		CarbsG:   req.CarbsG,
		FatG:     req.FatG,
		LogDate:  logDate,
	}

	if err := config.DB.Create(&logEntry).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to save intake log"})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"message": "Intake log saved successfully",
		"data":    logEntry,
	})
}

type StoreWorkoutRequest struct {
	FocusArea string `json:"focus_area" binding:"required"`
	Notes     string `json:"notes"`
	LogDate   string `json:"log_date"`
}

// StoreWorkoutLog handles POST /api/workout
func StoreWorkoutLog(c *gin.Context) {
	userIDVal, exists := c.Get("user_id")
	if !exists {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
		return
	}
	userID := userIDVal.(uint)

	var req StoreWorkoutRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	logDate := req.LogDate
	if logDate == "" {
		logDate = time.Now().Format("2006-01-02")
	}

	var notesPtr *string
	if req.Notes != "" {
		notesPtr = &req.Notes
	}

	logEntry := models.WorkoutLog{
		UserID:    userID,
		FocusArea: req.FocusArea,
		LogDate:   logDate,
		Notes:     notesPtr,
	}

	if err := config.DB.Create(&logEntry).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to save workout log"})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"message": "Workout log saved successfully",
		"data":    logEntry,
	})
}

type StoreBmiRequest struct {
	WeightKg float64 `json:"weight_kg" binding:"required"`
	HeightCm float64 `json:"height_cm" binding:"required"`
}

// StoreBmi handles POST /api/bmi
func StoreBmi(c *gin.Context) {
	userIDVal, exists := c.Get("user_id")
	if !exists {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
		return
	}
	userID := userIDVal.(uint)

	var req StoreBmiRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	// Calculate BMI
	heightM := req.HeightCm / 100.0
	bmiValue := req.WeightKg / (heightM * heightM)
	
	category := "Normal"
	if bmiValue < 18.5 {
		category = "Underweight"
	} else if bmiValue >= 25 && bmiValue <= 29.9 {
		category = "Overweight"
	} else if bmiValue >= 30 {
		category = "Obese"
	}

	// Fetch Profile for BMR and Recommended Calories
	var profile models.UserProfile
	var recCalories *int
	if err := config.DB.Where("user_id = ?", userID).First(&profile).Error; err == nil {
		if profile.Age != nil && profile.Gender != nil && profile.TargetWeightKg != nil {
			var bmr float64
			if *profile.Gender == "male" {
				bmr = 10*req.WeightKg + 6.25*req.HeightCm - 5*float64(*profile.Age) + 5
			} else {
				bmr = 10*req.WeightKg + 6.25*req.HeightCm - 5*float64(*profile.Age) - 161
			}

			multiplier := profile.ActivityLevelMultiplier
			if multiplier == 0 {
				multiplier = 1.2
			}
			tdee := bmr * multiplier

			var target float64
			if *profile.TargetWeightKg < req.WeightKg {
				target = tdee - 400
			} else if *profile.TargetWeightKg > req.WeightKg {
				target = tdee + 400
			} else {
				target = tdee
			}
			
			intTarget := int(target)
			recCalories = &intTarget
		}
	}

	record := models.BmiRecord{
		UserID:              userID,
		WeightKg:            req.WeightKg,
		HeightCm:            req.HeightCm,
		BmiValue:            bmiValue,
		Category:            category,
		RecommendedCalories: recCalories,
	}

	if err := config.DB.Create(&record).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to save BMI record"})
		return
	}

	// Update User Profile weight and height
	config.DB.Model(&models.UserProfile{}).Where("user_id = ?", userID).Updates(map[string]interface{}{
		"weight_kg": req.WeightKg,
		"height_cm": req.HeightCm,
	})

	c.JSON(http.StatusOK, gin.H{
		"message": "BMI recorded successfully",
		"data":    record,
	})
}
