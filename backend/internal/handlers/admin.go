package handlers

import (
	"net/http"

	"github.com/gin-gonic/gin"
	"gym-backend-go/internal/config"
	"gym-backend-go/internal/models"
)

// GetAdminDashboard returns statistics for the admin dashboard
func GetAdminDashboard(c *gin.Context) {
	var memberCount int64
	config.DB.Model(&models.User{}).Where("role = ?", "member").Count(&memberCount)

	var classCount int64
	config.DB.Model(&models.GymClass{}).Count(&classCount)

	var bookingCount int64
	config.DB.Model(&models.ClassBooking{}).Count(&bookingCount)

	var basicCount int64
	config.DB.Model(&models.User{}).Where("plan = ?", "basic").Count(&basicCount)

	var proCount int64
	config.DB.Model(&models.User{}).Where("plan = ?", "pro").Count(&proCount)

	var eliteCount int64
	config.DB.Model(&models.User{}).Where("plan = ?", "elite").Count(&eliteCount)

	var recentMembers []models.User
	config.DB.Where("role = ?", "member").Order("created_at desc").Limit(5).Find(&recentMembers)

	monthlyRevenue := (basicCount * 299000) + (proCount * 549000) + (eliteCount * 899000)

	c.JSON(http.StatusOK, gin.H{
		"memberCount":    memberCount,
		"classCount":     classCount,
		"bookingCount":   bookingCount,
		"basicCount":     basicCount,
		"proCount":       proCount,
		"eliteCount":     eliteCount,
		"monthlyRevenue": monthlyRevenue,
		"recentMembers":  recentMembers,
	})
}

// GetAdminReports returns statistics for the admin reports
func GetAdminReports(c *gin.Context) {
	// Let's implement the basic stats that AdminReportController uses
	// We return all the raw data, Laravel can format it
	
	// We can reuse the queries from above
	var totalMembers int64
	config.DB.Model(&models.User{}).Where("role = ?", "member").Count(&totalMembers)

	var activeMembers int64
	config.DB.Model(&models.User{}).Where("role = ?", "member").Where("status = ?", "active").Count(&activeMembers)

	var basicCount int64
	config.DB.Model(&models.User{}).Where("plan = ?", "basic").Count(&basicCount)

	var proCount int64
	config.DB.Model(&models.User{}).Where("plan = ?", "pro").Count(&proCount)

	var eliteCount int64
	config.DB.Model(&models.User{}).Where("plan = ?", "elite").Count(&eliteCount)

	var expiredCount int64
	config.DB.Model(&models.User{}).Where("role = ?", "member").Where("status = ?", "expired").Count(&expiredCount)

	var profiles []models.UserProfile
	config.DB.Where("age IS NOT NULL").Find(&profiles)

	// Fetch class schedules for class stats
	// We need gym classes with schedules and bookings
	var classes []models.GymClass
	config.DB.Preload("Schedules.Bookings").Find(&classes)

	c.JSON(http.StatusOK, gin.H{
		"totalMembers":  totalMembers,
		"activeMembers": activeMembers,
		"basicCount":    basicCount,
		"proCount":      proCount,
		"eliteCount":    eliteCount,
		"expiredCount":  expiredCount,
		"profiles":      profiles,
		"classes":       classes,
	})
}
