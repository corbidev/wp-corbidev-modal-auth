<?php

namespace Corbidev\ModalAuth\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Settings
{
    private const OPTION_KEY = 'corbidev_modal_auth_settings';
    private const OPTION_GROUP = 'corbidev_modal_auth_group';
    private const NETWORK_RULES_KEY = 'corbidev_modal_auth_network_rules';
    private const SITE_OVERRIDES_KEY = 'corbidev_modal_auth_site_overrides';

    public static function getManagedKeys(): array
    {
        return [
            'enable_modal',
            'redirect_after_login',
            'redirect_after_logout',
            'enable_remember_me',
            'enable_lost_password',
            'auto_open',
            'hide_for_logged_users',
            'security_max_attempts',
            'security_lock_time',
            'rest_max_requests',
            'rest_window',
            'cloudflare_enabled',
            'floating_position',
            'floating_size_mobile',
            'floating_size_tablet',
            'floating_size_desktop',
            'floating_custom_classes',
            'floating_label_login',
            'floating_label_logout',
            'show_label_mobile',
            'show_label_tablet',
            'show_label_desktop',
        ];
    }

    public static function getNetworkControlKeys(): array
    {
        return array_merge(self::getManagedKeys(), ['turnstile_site_key', 'cloudflare_secret']);
    }

    /**
     * Initialisation
     */
    public function init(): void
    {
        add_action('admin_init', [$this, 'register']);
    }

    /**
     * Enregistrement WordPress
     */
    public function register(): void
    {
        register_setting(
            self::OPTION_GROUP,
            self::OPTION_KEY,
            [
                'type'              => 'array',
                'sanitize_callback' => [$this, 'sanitize'],
                'default'           => $this->getDefaults(),
            ]
        );
    }

    /**
     * Nettoyage et normalisation
     */
    public function sanitize(array $input): array
    {
        $defaults = $this->getDefaults();

        $allowedPositions = ['bottom-right', 'bottom-left', 'top-right', 'top-left'];
        $allowedSizes = ['sm', 'md', 'lg'];

        $floatingPosition = sanitize_key((string) ($input['floating_position'] ?? $defaults['floating_position']));
        if (!in_array($floatingPosition, $allowedPositions, true)) {
            $floatingPosition = $defaults['floating_position'];
        }

        $floatingSizeMobile = sanitize_key((string) ($input['floating_size_mobile'] ?? $defaults['floating_size_mobile']));
        if (!in_array($floatingSizeMobile, $allowedSizes, true)) {
            $floatingSizeMobile = $defaults['floating_size_mobile'];
        }

        $floatingSizeTablet = sanitize_key((string) ($input['floating_size_tablet'] ?? $defaults['floating_size_tablet']));
        if (!in_array($floatingSizeTablet, $allowedSizes, true)) {
            $floatingSizeTablet = $defaults['floating_size_tablet'];
        }

        $floatingSizeDesktop = sanitize_key((string) ($input['floating_size_desktop'] ?? $defaults['floating_size_desktop']));
        if (!in_array($floatingSizeDesktop, $allowedSizes, true)) {
            $floatingSizeDesktop = $defaults['floating_size_desktop'];
        }

        return [
            // UI
            'enable_modal'          => !empty($input['enable_modal']),
            'redirect_after_login'  => esc_url_raw($input['redirect_after_login'] ?? $defaults['redirect_after_login']),
            'redirect_after_logout' => esc_url_raw($input['redirect_after_logout'] ?? $defaults['redirect_after_logout']),
            'enable_remember_me'    => !empty($input['enable_remember_me']),
            'enable_lost_password'  => !empty($input['enable_lost_password']),
            'auto_open'             => !empty($input['auto_open']),
            'hide_for_logged_users' => !empty($input['hide_for_logged_users']),

            // Securite brute force
            'security_max_attempts' => max(1, (int) ($input['security_max_attempts'] ?? $defaults['security_max_attempts'])),
            'security_lock_time'    => max(60, (int) ($input['security_lock_time'] ?? $defaults['security_lock_time'])),

            // Rate limit REST
            'rest_max_requests'     => max(1, (int) ($input['rest_max_requests'] ?? $defaults['rest_max_requests'])),
            'rest_window'           => max(10, (int) ($input['rest_window'] ?? $defaults['rest_window'])),

            // Cloudflare
            'cloudflare_enabled'    => !empty($input['cloudflare_enabled']),

            // Floating button
            'floating_position'       => $floatingPosition,
            'floating_size_mobile'    => $floatingSizeMobile,
            'floating_size_tablet'    => $floatingSizeTablet,
            'floating_size_desktop'   => $floatingSizeDesktop,
            'floating_custom_classes' => sanitize_textarea_field((string) ($input['floating_custom_classes'] ?? $defaults['floating_custom_classes'])),
            'floating_label_login'    => sanitize_text_field((string) ($input['floating_label_login'] ?? $defaults['floating_label_login'])),
            'floating_label_logout'   => sanitize_text_field((string) ($input['floating_label_logout'] ?? $defaults['floating_label_logout'])),
            'show_label_mobile'       => !empty($input['show_label_mobile']),
            'show_label_tablet'       => !empty($input['show_label_tablet']),
            'show_label_desktop'      => !empty($input['show_label_desktop']),
        ];
    }

