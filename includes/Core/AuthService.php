<?php

namespace Corbidev\ModalAuth\Core;

class AuthService {

    public function login(string $username, string $password): array
    {
        $creds = [
            'user_login'    => sanitize_text_field($username),
            'user_password' => $password,
            'remember'      => true
        ];

        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            return [
                'success' => false,
                'message' => __('Invalid credentials', 'corbidevmodalauth')
            ];
        }

        return ['success' => true];
    }
}