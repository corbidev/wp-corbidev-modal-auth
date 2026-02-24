<?php

namespace Corbidev\ModalAuth\Core;

class Settings
{
    public static function get(): array
    {
        return get_option('cda_settings', []);
    }

    public static function update(array $data): void
    {
        update_option('cda_settings', $data);
    }
}