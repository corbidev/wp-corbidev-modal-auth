<?php

namespace Corbidev\ModalAuth\Core;

use WP_Error;

if (!defined('ABSPATH')) {
    exit;
}

class AuthService
{

   private SecurityService $security;

   public function __construct()
{
    $this->security = new SecurityService();
}
    /**
     * Login utilisateur
     */
    public function login(array $data): array
    {
      if ($this->security->isBlocked()) {
      return $this->error('too_many_attempts');
}
        $credentials = [
            'user_login'    => $data['username'] ?? '',
            'user_password' => $data['password'] ?? '',
            'remember'      => !empty($data['remember']),
        ];

        if (empty($credentials['user_login']) || empty($credentials['user_password'])) {
            return $this->error('missing_fields');
        }

        $user = wp_signon($credentials, is_ssl());
        if ($user instanceof \WP_Error) {
    $this->security->registerFailure();
             return $this->error('invalid_credentials');
         }

$this->security->reset();
        if ($user instanceof WP_Error) {
            return $this->error($user->get_error_code());
        }

        wp_set_current_user($user->ID);

        return [
            'success'  => true,
            'user_id'  => $user->ID,
            'redirect' => $this->getRedirectUrl($user),
        ];
    }

    /**
     * Logout utilisateur
     */
    public function logout(): array
    {
        if (!is_user_logged_in()) {
            return $this->error('not_logged_in');
        }

        wp_logout();

        return [
            'success'  => true,
            'redirect' => $this->getLogoutRedirectUrl(),
        ];
    }

    /**
     * Mot de passe oublié
     */
    public function lostPassword(array $data): array
    {
        $user_login = $data['user_login'] ?? '';

        if (empty($user_login)) {
            return $this->error('missing_login');
        }

        $result = retrieve_password($user_login);

        if ($result instanceof WP_Error) {
            return $this->error($result->get_error_code());
        }

        return [
            'success' => true,
        ];
    }

    /**
     * URL de redirection après login
     */
    private function getRedirectUrl($user): string
    {
        $redirect = apply_filters(
            'corbidev_modal_auth_login_redirect',
            home_url(),
            $user
        );

        return esc_url_raw($redirect);
    }

    /**
     * URL de redirection après logout
     */
    private function getLogoutRedirectUrl(): string
    {
        $redirect = apply_filters(
            'corbidev_modal_auth_logout_redirect',
            home_url()
        );

        return esc_url_raw($redirect);
    }

    /**
     * Format standard d'erreur
     */
    private function error(string $code): array
    {
        return [
            'success' => false,
            'code'    => $code,
        ];
    }
}