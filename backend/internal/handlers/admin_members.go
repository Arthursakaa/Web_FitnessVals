package handlers

import (
	"net/http"

	"github.com/gin-gonic/gin"
	"gym-backend-go/internal/config"
	"gym-backend-go/internal/models"
)

// GetAdminMembers returns a list of members with filters
func GetAdminMembers(c *gin.Context) {
	var members []models.User
	query := config.DB.Model(&models.User{}).Where("role = ?", "member")

	if tab := c.Query("tab"); tab != "" && tab != "semua" {
		query = query.Where("status = ?", tab)
	}

	if search := c.Query("search"); search != "" {
		query = query.Where("name LIKE ? OR email LIKE ?", "%"+search+"%", "%"+search+"%")
	}

	if plan := c.Query("plan"); plan != "" {
		query = query.Where("plan = ?", plan)
	}

	// We'll return all members that match the criteria. Pagination will be handled
	// either by returning everything and letting Laravel paginate or passing page params.
	// For simplicity, we just return all matching members and Laravel can paginate the collection.
	query.Order("created_at desc").Find(&members)

	c.JSON(http.StatusOK, gin.H{
		"members": members,
	})
}

// DeleteAdminMember deletes a member
func DeleteAdminMember(c *gin.Context) {
	id := c.Param("id")
	var member models.User
	if err := config.DB.First(&member, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Member not found"})
		return
	}

	if err := config.DB.Delete(&member).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to delete member"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Member berhasil dihapus dari sistem."})
}

// BulkDeleteAdminMembers deletes multiple members
func BulkDeleteAdminMembers(c *gin.Context) {
	var req struct {
		IDs []uint `json:"ids"`
	}
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid request"})
		return
	}

	if len(req.IDs) > 0 {
		if err := config.DB.Where("id IN ?", req.IDs).Delete(&models.User{}).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to delete members"})
			return
		}
	}

	c.JSON(http.StatusOK, gin.H{"message": "Members berhasil dihapus."})
}
