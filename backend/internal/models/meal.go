package models

import (
	"time"
)

type Meal struct {
	ID            uint       `gorm:"primaryKey" json:"id"`
	Name          string     `json:"name"`
	Description   *string    `json:"description"`
	Calories      int        `json:"calories"`
	ProteinG      int        `json:"protein_g"`
	CarbsG        int        `json:"carbs_g"`
	FatG          int        `json:"fat_g"`
	MealType      *string    `json:"meal_type"`
	ImageUrl      *string    `json:"image_url"`
	DietaryTags   *string    `json:"dietary_tags"`
	MedicalTags   *string    `json:"medical_tags"`
	TargetWorkout *string    `json:"target_workout"`
	CreatedAt     time.Time  `json:"created_at"`
	UpdatedAt     time.Time  `json:"updated_at"`
}
