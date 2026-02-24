<?php

namespace Corbidev\ModalAuth\Core;

class Installer
{
    public static function activate(): void
    {
        add_option('cda_settings', [
            'enabled' => true,
            'redirect_after_login' => home_url(),
            'allowed_roles' => ['subscriber']
        ]);
    }
}