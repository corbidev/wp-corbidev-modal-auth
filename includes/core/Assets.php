<?php

namespace Corbidev\ModalAuth\Core;

class Assets {

    public function register(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue(): void
    {
        wp_enqueue_script(
            'cda-app',
            CDA_PLUGIN_URL . 'assets/dist/main.js',
            [],
            CDA_VERSION,
            true
        );

        wp_localize_script(
            'cda-app',
            'CDA_CONFIG',
            [
                'rest_url' => rest_url('corbidev/v1/login'),
                'nonce'    => wp_create_nonce('wp_rest'),
                'translations' => [
                    'login' => __('Login', 'corbidevmodalauth'),
                    'username' => __('Username', 'corbidevmodalauth'),
                    'password' => __('Password', 'corbidevmodalauth'),
                    'error' => __('Invalid credentials', 'corbidevmodalauth'),
                ]
            ]
        );
    }
}