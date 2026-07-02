package models

import "time"

// GymClass represents a gym class (e.g. Zumba, Yoga)
type GymClass struct {
	ID              uint            `gorm:"primaryKey" json:"id"`
	Name            string          `json:"name"`
	Description     string          `json:"description"`
	Type            string          `json:"type"`
	DurationMinutes int             `json:"duration_minutes"`
	MaxCapacity     int             `json:"max_capacity"`
	CreatedAt       time.Time       `json:"created_at"`
	UpdatedAt       time.Time       `json:"updated_at"`
	Schedules       []ClassSchedule `gorm:"foreignKey:GymClassID" json:"schedules,omitempty"`
}

// ClassSchedule represents a scheduled instance of a GymClass
type ClassSchedule struct {
	ID              uint           `gorm:"primaryKey" json:"id"`
	GymClassID      uint           `json:"gym_class_id"`
	GymClass        GymClass       `gorm:"foreignKey:GymClassID" json:"gymClass"`
	TrainerName     string         `json:"trainer_name"`
	StartTime       time.Time      `json:"start_time"`
	CurrentBookings int            `json:"current_bookings"`
	Status          string         `json:"status"` // e.g. "scheduled"
	CreatedAt       time.Time      `json:"created_at"`
	UpdatedAt       time.Time      `json:"updated_at"`
	Bookings        []ClassBooking `gorm:"foreignKey:ClassScheduleID" json:"bookings,omitempty"`
}

// ClassBooking represents a user's booking for a ClassSchedule
type ClassBooking struct {
	ID              uint          `gorm:"primaryKey" json:"id"`
	UserID          uint          `json:"user_id"`
	ClassScheduleID uint          `json:"class_schedule_id"`
	ClassSchedule   ClassSchedule `gorm:"foreignKey:ClassScheduleID" json:"class_schedule,omitempty"`
	Status          string        `json:"status"` // booked, attended, cancelled
	CreatedAt       time.Time     `json:"created_at"`
	UpdatedAt       time.Time     `json:"updated_at"`
}
