<?php

namespace Corbidev\ModalAuth\Core;

class Admin
{
    public function register(): void
    {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    public function menu(): void
    {
        add_menu_page(
            __('Modal Auth', 'corbidevmodalauth'),
            __('Modal Auth', 'corbidevmodalauth'),
            'manage_options',
            'cda-admin',
            [$this, 'render'],
            'dashicons-lock'
        );
    }

    public function render(): void
    {
        echo '<div id="cda-admin-app"></div>';
    }

    public function enqueue(): void
    {
        wp_enqueue_script(
            'cda-admin-app',
            CDA_PLUGIN_URL . 'assets/dist/admin.js',
            [],
            CDA_VERSION,
            true
        );

        wp_localize_script(
            'cda-admin-app',
            'CDA_ADMIN',
            [
                'settings' => Settings::get(),
                'nonce' => wp_create_nonce('wp_rest')
            ]
        );
    }
}