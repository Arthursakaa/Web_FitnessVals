package models

import (
	"time"
)

type WorkoutLog struct {
	ID        uint       `gorm:"primaryKey" json:"id"`
	UserID    uint       `json:"user_id"`
	FocusArea string     `json:"focus_area"`
	LogDate   string     `json:"log_date"`
	Notes     *string    `json:"notes"`
	CreatedAt time.Time  `json:"created_at"`
	UpdatedAt time.Time  `json:"updated_at"`

	User User `gorm:"foreignKey:UserID"`
}
