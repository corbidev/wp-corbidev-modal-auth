<?php

namespace Corbidev\ModalAuth\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Assets
{
    private const FRONT_HANDLE = 'corbidev-modal-auth-app';
    private const ADMIN_HANDLE = 'corbidev-modal-auth-admin';

    /**
     * Initialisation globale
     */
    public function register(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontend']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdmin']);
    }

    /**
     * FRONTEND
     */
    public function enqueueFrontend(): void
    {
        $manifest = $this->getManifest();

        if (!isset($manifest['assets/src/main.js'])) {
            return;
        }

        $entry = $manifest['assets/src/main.js'];

        wp_enqueue_script(
            self::FRONT_HANDLE,
            $this->assetUrl($entry['file']),
            [],
            null,
            true
        );

        $this->localize(self::FRONT_HANDLE);
    }

    /**
     * ADMIN (chargé uniquement sur page plugin)
     */
    public function enqueueAdmin(string $hook): void
    {
        if ($hook !== 'toplevel_page_corbidev-modal-auth') {
            return;
        }

        $manifest = $this->getManifest();

        if (!isset($manifest['assets/src/admin/admin.js'])) {
            return;
        }

        $entry = $manifest['assets/src/admin/admin.js'];

        wp_enqueue_script(
            self::ADMIN_HANDLE,
            $this->assetUrl($entry['file']),
            [],
            null,
            true
        );

        $this->localize(self::ADMIN_HANDLE);
    }

    /**
     * Injection configuration JS
     */
    private function localize(string $handle): void
    {
        $settings = (new Settings())->get();

        wp_localize_script(
            $handle,
            'CorbidevModalAuth',
            [
                'restUrl'  => esc_url_raw(rest_url('corbidev-modal-auth/v1')),
                'nonce'    => wp_create_nonce('wp_rest'),
                'settings' => $settings,
                'i18n'     => [
                    'login'                 => __('Login', 'corbidevmodalauth'),
                    'logout'                => __('Logout', 'corbidevmodalauth'),
                    'username'              => __('Username', 'corbidevmodalauth'),
                    'password'              => __('Password', 'corbidevmodalauth'),
                    'remember_me'           => __('Remember me', 'corbidevmodalauth'),
                    'forgot_password'       => __('Forgot password?', 'corbidevmodalauth'),
                    'reset_password'        => __('Reset password', 'corbidevmodalauth'),
                    'back_to_login'         => __('Back to login', 'corbidevmodalauth'),
                    'loading'               => __('Loading...', 'corbidevmodalauth'),
                    'invalid_credentials'   => __('Invalid credentials', 'corbidevmodalauth'),
                    'too_many_attempts'     => __('Too many attempts. Please try later.', 'corbidevmodalauth'),
                    'rate_limited'          => __('Too many requests. Please slow down.', 'corbidevmodalauth'),
                    'modal_settings'        => __('Modal Settings', 'corbidevmodalauth'),
                    'enable_modal'          => __('Enable Modal', 'corbidevmodalauth'),
                    'redirect_after_login'  => __('Redirect After Login', 'corbidevmodalauth'),
                    'redirect_after_logout' => __('Redirect After Logout', 'corbidevmodalauth'),
                    'security'              => __('Security', 'corbidevmodalauth'),
                    'max_login_attempts'    => __('Max Login Attempts', 'corbidevmodalauth'),
                    'lock_time_seconds'     => __('Lock Time (seconds)', 'corbidevmodalauth'),
                    'rest_max_requests'     => __('REST Max Requests', 'corbidevmodalauth'),
                    'rest_window_seconds'   => __('REST Window (seconds)', 'corbidevmodalauth'),
                    'save'                  => __('Save', 'corbidevmodalauth'),
                    'saving'                => __('Saving...', 'corbidevmodalauth'),
                    'saved'                 => __('Settings saved', 'corbidevmodalauth'),
                    'error'                 => __('An error occurred', 'corbidevmodalauth'),
                ],
            ]
        );
    }

    /**
     * Lecture manifest Vite
     */
    private function getManifest(): array
    {
        $path = plugin_dir_path(dirname(__DIR__, 2)) . 'assets/dist/manifest.json';

        if (!file_exists($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true) ?? [];
    }

    /**
     * Génération URL asset
     */
    private function assetUrl(string $file): string
    {
        return plugin_dir_url(dirname(__DIR__, 2)) . 'assets/dist/' . $file;
    }
}