package models

import (
	"time"
)

type IntakeLog struct {
	ID        uint       `gorm:"primaryKey" json:"id"`
	UserID    uint       `json:"user_id"`
	MealID    *uint      `json:"meal_id"`
	Name      string     `json:"name"`
	MealType  *string    `json:"meal_type"`
	Calories  int        `json:"calories"`
	ProteinG  int        `json:"protein_g"`
	CarbsG    int        `json:"carbs_g"`
	FatG      int        `json:"fat_g"`
	LogDate   string     `json:"log_date"` // YYYY-MM-DD
	CreatedAt time.Time  `json:"created_at"`
	UpdatedAt time.Time  `json:"updated_at"`

	User User `gorm:"foreignKey:UserID"`
}
