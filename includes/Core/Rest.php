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
     * Declaration des routes REST
     */
    public function registerRoutes(): void
    {
        register_rest_route(self::NAMESPACE, '/login', [
            'methods'             => 'POST',
            'callback'            => [$this, 'login'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route(self::NAMESPACE, '/lost-password', [
            'methods'             => 'POST',
            'callback'            => [$this, 'lostPassword'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route(self::NAMESPACE, '/logout', [
            'methods'             => 'POST',
            'callback'            => [$this, 'logout'],
            'permission_callback' => [$this, 'verifyNonce'],
        ]);

        register_rest_route(self::NAMESPACE, '/me', [
            'methods'             => 'GET',
            'callback'            => [$this, 'currentUser'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route(self::NAMESPACE, '/settings', [
            'methods'             => 'GET',
            'callback'            => [$this, 'getSettings'],
            'permission_callback' => [$this, 'canManage'],
        ]);

        register_rest_route(self::NAMESPACE, '/settings', [
            'methods'             => 'POST',
            'callback'            => [$this, 'updateSettings'],
            'permission_callback' => [$this, 'canManage'],
        ]);
    }

    /**
     * Verification nonce pour endpoints proteges
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
     * Format standardise des reponses
     */
    private function formatResponse(array $result): WP_REST_Response
    {
        $status = (!empty($result['success']) && $result['success'] === true) ? 200 : 400;
        return new WP_REST_Response($result, $status);
    }

    public function canManage(): bool
    {
        return current_user_can('manage_options');
    }

    public function getSettings(): WP_REST_Response
    {
        $settingsService = new Settings();
        $settings = $settingsService->get();
        $settings['turnstile_site_key'] = $settingsService->getEffectiveTurnstileSiteKey();
        $settings['cloudflare_secret'] = $settingsService->getEffectiveCloudflareSecret();
        $settings['_multisite'] = $settingsService->getMultisiteContext();

        return new WP_REST_Response($settings, 200);
    }

    public function updateSettings(WP_REST_Request $request): WP_REST_Response
    {
        $data = $request->get_json_params() ?? [];
        $networkControls = isset($data['_multisite_controls']) && is_array($data['_multisite_controls'])
            ? $data['_multisite_controls']
            : null;
        unset($data['_multisite_controls']);

        $hasTurnstileSiteKey = array_key_exists('turnstile_site_key', $data);
        $submittedSiteKey = $hasTurnstileSiteKey
            ? sanitize_text_field((string) $data['turnstile_site_key'])
            : null;
        unset($data['turnstile_site_key']);

        $hasCloudflareSecret = array_key_exists('cloudflare_secret', $data);
        $submittedSecret = $hasCloudflareSecret
            ? sanitize_text_field((string) $data['cloudflare_secret'])
            : null;
        unset($data['cloudflare_secret']);

        $settingsService = new Settings();

        $credentialsToPersist = [];
        if ($hasTurnstileSiteKey) {
            $credentialsToPersist['turnstile_site_key'] = (string) $submittedSiteKey;
        }
        if ($hasCloudflareSecret) {
            $credentialsToPersist['cloudflare_secret'] = (string) $submittedSecret;
        }

        if (!empty($credentialsToPersist)) {
            $env = new EnvFile();

            foreach ($credentialsToPersist as $key => $valueToPersist) {
                if ($settingsService->isFieldLockedForCurrentSite($key)) {
                    $valueToPersist = $key === 'turnstile_site_key'
                        ? $settingsService->getMasterTurnstileSiteKey()
                        : $settingsService->getMasterCloudflareSecret();
                    $settingsService->setSiteOverride($key, false);
                } elseif ($settingsService->isNetworkDefaultEnabled($key) && !is_main_site()) {
                    $masterValue = $key === 'turnstile_site_key'
                        ? $settingsService->getMasterTurnstileSiteKey()
                        : $settingsService->getMasterCloudflareSecret();
                    $isOverride = !hash_equals($masterValue, $valueToPersist);
                    $settingsService->setSiteOverride($key, $isOverride);

                    if (!$isOverride) {
                        $valueToPersist = $masterValue;
                    }
                } elseif (is_multisite() && !is_main_site()) {
                    $settingsService->setSiteOverride($key, false);
                }

                $saved = $key === 'turnstile_site_key'
                    ? $env->setTurnstileSiteKey($valueToPersist)
                    : $env->setCloudflareSecret($valueToPersist);

                if (!$saved) {
                    return new WP_REST_Response([
                        'success' => false,
                        'code' => 'env_write_failed',
                    ], 500);
                }
            }
        }

        $settingsService->update($data, $networkControls);

        $updatedSettings = $settingsService->get();
        $updatedSettings['turnstile_site_key'] = $settingsService->getEffectiveTurnstileSiteKey();
        $updatedSettings['cloudflare_secret'] = $settingsService->getEffectiveCloudflareSecret();

        return new WP_REST_Response([
            'success' => true,
            'settings' => $updatedSettings,
            'multisite' => $settingsService->getMultisiteContext(),
        ], 200);
    }
}
