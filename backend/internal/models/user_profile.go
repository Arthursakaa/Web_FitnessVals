package models

import (
	"time"
)

type UserProfile struct {
	ID                      uint       `gorm:"primaryKey"`
	UserID                  uint       `gorm:"index"`
	Gender                  *string
	Age                     *int
	HeightCm                *float64
	WeightKg                *float64
	TargetWeightKg          *float64
	ActivityLevelMultiplier float64    `gorm:"default:1.200"`
	DietaryPreference       *string
	MedicalHistory          *string    // JSON is typically stored as string or custom type in gorm
	FitnessGoal             *string
	CreatedAt               time.Time
	UpdatedAt               time.Time

	// BelongsTo relationship
	User User `gorm:"foreignKey:UserID"`
}
