<?php

namespace Corbidev\ModalAuth\Core;

use Corbidev\ModalAuth\Core\AuthService;
use Corbidev\ModalAuth\Core\Settings;

class Rest {

    public function register(): void
    {
        add_action('rest_api_init', function () {

            /*
            |--------------------------------------------------------------------------
            | LOGIN
            |--------------------------------------------------------------------------
            */

            register_rest_route(
                'corbidev/v1',
                '/login',
                [
                    'methods'  => 'POST',
                    'callback' => [$this, 'login'],
                    'permission_callback' => '__return_true'
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | SETTINGS (ADMIN)
            |--------------------------------------------------------------------------
            */

            register_rest_route(
                'corbidev/v1',
                '/settings',
                [
                    'methods'  => 'POST',
                    'callback' => [$this, 'saveSettings'],
                    'permission_callback' => function () {
                        return current_user_can('manage_options');
                    }
                ]
            );

        });
    }

    public function login($request)
    {
        $params = $request->get_json_params();

        return (new AuthService())->login(
            $params['username'] ?? '',
            $params['password'] ?? ''
        );
    }

    public function saveSettings($request)
    {
        Settings::update(
            $request->get_json_params()
        );

        return [
            'success' => true
        ];
    }
}