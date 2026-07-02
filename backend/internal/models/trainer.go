package models

import "time"

// Trainer represents a personal trainer
type Trainer struct {
	ID              uint    `gorm:"primaryKey" json:"id"`
	Name            string  `json:"name"`
	Specialty       string  `json:"specialty"`
	PhotoURL        string  `json:"photo_url"`
	Bio             string  `json:"bio"`
	Whatsapp        string  `json:"whatsapp"`
	Rating          float64 `json:"rating"`
	PricePerSession int     `json:"price_per_session"`
	Availability    string  `json:"availability"`
	CreatedAt       time.Time `json:"created_at"`
	UpdatedAt       time.Time `json:"updated_at"`
}

// TrainerBooking represents a user's booking for a personal trainer
type TrainerBooking struct {
	ID          uint      `gorm:"primaryKey" json:"id"`
	UserID      uint      `json:"user_id"`
	TrainerID   uint      `json:"trainer_id"`
	Trainer     Trainer   `gorm:"foreignKey:TrainerID" json:"trainer,omitempty"`
	BookingDate string    `json:"booking_date"` // YYYY-MM-DD
	BookingTime string    `json:"booking_time"` // HH:mm
	SessionType string    `json:"session_type"`
	Message     string    `json:"message"`
	Status      string    `json:"status"` // "Menunggu Konfirmasi"
	CreatedAt   time.Time `json:"created_at"`
	UpdatedAt   time.Time `json:"updated_at"`
}
