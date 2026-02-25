<?php

namespace Corbidev\ModalAuth\Core;

if (!defined('ABSPATH')) {
    exit;
}

class EnvFile
{
    private const CLOUDFLARE_SECRET_KEY = 'CORBIDEV_CLOUDFLARE_SECRET';
    private const DB_OPTION_KEY = 'corbidev_modal_auth_cloudflare_secret';

    public function getStorageType(): string
    {
        return $this->useDatabaseStorage() ? 'database' : 'env';
    }

    public function getCloudflareSecret(): string
    {
        if ($this->useDatabaseStorage()) {
            return $this->getCloudflareSecretFromDatabase();
        }

        $path = $this->resolveEnvPath();

        if ($path === '' || !file_exists($path)) {
            return '';
        }

        $content = file_get_contents($path);

        if ($content === false || $content === '') {
            return '';
        }

        return $this->extractEnvValue((string) $content, self::CLOUDFLARE_SECRET_KEY);
    }

    public function setCloudflareSecret(string $value): bool
    {
        $normalized = trim($value);

        if ($this->useDatabaseStorage()) {
            return $this->setCloudflareSecretInDatabase($normalized);
        }

        $path = $this->resolveEnvPath();

        if ($path === '') {
            return false;
        }

        $line = self::CLOUDFLARE_SECRET_KEY . '=' . $this->encodeEnvValue($normalized);

        if (!file_exists($path)) {
            return file_put_contents($path, $line . PHP_EOL) !== false;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            return false;
        }

        $updated = false;

        foreach ($lines as $index => $existingLine) {
            if ($this->isTargetEnvLine($existingLine, self::CLOUDFLARE_SECRET_KEY)) {
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

    private function useDatabaseStorage(): bool
    {
        if (!defined('WP_CONTENT_DIR')) {
            return false;
        }

        return strtolower((string) basename((string) WP_CONTENT_DIR)) === 'wp-content';
    }

    private function getCloudflareSecretFromDatabase(): string
    {
        $value = get_option(self::DB_OPTION_KEY, '');

        return is_string($value) ? $value : '';
    }

    private function setCloudflareSecretInDatabase(string $value): bool
    {
        $current = get_option(self::DB_OPTION_KEY, null);

        if (is_string($current) && $current === $value) {
            return true;
        }

        return update_option(self::DB_OPTION_KEY, $value, false);
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
