<?php

namespace Corbidev\ModalAuth\Core;

if (!defined('ABSPATH')) {
    exit;
}

class SecurityService
{
    private const MAX_ATTEMPTS = 5;
    private const LOCK_TIME = 900; // 15 minutes

    private function getIp(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    private function getKey(): string
    {
        return 'corbidev_modal_auth_attempts_' . md5($this->getIp());
    }

    public function isBlocked(): bool
    {
        $data = get_transient($this->getKey());

        if (!$data) {
            return false;
        }

        if ($data['count'] >= self::MAX_ATTEMPTS) {
            return true;
        }

        return false;
    }

    public function registerFailure(): void
    {
        $key = $this->getKey();
        $data = get_transient($key);

        if (!$data) {
            $data = [
                'count' => 1,
                'time'  => time(),
            ];
        } else {
            $data['count']++;
        }

        set_transient($key, $data, self::LOCK_TIME);
    }

    public function reset(): void
    {
        delete_transient($this->getKey());
    }
}