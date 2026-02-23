<?php

namespace Corbidev\ModalAuth\Core;

class Rest {

    public function register(): void
    {
        add_action('rest_api_init', function () {

            register_rest_route(
                'corbidev/v1',
                '/login',
                [
                    'methods'  => 'POST',
                    'callback' => [$this, 'login'],
                    'permission_callback' => '__return_true'
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
}