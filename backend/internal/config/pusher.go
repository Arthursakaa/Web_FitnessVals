package config

import (
	"log"
	"os"

	"github.com/pusher/pusher-http-go/v5"
)

var PusherClient pusher.Client

func InitPusher() {
	appID := os.Getenv("PUSHER_APP_ID")
	key := os.Getenv("PUSHER_KEY")
	secret := os.Getenv("PUSHER_SECRET")
	host := os.Getenv("PUSHER_HOST")

	if appID == "" || key == "" || secret == "" || host == "" {
		log.Println("Pusher credentials are not set in .env")
		return
	}

	PusherClient = pusher.Client{
		AppID:   appID,
		Key:     key,
		Secret:  secret,
		Cluster: "", // Reverb doesn't use cluster
		Host:    host,
	}

	log.Println("Pusher/Reverb client initialized")
}
