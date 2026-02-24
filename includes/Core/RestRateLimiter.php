<?php

namespace Corbidev\ModalAuth\Core;

if (!defined('ABSPATH')) {
    exit;
}

class RestRateLimiter
{
    private const MAX_REQUESTS = 30;
    private const WINDOW = 60; // secondes

    private function getIp(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    private function getKey(string $route): string
    {
        return 'corbidev_rest_limit_' . md5($this->getIp() . $route);
    }

    public function isAllowed(string $route): bool
    {
        $key = $this->getKey($route);
        $data = get_transient($key);

        if (!$data) {
            $data = [
                'count' => 1,
                'start' => time(),
            ];

            set_transient($key, $data, self::WINDOW);
            return true;
        }

        if ($data['count'] >= self::MAX_REQUESTS) {
            return false;
        }

        $data['count']++;
        set_transient($key, $data, self::WINDOW);

        return true;
    }
}