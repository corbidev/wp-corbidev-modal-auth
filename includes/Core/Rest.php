<?php

namespace Corbidev\ModalAuth\Core;

use WP_REST_Request;
use WP_REST_Response;

if (!defined('ABSPATH')) {
    exit;
}

class Rest
{
    private AuthService $authService;
    private RestRateLimiter $rateLimiter;

    private const NAMESPACE = 'corbidev-modal-auth/v1';

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->rateLimiter = new RestRateLimiter();
    }

    /**
     * Initialisation REST
     */
    public function register(): void
    {
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    /**
     * Déclaration des routes REST
     */
    public function registerRoutes(): void
    {
        // LOGIN (public)
        register_rest_route(self::NAMESPACE, '/login', [
            'methods'             => 'POST',
            'callback'            => [$this, 'login'],
            'permission_callback' => '__return_true',
        ]);

        // LOST PASSWORD (public)
        register_rest_route(self::NAMESPACE, '/lost-password', [
            'methods'             => 'POST',
            'callback'            => [$this, 'lostPassword'],
            'permission_callback' => '__return_true',
        ]);

        // LOGOUT (nonce requis)
        register_rest_route(self::NAMESPACE, '/logout', [
            'methods'             => 'POST',
            'callback'            => [$this, 'logout'],
            'permission_callback' => [$this, 'verifyNonce'],
        ]);

        // SESSION USER
        register_rest_route(self::NAMESPACE, '/me', [
            'methods'             => 'GET',
            'callback'            => [$this, 'currentUser'],
            'permission_callback' => '__return_true',
        ]);

// SETTINGS GET
register_rest_route(self::NAMESPACE, '/settings', [
    'methods'             => 'GET',
    'callback'            => [$this, 'getSettings'],
    'permission_callback' => [$this, 'canManage'],
]);

// SETTINGS UPDATE
register_rest_route(self::NAMESPACE, '/settings', [
    'methods'             => 'POST',
    'callback'            => [$this, 'updateSettings'],
    'permission_callback' => [$this, 'canManage'],
]);
    }

    /**
     * Vérification nonce pour endpoints protégés
     */
    public function verifyNonce(): bool
    {
        $nonce = $_SERVER['HTTP_X_WP_NONCE'] ?? '';

        if (empty($nonce)) {
            return false;
        }

        return wp_verify_nonce($nonce, 'wp_rest');
    }

    /**
     * Middleware rate limit
     */
    private function applyRateLimit(string $route): ?WP_REST_Response
    {
        if (!$this->rateLimiter->isAllowed($route)) {
            return new WP_REST_Response([
                'success' => false,
                'code'    => 'rate_limited',
            ], 429);
        }

        return null;
    }

    /**
     * LOGIN
     */
    public function login(WP_REST_Request $request): WP_REST_Response
    {
        if ($limited = $this->applyRateLimit('login')) {
            return $limited;
        }

        $data = $request->get_json_params() ?? [];

        $result = $this->authService->login($data);

        return $this->formatResponse($result);
    }

    /**
     * LOGOUT
     */
    public function logout(): WP_REST_Response
    {
        if ($limited = $this->applyRateLimit('logout')) {
            return $limited;
        }

        $result = $this->authService->logout();

        return $this->formatResponse($result);
    }

    /**
     * LOST PASSWORD
     */
    public function lostPassword(WP_REST_Request $request): WP_REST_Response
    {
        if ($limited = $this->applyRateLimit('lost_password')) {
            return $limited;
        }

        $data = $request->get_json_params() ?? [];

        $result = $this->authService->lostPassword($data);

        return $this->formatResponse($result);
    }

    /**
     * UTILISATEUR COURANT
     */
    public function currentUser(): WP_REST_Response
    {
        if (!is_user_logged_in()) {
            return new WP_REST_Response([
                'logged_in' => false,
            ], 200);
        }

        $user = wp_get_current_user();

        return new WP_REST_Response([
            'logged_in' => true,
            'id'        => $user->ID,
            'roles'     => $user->roles,
        ], 200);
    }

    /**
     * Format standardisé des réponses
     */
    private function formatResponse(array $result): WP_REST_Response
    {
        $status = (!empty($result['success']) && $result['success'] === true)
            ? 200
            : 400;

        return new WP_REST_Response($result, $status);
    }

public function canManage(): bool
{
    return current_user_can('manage_options');
}

public function getSettings(): WP_REST_Response
{
    $settings = (new Settings())->get();

    return new WP_REST_Response($settings, 200);
}

public function updateSettings(WP_REST_Request $request): WP_REST_Response
{
    $data = $request->get_json_params() ?? [];

    $settings = new Settings();
    $settings->update($data);

    return new WP_REST_Response([
        'success' => true,
        'settings' => $settings->get(),
    ], 200);
}
}