    /**
     * Valeurs par defaut
     */
    public function getDefaults(): array
    {
        return [
            // UI
            'enable_modal'          => true,
            'redirect_after_login'  => home_url(),
            'redirect_after_logout' => home_url(),
            'enable_remember_me'    => true,
            'enable_lost_password'  => true,
            'auto_open'             => false,
            'hide_for_logged_users' => true,

            // Securite brute force
            'security_max_attempts' => 5,
            'security_lock_time'    => 900,

            // Rate limit REST
            'rest_max_requests'     => 30,
            'rest_window'           => 60,

            // Cloudflare
            'cloudflare_enabled'    => false,

            // Floating button
            'floating_position'       => 'bottom-right',
            'floating_size_mobile'    => 'md',
            'floating_size_tablet'    => 'md',
            'floating_size_desktop'   => 'md',
            'floating_custom_classes' => '',
            'floating_label_login'    => 'Connexion',
            'floating_label_logout'   => 'Deconnexion',
            'show_label_mobile'       => true,
            'show_label_tablet'       => true,
            'show_label_desktop'      => true,
        ];
    }

    /**
     * Recuperation normalisee (inclut les regles multisite)
     */
    public function get(): array
    {
        $local = $this->getLocalSettings();

        if (!is_multisite()) {
            return $local;
        }

        return $this->applyNetworkDefaults($local);
    }

    /**
     * Mise a jour centralisee
     */
    public function update(array $data, ?array $networkControls = null): bool
    {
        $sanitized = $this->sanitize($data);

        if (is_multisite() && !is_main_site()) {
            $sanitized = $this->enforceLockedOnSubSite($sanitized);
            $this->saveSiteOverridesFromValues($sanitized);
        }

        $updated = update_option(self::OPTION_KEY, $sanitized);

        if (is_multisite() && is_main_site() && is_array($networkControls)) {
            $this->saveNetworkRules($networkControls);
        }

        return $updated;
    }

    public function getMultisiteContext(): array
    {
        if (!is_multisite()) {
            return [
                'enabled' => false,
            ];
        }

        $rules = $this->getNetworkRules();
        $locked = [];
        foreach (self::getNetworkControlKeys() as $key) {
            $locked[$key] = !is_main_site()
                && !empty($rules['defaults'][$key])
                && !empty($rules['locks'][$key]);
        }

        return [
            'enabled'  => true,
            'is_master' => is_main_site(),
            'controls' => $rules,
            'locked'   => $locked,
        ];
    }

    public function isNetworkDefaultEnabled(string $key): bool
    {
        if (!in_array($key, self::getNetworkControlKeys(), true)) {
            return false;
        }

        $rules = $this->getNetworkRules();
        return !empty($rules['defaults'][$key]);
    }

