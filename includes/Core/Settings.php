<?php

namespace Corbidev\ModalAuth\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Settings
{
    private const OPTION_KEY = 'corbidev_modal_auth_settings';
    private const OPTION_GROUP = 'corbidev_modal_auth_group';

    /**
     * Initialisation
     */
    public function init(): void
    {
        add_action('admin_init', [$this, 'register']);
    }

    /**
     * Enregistrement WordPress
     */
    public function register(): void
    {
        register_setting(
            self::OPTION_GROUP,
            self::OPTION_KEY,
            [
                'type'              => 'array',
                'sanitize_callback' => [$this, 'sanitize'],
                'default'           => $this->getDefaults(),
            ]
        );
    }

    /**
     * Nettoyage & normalisation
     */
    public function sanitize(array $input): array
    {
        $defaults = $this->getDefaults();

        return [
            // UI
            'enable_modal'            => !empty($input['enable_modal']),
            'redirect_after_login'    => esc_url_raw($input['redirect_after_login'] ?? $defaults['redirect_after_login']),
            'redirect_after_logout'   => esc_url_raw($input['redirect_after_logout'] ?? $defaults['redirect_after_logout']),
            'enable_remember_me'      => !empty($input['enable_remember_me']),
            'enable_lost_password'    => !empty($input['enable_lost_password']),
            'auto_open'               => !empty($input['auto_open']),
            'hide_for_logged_users'   => !empty($input['hide_for_logged_users']),

            // Sécurité brute force
            'security_max_attempts'   => max(1, (int) ($input['security_max_attempts'] ?? $defaults['security_max_attempts'])),
            'security_lock_time'      => max(60, (int) ($input['security_lock_time'] ?? $defaults['security_lock_time'])),

            // Rate limit REST
            'rest_max_requests'       => max(1, (int) ($input['rest_max_requests'] ?? $defaults['rest_max_requests'])),
            'rest_window'             => max(10, (int) ($input['rest_window'] ?? $defaults['rest_window'])),
        ];
    }

    /**
     * Valeurs par défaut
     */
    public function getDefaults(): array
    {
        return [
            // UI
            'enable_modal'          => true,
            'redirect_after_login'  => home_url(),
            'redirect_after_logout' => home_url(),
            'enable_remember_me'    => true,
            'enable_lost_password'  => true,
            'auto_open'             => false,
            'hide_for_logged_users' => true,

            // Sécurité brute force
            'security_max_attempts' => 5,
            'security_lock_time'    => 900,

            // Rate limit REST
            'rest_max_requests'     => 30,
            'rest_window'           => 60,
        ];
    }

    /**
     * Récupération normalisée
     */
    public function get(): array
    {
        $options = get_option(self::OPTION_KEY, []);

        return wp_parse_args($options, $this->getDefaults());
    }

    /**
     * Mise à jour centralisée
     */
    public function update(array $data): bool
    {
        return update_option(self::OPTION_KEY, $this->sanitize($data));
    }
}