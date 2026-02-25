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

        $allowedPositions = ['bottom-right', 'bottom-left', 'top-right', 'top-left'];
        $allowedSizes = ['sm', 'md', 'lg'];

        $floatingPosition = sanitize_key((string) ($input['floating_position'] ?? $defaults['floating_position']));
        if (!in_array($floatingPosition, $allowedPositions, true)) {
            $floatingPosition = $defaults['floating_position'];
        }

        $floatingSizeMobile = sanitize_key((string) ($input['floating_size_mobile'] ?? $defaults['floating_size_mobile']));
        if (!in_array($floatingSizeMobile, $allowedSizes, true)) {
            $floatingSizeMobile = $defaults['floating_size_mobile'];
        }

        $floatingSizeTablet = sanitize_key((string) ($input['floating_size_tablet'] ?? $defaults['floating_size_tablet']));
        if (!in_array($floatingSizeTablet, $allowedSizes, true)) {
            $floatingSizeTablet = $defaults['floating_size_tablet'];
        }

        $floatingSizeDesktop = sanitize_key((string) ($input['floating_size_desktop'] ?? $defaults['floating_size_desktop']));
        if (!in_array($floatingSizeDesktop, $allowedSizes, true)) {
            $floatingSizeDesktop = $defaults['floating_size_desktop'];
        }

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

            // Cloudflare
            'cloudflare_enabled'      => !empty($input['cloudflare_enabled']),

            // Floating button
            'floating_position'       => $floatingPosition,
            'floating_size_mobile'    => $floatingSizeMobile,
            'floating_size_tablet'    => $floatingSizeTablet,
            'floating_size_desktop'   => $floatingSizeDesktop,
            'floating_custom_classes' => sanitize_textarea_field((string) ($input['floating_custom_classes'] ?? $defaults['floating_custom_classes'])),
            'floating_label_login'    => sanitize_text_field((string) ($input['floating_label_login'] ?? $defaults['floating_label_login'])),
            'floating_label_logout'   => sanitize_text_field((string) ($input['floating_label_logout'] ?? $defaults['floating_label_logout'])),
            'show_label_mobile'       => !empty($input['show_label_mobile']),
            'show_label_tablet'       => !empty($input['show_label_tablet']),
            'show_label_desktop'      => !empty($input['show_label_desktop']),
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

            // Cloudflare
            'cloudflare_enabled'    => false,

            // Floating button
            'floating_position'     => 'bottom-right',
            'floating_size_mobile'  => 'md',
            'floating_size_tablet'  => 'md',
            'floating_size_desktop' => 'md',
            'floating_custom_classes' => '',
            'floating_label_login'  => 'Connexion',
            'floating_label_logout' => 'Déconnexion',
            'show_label_mobile'     => true,
            'show_label_tablet'     => true,
            'show_label_desktop'    => true,
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
