<?php

namespace Corbidev\ModalAuth\Core;

if (!defined('ABSPATH')) {
    exit;
}

class EnvFile
{
    private const TURNSTILE_SITE_KEY = 'TURNSTILE_SITE_KEY';
    private const TURNSTILE_SECRET_KEY = 'TURNSTILE_SECRET_KEY';
    private const LEGACY_CLOUDFLARE_SECRET_KEY = 'CORBIDEV_CLOUDFLARE_SECRET';
    private const DB_OPTION_TURNSTILE_SITE_KEY = 'corbidev_modal_auth_turnstile_site_key';
    private const DB_OPTION_TURNSTILE_SECRET_KEY = 'corbidev_modal_auth_turnstile_secret_key';
    private const LEGACY_DB_OPTION_CLOUDFLARE_SECRET = 'corbidev_modal_auth_cloudflare_secret';

    public function getStorageType(): string
    {
        return $this->useDatabaseStorage() ? 'database' : 'env';
    }

    public function getCloudflareSecret(): string
    {
        return $this->getTurnstileSecretKey();
    }

    public function setCloudflareSecret(string $value): bool
    {
        return $this->setTurnstileSecretKey($value);
    }

    public function getTurnstileSiteKey(): string
    {
        if ($this->useDatabaseStorage()) {
            return $this->getOptionString(self::DB_OPTION_TURNSTILE_SITE_KEY);
        }

        return $this->getEnvValue(self::TURNSTILE_SITE_KEY);
    }

    public function setTurnstileSiteKey(string $value): bool
    {
        $normalized = trim($value);

        if ($this->useDatabaseStorage()) {
            return $this->setOptionString(self::DB_OPTION_TURNSTILE_SITE_KEY, $normalized);
        }

        return $this->setEnvValue(self::TURNSTILE_SITE_KEY, $normalized);
    }

    public function getTurnstileSecretKey(): string
    {
        if ($this->useDatabaseStorage()) {
            $value = $this->getOptionString(self::DB_OPTION_TURNSTILE_SECRET_KEY);
            if ($value !== '') {
                return $value;
            }

            return $this->getOptionString(self::LEGACY_DB_OPTION_CLOUDFLARE_SECRET);
        }

        $secret = $this->getEnvValue(self::TURNSTILE_SECRET_KEY);
        if ($secret !== '') {
            return $secret;
        }

        return $this->getEnvValue(self::LEGACY_CLOUDFLARE_SECRET_KEY);
    }

    public function setTurnstileSecretKey(string $value): bool
    {
        $normalized = trim($value);

        if ($this->useDatabaseStorage()) {
            return $this->setOptionString(self::DB_OPTION_TURNSTILE_SECRET_KEY, $normalized);
        }

        return $this->setEnvValue(self::TURNSTILE_SECRET_KEY, $normalized);
    }

    private function useDatabaseStorage(): bool
    {
        // In multisite, sub-sites need site-specific credentials.
        // Their options table is per blog, unlike the shared .env file.
        if (function_exists('is_multisite') && is_multisite()) {
            if (!function_exists('is_main_site') || !is_main_site()) {
                return true;
            }
        }

        if (!defined('WP_CONTENT_DIR')) {
            return false;
        }

        return strtolower((string) basename((string) WP_CONTENT_DIR)) === 'wp-content';
    }

    private function getOptionString(string $optionKey): string
    {
        $value = get_option($optionKey, '');

        return is_string($value) ? $value : '';
    }

    private function setOptionString(string $optionKey, string $value): bool
    {
        $current = get_option($optionKey, null);

        if (is_string($current) && $current === $value) {
            return true;
        }

        return update_option($optionKey, $value, false);
    }

    private function getEnvValue(string $key): string
    {
        $path = $this->resolveEnvPath();

        if ($path === '' || !file_exists($path)) {
            return '';
        }

        $content = file_get_contents($path);

        if ($content === false || $content === '') {
            return '';
        }

        return $this->extractEnvValue((string) $content, $key);
    }

    private function setEnvValue(string $key, string $value): bool
    {
        $path = $this->resolveEnvPath();

        if ($path === '') {
            return false;
        }

        $line = $key . '=' . $this->encodeEnvValue($value);

        if (!file_exists($path)) {
            return file_put_contents($path, $line . PHP_EOL) !== false;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            return false;
        }

        $updated = false;

        foreach ($lines as $index => $existingLine) {
            if ($this->isTargetEnvLine($existingLine, $key)) {
                $lines[$index] = $line;
                $updated = true;
                break;
            }
        }

        if (!$updated) {
            $lines[] = $line;
        }

        $output = implode(PHP_EOL, $lines);

        if (!str_ends_with($output, PHP_EOL)) {
            $output .= PHP_EOL;
        }

        return file_put_contents($path, $output) !== false;
    }

    private function resolveEnvPath(): string
    {
        $candidates = $this->buildCandidatePaths();

        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                return $candidate;
            }
        }

        return $candidates[0] ?? '';
    }

    private function buildCandidatePaths(): array
    {
        $paths = [];

        if (defined('WP_CONTENT_DIR')) {
            $paths[] = rtrim(dirname(WP_CONTENT_DIR), '/\\') . DIRECTORY_SEPARATOR . '.env';
            $paths[] = rtrim(dirname(dirname(WP_CONTENT_DIR)), '/\\') . DIRECTORY_SEPARATOR . '.env';
        }

        if (defined('CDA_PLUGIN_PATH')) {
            $paths[] = rtrim(dirname(CDA_PLUGIN_PATH, 4), '/\\') . DIRECTORY_SEPARATOR . '.env';
            $paths[] = rtrim(dirname(CDA_PLUGIN_PATH, 3), '/\\') . DIRECTORY_SEPARATOR . '.env';
        }

        if (defined('ABSPATH')) {
            $paths[] = rtrim(ABSPATH, '/\\') . DIRECTORY_SEPARATOR . '.env';
            $paths[] = rtrim(dirname(ABSPATH), '/\\') . DIRECTORY_SEPARATOR . '.env';
            $paths[] = rtrim(dirname(dirname(ABSPATH)), '/\\') . DIRECTORY_SEPARATOR . '.env';
        }

        return array_values(array_unique(array_filter($paths)));
    }

    private function isTargetEnvLine(string $line, string $key): bool
    {
        $pattern = '/^\s*' . preg_quote($key, '/') . '\s*=/';
        return preg_match($pattern, $line) === 1;
    }

    private function extractEnvValue(string $content, string $key): string
    {
        $pattern = '/^\s*' . preg_quote($key, '/') . '\s*=\s*(.*)\s*$/m';
        if (preg_match($pattern, $content, $matches) !== 1) {
            return '';
        }

        $raw = trim((string) ($matches[1] ?? ''));
        if ($raw === '') {
            return '';
        }

        if (
            (str_starts_with($raw, '"') && str_ends_with($raw, '"')) ||
            (str_starts_with($raw, "'") && str_ends_with($raw, "'"))
        ) {
            $raw = substr($raw, 1, -1);
        }

        return stripcslashes($raw);
    }

    private function encodeEnvValue(string $value): string
    {
        return '"' . addcslashes($value, "\\\"") . '"';
    }
}