    public function isFieldLockedForCurrentSite(string $key): bool
    {
        if (!is_multisite() || is_main_site()) {
            return false;
        }

        if (!in_array($key, self::getNetworkControlKeys(), true)) {
            return false;
        }

        $rules = $this->getNetworkRules();
        return !empty($rules['defaults'][$key]) && !empty($rules['locks'][$key]);
    }

    public function shouldUseNetworkDefaultForCurrentSite(string $key): bool
    {
        if (!is_multisite() || is_main_site()) {
            return false;
        }

        if (!in_array($key, self::getNetworkControlKeys(), true)) {
            return false;
        }

        $rules = $this->getNetworkRules();
        if (empty($rules['defaults'][$key])) {
            return false;
        }

        if (!empty($rules['locks'][$key])) {
            return true;
        }

        $overrides = $this->getSiteOverrides();
        return empty($overrides[$key]);
    }

    public function setSiteOverride(string $key, bool $override): void
    {
        if (!is_multisite() || is_main_site()) {
            return;
        }

        if (!in_array($key, self::getNetworkControlKeys(), true)) {
            return;
        }

        $overrides = $this->getSiteOverrides();
        $overrides[$key] = $override;
        update_option(self::SITE_OVERRIDES_KEY, $overrides, false);
    }

    public function getEffectiveCloudflareSecret(): string
    {
        return $this->getEffectiveTurnstileSecret();
    }

    public function getEffectiveTurnstileSiteKey(): string
    {
        $env = new EnvFile();
        $localSiteKey = $env->getTurnstileSiteKey();

        if (!$this->shouldUseNetworkDefaultForCurrentSite('turnstile_site_key')) {
            return $localSiteKey;
        }

        return $this->getMasterTurnstileSiteKey();
    }

    public function getEffectiveTurnstileSecret(): string
    {
        $env = new EnvFile();
        $localSecret = $env->getTurnstileSecretKey();

        if (!$this->shouldUseNetworkDefaultForCurrentSite('cloudflare_secret')) {
            return $localSecret;
        }

        return $this->getMasterTurnstileSecret();
    }

    public function getMasterCloudflareSecret(): string
    {
        return $this->getMasterTurnstileSecret();
    }

    public function getMasterTurnstileSiteKey(): string
    {
        $mainSiteId = function_exists('get_main_site_id') ? (int) get_main_site_id() : 1;

        if (!is_multisite() || (int) get_current_blog_id() === $mainSiteId) {
            return (new EnvFile())->getTurnstileSiteKey();
        }

        switch_to_blog($mainSiteId);
        $siteKey = (new EnvFile())->getTurnstileSiteKey();
        restore_current_blog();

        return $siteKey;
    }

    public function getMasterTurnstileSecret(): string
    {
        $mainSiteId = function_exists('get_main_site_id') ? (int) get_main_site_id() : 1;

        if (!is_multisite() || (int) get_current_blog_id() === $mainSiteId) {
            return (new EnvFile())->getTurnstileSecretKey();
        }

        switch_to_blog($mainSiteId);
        $secret = (new EnvFile())->getTurnstileSecretKey();
        restore_current_blog();

        return $secret;
    }

    private function getLocalSettings(): array
    {
        $options = get_option(self::OPTION_KEY, []);
        if (!is_array($options)) {
            $options = [];
        }

        return wp_parse_args($options, $this->getDefaults());
    }

    private function getMasterSettings(): array
    {
        if (!is_multisite()) {
            return $this->getLocalSettings();
        }

        $mainSiteId = function_exists('get_main_site_id') ? (int) get_main_site_id() : 1;

        if ((int) get_current_blog_id() === $mainSiteId) {
            return $this->getLocalSettings();
        }

        switch_to_blog($mainSiteId);
        $options = get_option(self::OPTION_KEY, []);
        restore_current_blog();

        if (!is_array($options)) {
            $options = [];
        }

        return wp_parse_args($options, $this->getDefaults());
    }

