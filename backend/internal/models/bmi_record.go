package models

import (
	"time"
)

type BmiRecord struct {
	ID                  uint       `gorm:"primaryKey"`
	UserID              uint       `gorm:"index"`
	WeightKg            float64
	HeightCm            float64
	BmiValue            float64
	Category            string
	RecommendedCalories *int
	RecordedAt          time.Time  `gorm:"default:CURRENT_TIMESTAMP"`
	CreatedAt           time.Time
	UpdatedAt           time.Time

	// BelongsTo relationship
	User User `gorm:"foreignKey:UserID"`
}
