<?php

namespace Corbidev\ModalAuth\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Plugin
{
    private Settings $settings;
    private Rest $rest;
    private Assets $assets;

    /**
     * Initialisation globale
     */
    public function init(): void
    {
        $this->settings = new Settings();
        $this->settings->init();

        $this->assets = new Assets();
        $this->assets->register();

        $options = $this->settings->get();

        // Injection sécurité configurable
        $security = new SecurityService(
            $options['security_max_attempts'],
            $options['security_lock_time']
        );

        $authService = new AuthService($security);

        $rateLimiter = new RestRateLimiter(
            $options['rest_max_requests'],
            $options['rest_window']
        );

        $this->rest = new Rest($authService, $rateLimiter);
        $this->rest->register();

        // Hooks WordPress
        add_action('wp_login', [$this, 'onLogin'], 10, 2);
        add_action('wp_logout', [$this, 'onLogout']);

        add_filter('login_redirect', [$this, 'redirectAfterLogin'], 10, 3);
        add_filter('logout_redirect', [$this, 'redirectAfterLogout'], 10, 3);
        add_action('wp_footer', [$this, 'renderFrontendMount']);
        add_action('admin_menu', function () {
            add_menu_page(
                'CorbiDev Modal Auth',
                'Modal Auth',
                'manage_options',
                'corbidev-modal-auth',
                function () {
                    include plugin_dir_path(__FILE__) . '../../admin/pages/mount.php';
                },
                'dashicons-lock'
            );
        });
    }

    public function renderFrontendMount(): void
    {
        include plugin_dir_path(__FILE__) . '../../public/mount.php';
    }

    /**
     * Action après login
     */
    public function onLogin(string $user_login, \WP_User $user): void
    {
        do_action('corbidev_modal_auth_user_logged_in', $user);
    }

    /**
     * Action après logout
     */
    public function onLogout(): void
    {
        do_action('corbidev_modal_auth_user_logged_out');
    }

    /**
     * Redirection login natif
     */
    public function redirectAfterLogin(
        string $redirect_to,
        string $requested_redirect_to,
        \WP_User|\WP_Error $user
    ): string {
        if ($user instanceof \WP_Error) {
            return $redirect_to;
        }

        return apply_filters(
            'corbidev_modal_auth_login_redirect',
            $redirect_to,
            $user
        );
    }

    /**
     * Redirection logout natif
     */
    public function redirectAfterLogout(
        string $redirect_to,
        string $requested_redirect_to,
        \WP_User $user
    ): string {
        return apply_filters(
            'corbidev_modal_auth_logout_redirect',
            $redirect_to,
            $user
        );
    }
}