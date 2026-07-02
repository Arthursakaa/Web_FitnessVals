package middleware

import (
	"net/http"

	"github.com/gin-gonic/gin"
)

// RequireAdmin middleware ensures the user has the 'admin' role
func RequireAdmin() gin.HandlerFunc {
	return func(c *gin.Context) {
		roleVal, exists := c.Get("role")
		if !exists {
			c.AbortWithStatusJSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
			return
		}

		role := roleVal.(string)
		if role != "admin" {
			c.AbortWithStatusJSON(http.StatusForbidden, gin.H{"error": "Forbidden: Admin access required"})
			return
		}

		c.Next()
	}
}
