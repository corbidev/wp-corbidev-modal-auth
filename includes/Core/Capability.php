<?php

namespace Corbidev\ModalAuth\Core;

class Capability
{
    public static function canAccessAdmin(): bool
    {
        return current_user_can('manage_options');
    }
}