package main

import (
	"log"
	"os"

	"gym-backend-go/internal/config"
	"gym-backend-go/internal/routes"
	"github.com/gin-gonic/gin"
)

func main() {
	// Initialize Database connection
	config.ConnectDatabase()

	// Initialize Pusher Client
	config.InitPusher()

	// Initialize Gin router
	r := gin.Default()

	// Setup routes
	routes.SetupRoutes(r)

	// Get port from .env or default to 8080
	port := os.Getenv("PORT")
	if port == "" {
		port = "8080"
	}

	log.Printf("Server is running on port %s", port)
	
	// Start server
	r.Run(":" + port)
}
