<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Get a valid JWT token for calling the Go backend.
     * First tries the session token, then auto-generates one
     * using the shared secret — so this never returns empty.
     */
    protected function getGoJwtToken(): string
    {
        // 1. Return cached session token if available
        if (session('jwt_token')) {
            return session('jwt_token');
        }

        // 2. Auto-generate a fresh JWT using the shared HS256 secret
        $user   = Auth::user();
        $secret = env('GO_JWT_SECRET', 'supersecretgymkey2026');

        $header  = $this->base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = $this->base64UrlEncode(json_encode([
            'user_id' => (int) $user->id,
            'email'   => $user->email,
            'role'    => $user->role ?? 'member',
            'exp'     => time() + 86400,
            'iat'     => time(),
        ]));

        $signature = $this->base64UrlEncode(
            hash_hmac('sha256', "{$header}.{$payload}", $secret, true)
        );

        $token = "{$header}.{$payload}.{$signature}";

        // 3. Cache in session for subsequent requests in this browser session
        session(['jwt_token' => $token]);

        return $token;
    }

    /** RFC 4648 base64url (no padding) */
    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
