package routes

import (
	"github.com/gin-gonic/gin"
	"gym-backend-go/internal/handlers"
	"gym-backend-go/internal/middleware"
)

func SetupRoutes(r *gin.Engine) {
	// Public routes
	api := r.Group("/api")
	{
		api.GET("/ping", func(c *gin.Context) {
			c.JSON(200, gin.H{
				"message": "pong",
				"status":  "Gym Backend Go is running!",
			})
		})

		// Authentication
		api.POST("/login", handlers.Login)
	}

	// Protected routes (Requires JWT)
	protected := r.Group("/api")
	protected.Use(middleware.JWTAuthMiddleware())
	{
		// Protected routes
		protected.GET("/meals", handlers.GetMeals)
		protected.GET("/meals/recommendations", handlers.GetRecommendedMeals)
		
		protected.POST("/intake", handlers.StoreIntakeLog)
		protected.POST("/workout", handlers.StoreWorkoutLog)
		protected.POST("/bmi", handlers.StoreBmi)

		// Gym Classes
		protected.GET("/classes", handlers.GetSchedules)
		protected.GET("/classes/my-bookings", handlers.GetMyClassBookings)
		protected.POST("/classes/book", handlers.BookClass)
		protected.POST("/classes/cancel", handlers.CancelClassBooking)

		// Trainers
		protected.GET("/trainers", handlers.GetTrainers)
		protected.GET("/trainers/my-bookings", handlers.GetMyTrainerBookings)
		protected.POST("/trainers/book", handlers.BookTrainer)

		// User Profile
		protected.GET("/profile", func(c *gin.Context) {
			userID, _ := c.Get("user_id")
			email, _ := c.Get("email")
			role, _ := c.Get("role")

			c.JSON(200, gin.H{
				"user_id": userID,
				"email":   email,
				"role":    role,
			})
		})

		// Admin Routes
		adminRoutes := protected.Group("/admin")
		adminRoutes.Use(middleware.RequireAdmin())
		{
			// Dashboard & Reports
			adminRoutes.GET("/dashboard", handlers.GetAdminDashboard)
			adminRoutes.GET("/reports", handlers.GetAdminReports)

			// Members
			adminRoutes.GET("/members", handlers.GetAdminMembers)
			adminRoutes.DELETE("/members/:id", handlers.DeleteAdminMember)
			adminRoutes.POST("/members/bulk-delete", handlers.BulkDeleteAdminMembers)

			// Classes
			adminRoutes.GET("/classes", handlers.GetAdminClasses)
			adminRoutes.POST("/classes", handlers.StoreAdminClass)
			adminRoutes.PUT("/classes/:id", handlers.UpdateAdminClass)
			adminRoutes.DELETE("/classes/:id", handlers.DeleteAdminClass)

			// Trainers
			adminRoutes.POST("/trainers", handlers.StoreAdminTrainer)
			adminRoutes.PUT("/trainers/:id", handlers.UpdateAdminTrainer)
			adminRoutes.DELETE("/trainers/:id", handlers.DeleteAdminTrainer)
		}
	}
}
