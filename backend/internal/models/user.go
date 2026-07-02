package models

import (
	"time"
)

type User struct {
	ID              uint       `gorm:"primaryKey" json:"id"`
	Name            string     `json:"name"`
	Email           string     `gorm:"unique" json:"email"`
	EmailVerifiedAt *time.Time `json:"email_verified_at,omitempty"`
	Password        string     `json:"-"` // Don't expose password in JSON
	RememberToken   *string    `json:"remember_token,omitempty"`
	Role            string     `gorm:"default:'member'" json:"role"`
	Plan            string     `json:"plan"`
	BillingCycle    string     `json:"billing_cycle"`
	MembershipID    *uint      `json:"membership_id,omitempty"`
	Status          string     `gorm:"default:'active'" json:"status"`
	ExpiresAt       *time.Time `json:"expires_at,omitempty"`
	CreatedAt       time.Time  `json:"created_at"`
	UpdatedAt       time.Time  `json:"updated_at"`
}
