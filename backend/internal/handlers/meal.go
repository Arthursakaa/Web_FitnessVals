package handlers

import (
	"encoding/json"
	"net/http"

	"github.com/gin-gonic/gin"
	"gorm.io/gorm"

	"gym-backend-go/internal/config"
	"gym-backend-go/internal/models"
)

// GetMeals returns meals based on user profile preferences
func GetMeals(c *gin.Context) {
	// Get UserID from JWT context
	userIDVal, exists := c.Get("user_id")
	if !exists {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
		return
	}
	userID := userIDVal.(uint)

	// Fetch user profile
	var profile models.UserProfile
	if err := config.DB.Where("user_id = ?", userID).First(&profile).Error; err != nil && err != gorm.ErrRecordNotFound {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Database error"})
		return
	}

	// Fetch meals
	query := config.DB.Model(&models.Meal{})

	if profile.ID != 0 {
		// Apply Dietary Filters
		if profile.DietaryPreference != nil && *profile.DietaryPreference != "Normal" {
			// MySQL JSON_CONTAINS
			query = query.Where("JSON_CONTAINS(dietary_tags, ?)", `"`+*profile.DietaryPreference+`"`)
		}

		// Apply Medical History Filters
		if profile.MedicalHistory != nil && *profile.MedicalHistory != "" {
			var medHistory []string
			if err := json.Unmarshal([]byte(*profile.MedicalHistory), &medHistory); err == nil {
				for _, med := range medHistory {
					if med != "Tidak Ada" {
						query = query.Where("JSON_CONTAINS(medical_tags, ?)", `"`+med+`"`)
					}
				}
			}
		}
	}

	var allMeals []models.Meal
	if err := query.Find(&allMeals).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch meals"})
		return
	}

	// Categorize meals
	var breakfast []models.Meal
	var lunch []models.Meal
	var dinner []models.Meal

	for _, m := range allMeals {
		if m.MealType != nil {
			if *m.MealType == "Sarapan" && len(breakfast) < 4 {
				breakfast = append(breakfast, m)
			} else if *m.MealType == "Makan Siang" && len(lunch) < 4 {
				lunch = append(lunch, m)
			} else if *m.MealType == "Makan Malam" && len(dinner) < 4 {
				dinner = append(dinner, m)
			}
		}
	}

	// Return JSON response
	c.JSON(http.StatusOK, gin.H{
		"breakfast": breakfast,
		"lunch":     lunch,
		"dinner":    dinner,
	})
}

// GetRecommendedMeals returns 3 recommended meals based on workout intensity
func GetRecommendedMeals(c *gin.Context) {
	userIDVal, exists := c.Get("user_id")
	if !exists {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
		return
	}
	userID := userIDVal.(uint)

	workoutIntensity := c.Query("workout_intensity")
	if workoutIntensity == "" {
		workoutIntensity = "rest"
	}

	var profile models.UserProfile
	if err := config.DB.Where("user_id = ?", userID).First(&profile).Error; err != nil && err != gorm.ErrRecordNotFound {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Database error"})
		return
	}

	query := config.DB.Model(&models.Meal{})

	if profile.ID != 0 {
		if profile.DietaryPreference != nil && *profile.DietaryPreference != "Normal" {
			query = query.Where("JSON_CONTAINS(dietary_tags, ?)", `"`+*profile.DietaryPreference+`"`)
		}

		if profile.MedicalHistory != nil && *profile.MedicalHistory != "" {
			var medHistory []string
			if err := json.Unmarshal([]byte(*profile.MedicalHistory), &medHistory); err == nil {
				for _, med := range medHistory {
					if med != "Tidak Ada" {
						query = query.Where("JSON_CONTAINS(medical_tags, ?)", `"`+med+`"`)
					}
				}
			}
		}
	}

	var allMeals []models.Meal
	if err := query.Find(&allMeals).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch meals"})
		return
	}

	// For simplicity, we just return all matched meals here, and let Laravel do the random variation logic,
	// or we can implement the random variation here if we want pure Go AI logic.
	// Since Laravel modifies `match_rate`, we can calculate it and return it in the JSON.
	type MealWithMatch struct {
		models.Meal
		MatchRate int `json:"match_rate"`
	}

	var recommendedMeals []MealWithMatch
	for _, m := range allMeals {
		matchRate := 70
		if m.TargetWorkout != nil {
			var targets []string
			if err := json.Unmarshal([]byte(*m.TargetWorkout), &targets); err == nil {
				for _, t := range targets {
					if t == workoutIntensity {
						matchRate += 25
						break
					}
				}
			}
		}
		
		// Note: rand(1,5) would require math/rand, skipping the rand component for now in Go to keep it deterministic,
		// or just use a pseudo variation based on ID
		variation := int(m.ID % 5) + 1
		finalRate := matchRate + variation
		if finalRate > 99 {
			finalRate = 99
		}
		
		recommendedMeals = append(recommendedMeals, MealWithMatch{
			Meal: m,
			MatchRate: finalRate,
		})
	}

	// Sort manually
	for i := 0; i < len(recommendedMeals)-1; i++ {
		for j := i + 1; j < len(recommendedMeals); j++ {
			if recommendedMeals[i].MatchRate < recommendedMeals[j].MatchRate {
				recommendedMeals[i], recommendedMeals[j] = recommendedMeals[j], recommendedMeals[i]
			}
		}
	}

	// Take top 3
	if len(recommendedMeals) > 3 {
		recommendedMeals = recommendedMeals[:3]
	}

	c.JSON(http.StatusOK, recommendedMeals)
}