    private function applyNetworkDefaults(array $local): array
    {
        if (is_main_site()) {
            return $local;
        }

        $rules = $this->getNetworkRules();
        $overrides = $this->getSiteOverrides();
        $master = $this->getMasterSettings();
        $effective = $local;

        foreach (self::getManagedKeys() as $key) {
            $useNetworkDefault = !empty($rules['defaults'][$key]) && (
                !empty($rules['locks'][$key]) || empty($overrides[$key])
            );

            if ($useNetworkDefault) {
                $effective[$key] = $master[$key] ?? $effective[$key];
            }
        }

        return $effective;
    }

    private function enforceLockedOnSubSite(array $sanitized): array
    {
        $rules = $this->getNetworkRules();
        $master = $this->getMasterSettings();

        foreach (self::getManagedKeys() as $key) {
            if (!empty($rules['defaults'][$key]) && !empty($rules['locks'][$key])) {
                $sanitized[$key] = $master[$key] ?? $sanitized[$key];
            }
        }

        return $sanitized;
    }

    private function saveSiteOverridesFromValues(array $values): void
    {
        $rules = $this->getNetworkRules();
        $master = $this->getMasterSettings();
        $overrides = $this->getSiteOverrides();

        foreach (self::getManagedKeys() as $key) {
            if (empty($rules['defaults'][$key]) || !empty($rules['locks'][$key])) {
                $overrides[$key] = false;
                continue;
            }

            $overrides[$key] = !$this->valuesEqual($values[$key] ?? null, $master[$key] ?? null);
        }

        update_option(self::SITE_OVERRIDES_KEY, $overrides, false);
    }

    private function valuesEqual(mixed $a, mixed $b): bool
    {
        return wp_json_encode($a) === wp_json_encode($b);
    }

    private function getSiteOverrides(): array
    {
        $keys = self::getNetworkControlKeys();
        $raw = get_option(self::SITE_OVERRIDES_KEY, []);

        if (!is_array($raw)) {
            $raw = [];
        }

        $overrides = [];
        foreach ($keys as $key) {
            $overrides[$key] = !empty($raw[$key]);
        }

        return $overrides;
    }

    private function getNetworkRules(): array
    {
        $keys = self::getNetworkControlKeys();
        $rules = [
            'defaults' => [],
            'locks' => [],
        ];

        foreach ($keys as $key) {
            $rules['defaults'][$key] = false;
            $rules['locks'][$key] = false;
        }

        if (!is_multisite()) {
            return $rules;
        }

        $raw = get_site_option(self::NETWORK_RULES_KEY, []);
        if (!is_array($raw)) {
            return $rules;
        }

        $rawDefaults = isset($raw['defaults']) && is_array($raw['defaults']) ? $raw['defaults'] : [];
        $rawLocks = isset($raw['locks']) && is_array($raw['locks']) ? $raw['locks'] : [];

        foreach ($keys as $key) {
            $rules['defaults'][$key] = !empty($rawDefaults[$key]);
            $rules['locks'][$key] = $rules['defaults'][$key] && !empty($rawLocks[$key]);
        }

        return $rules;
    }

    private function saveNetworkRules(array $input): void
    {
        if (!is_multisite() || !is_main_site()) {
            return;
        }

        $keys = self::getNetworkControlKeys();
        $rawDefaults = isset($input['defaults']) && is_array($input['defaults']) ? $input['defaults'] : [];
        $rawLocks = isset($input['locks']) && is_array($input['locks']) ? $input['locks'] : [];

        $sanitized = [
            'defaults' => [],
            'locks' => [],
        ];

        foreach ($keys as $key) {
            $isDefault = !empty($rawDefaults[$key]);
            $sanitized['defaults'][$key] = $isDefault;
            $sanitized['locks'][$key] = $isDefault && !empty($rawLocks[$key]);
        }

        update_site_option(self::NETWORK_RULES_KEY, $sanitized);
    }
}
