<?php

namespace Corbidev\ModalAuth\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Assets
{
    private const FRONT_HANDLE = 'corbidev-modal-auth-app';
    private const ADMIN_HANDLE = 'corbidev-modal-auth-admin';

    /**
     * Initialisation globale
     */
    public function register(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontend']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdmin']);
        add_filter('script_loader_tag', [$this, 'filterScriptLoaderTag'], 10, 3);
    }

    /**
     * Force les scripts Vite en module
     */
    public function filterScriptLoaderTag(string $tag, string $handle, string $src): string
    {
        if (!in_array($handle, [self::FRONT_HANDLE, self::ADMIN_HANDLE], true)) {
            return $tag;
        }

        if (str_contains($tag, ' type=')) {
            return $tag;
        }

        return str_replace('<script ', '<script type="module" ', $tag);
    }

    /**
     * FRONTEND
     */
    public function enqueueFrontend(): void
    {
        $manifest = $this->getManifest();

        $this->enqueueAssetsForEntry($manifest, 'assets/src/main.js', self::FRONT_HANDLE);

        $this->localize(self::FRONT_HANDLE);
    }

    /**
     * ADMIN (chargé uniquement sur page plugin)
     */
    public function enqueueAdmin(string $hook): void
    {
        $currentPage = isset($_GET['page']) ? sanitize_key((string) wp_unslash($_GET['page'])) : '';

        if ($hook !== 'toplevel_page_corbidev-modal-auth' && $currentPage !== 'corbidev-modal-auth') {
            return;
        }

        $manifest = $this->getManifest();

        $this->enqueueAssetsForEntry($manifest, 'assets/src/admin/main.js', self::ADMIN_HANDLE);

        $this->localize(self::ADMIN_HANDLE);
    }

    /**
     * Chargement des assets pour une entrée donnée
     */
    private function enqueueAssetsForEntry(array $manifest, string $entryKey, string $handle): void
    {
        if (!isset($manifest[$entryKey])) {
            return;
        }

        $entry = $manifest[$entryKey];

        // JS
        wp_enqueue_script(
            $handle,
            $this->assetUrl($entry['file']),
            [],
            null,
            true
        );

        wp_script_add_data($handle, 'type', 'module');

        // CSS in imports
        if (isset($entry['imports'])) {
            foreach ($entry['imports'] as $importKey) {
                if (isset($manifest[$importKey]['css'])) {
                    foreach ($manifest[$importKey]['css'] as $cssFile) {
                        wp_enqueue_style(
                            $handle . '-style-' . md5($cssFile),
                            $this->assetUrl($cssFile)
                        );
                    }
                }
            }
        }

        // CSS in entry
        if (isset($entry['css'])) {
            foreach ($entry['css'] as $cssFile) {
                wp_enqueue_style(
                    $handle . '-style-' . md5($cssFile),
                    $this->assetUrl($cssFile)
                );
            }
        }
    }

    /**
     * Injection configuration JS
     */
    private function localize(string $handle): void
    {
        $settingsService = new Settings();
        $settings = $settingsService->get();
        $settings['turnstile_site_key'] = $settingsService->getEffectiveTurnstileSiteKey();
        if ($handle === self::ADMIN_HANDLE) {
            $settings['cloudflare_secret'] = $settingsService->getEffectiveCloudflareSecret();
        }

        wp_localize_script(
            $handle,
            'CorbidevModalAuth',
            [
                'restUrl'  => esc_url_raw(rest_url('corbidev-modal-auth/v1')),
                'nonce'    => wp_create_nonce('wp_rest'),
                'settings' => $settings,
                'multisite' => $settingsService->getMultisiteContext(),
                'i18n'     => [
                    'login'                 => __('Login', 'corbidevmodalauth'),
                    'logout'                => __('Logout', 'corbidevmodalauth'),
                    'username'              => __('Username', 'corbidevmodalauth'),
                    'password'              => __('Password', 'corbidevmodalauth'),
                    'remember_me'           => __('Remember me', 'corbidevmodalauth'),
                    'forgot_password'       => __('Forgot password?', 'corbidevmodalauth'),
                    'reset_password'        => __('Reset password', 'corbidevmodalauth'),
                    'back_to_login'         => __('Back to login', 'corbidevmodalauth'),
                    'loading'               => __('Loading...', 'corbidevmodalauth'),
                    'invalid_credentials'   => __('Invalid credentials', 'corbidevmodalauth'),
                    'too_many_attempts'     => __('Too many attempts. Please try later.', 'corbidevmodalauth'),
                    'rate_limited'          => __('Too many requests. Please slow down.', 'corbidevmodalauth'),
                    'modal_settings'        => __('Modal Settings', 'corbidevmodalauth'),
                    'enable_modal'          => __('Enable Modal', 'corbidevmodalauth'),
                    'redirect_after_login'  => __('Redirect After Login', 'corbidevmodalauth'),
                    'redirect_after_logout' => __('Redirect After Logout', 'corbidevmodalauth'),
                    'security'              => __('Security', 'corbidevmodalauth'),
                    'max_login_attempts'    => __('Max Login Attempts', 'corbidevmodalauth'),
                    'lock_time_seconds'     => __('Lock Time (seconds)', 'corbidevmodalauth'),
                    'rest_max_requests'     => __('REST Max Requests', 'corbidevmodalauth'),
                    'rest_window_seconds'   => __('REST Window (seconds)', 'corbidevmodalauth'),
                    'cloudflare_settings'   => __('Cloudflare Turnstile', 'corbidevmodalauth'),
                    'enable_cloudflare'     => __('Enable Cloudflare', 'corbidevmodalauth'),
                    'turnstile_site_key'    => __('Turnstile Site Key', 'corbidevmodalauth'),
                    'cloudflare_secret_key' => __('Turnstile Secret Key', 'corbidevmodalauth'),
                    'cloudflare_secret_help' => __('Stored in database on WordPress classic. On Bedrock-like setups: main site uses .env (TURNSTILE_SITE_KEY / TURNSTILE_SECRET_KEY), sub-sites use per-site database values.', 'corbidevmodalauth'),
                    'show_secret'           => __('Show key', 'corbidevmodalauth'),
                    'hide_secret'           => __('Hide key', 'corbidevmodalauth'),
                    'multisite_controls'    => __('Multisite defaults and locks', 'corbidevmodalauth'),
                    'multisite_default_all_sites' => __('Default for all sites', 'corbidevmodalauth'),
                    'multisite_lock_main_site' => __('Only editable on main site', 'corbidevmodalauth'),
                    'multisite_controls_help' => __('Each option can inherit main-site default. Locking disables edits on sub-sites.', 'corbidevmodalauth'),
                    'multisite_read_only_subsite' => __('Read-only on this sub-site', 'corbidevmodalauth'),
                    'floating_size_mobile' => __('Button size (mobile)', 'corbidevmodalauth'),
                    'floating_size_tablet' => __('Button size (tablet)', 'corbidevmodalauth'),
                    'floating_size_desktop' => __('Button size (desktop)', 'corbidevmodalauth'),
                    'floating_button_settings' => __('Floating Button Settings', 'corbidevmodalauth'),
                    'floating_position'      => __('Button Position', 'corbidevmodalauth'),
                    'bottom_right'           => __('Bottom right', 'corbidevmodalauth'),
                    'bottom_left'            => __('Bottom left', 'corbidevmodalauth'),
                    'top_right'              => __('Top right', 'corbidevmodalauth'),
                    'top_left'               => __('Top left', 'corbidevmodalauth'),
                    'floating_size'          => __('Button size', 'corbidevmodalauth'),
                    'mobile'                 => __('Mobile', 'corbidevmodalauth'),
                    'tablet'                 => __('Tablet', 'corbidevmodalauth'),
                    'desktop'                => __('Desktop', 'corbidevmodalauth'),
                    'small'                  => __('Small', 'corbidevmodalauth'),
                    'medium'                 => __('Medium', 'corbidevmodalauth'),
                    'large'                  => __('Large', 'corbidevmodalauth'),
                    'floating_custom_css'    => __('Button CSS styles', 'corbidevmodalauth'),
                    'floating_custom_css_help' => __('If provided, replaces default button classes/styles.', 'corbidevmodalauth'),
                    'floating_label_login'   => __('Button text (logged out)', 'corbidevmodalauth'),
                    'floating_label_logout'  => __('Button text (logged in)', 'corbidevmodalauth'),
                    'floating_label_visibility' => __('Text visibility by device', 'corbidevmodalauth'),
                    'show_label_mobile'      => __('Show text on mobile', 'corbidevmodalauth'),
                    'show_label_tablet'      => __('Show text on tablet', 'corbidevmodalauth'),
                    'show_label_desktop'     => __('Show text on desktop', 'corbidevmodalauth'),
                    'save'                  => __('Save', 'corbidevmodalauth'),
                    'saving'                => __('Saving...', 'corbidevmodalauth'),
                    'saved'                 => __('Settings saved', 'corbidevmodalauth'),
                    'error'                 => __('An error occurred', 'corbidevmodalauth'),
                ],
            ]
        );
    }

    /**
     * Lecture manifest Vite
     */
    private function getManifest(): array
    {
        $basePath = defined('CDA_PLUGIN_PATH')
            ? CDA_PLUGIN_PATH
            : plugin_dir_path(dirname(__DIR__, 2));

        $path = $basePath . 'assets/dist/.vite/manifest.json';

        if (!file_exists($path)) {
            $path = $basePath . 'assets/dist/manifest.json';
        }

        if (!file_exists($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true) ?? [];
    }

    /**
     * Génération URL asset
     */
    private function assetUrl(string $file): string
    {
        $baseUrl = defined('CDA_PLUGIN_URL')
            ? CDA_PLUGIN_URL
            : plugin_dir_url(dirname(__DIR__, 2));

        return $baseUrl . 'assets/dist/' . $file;
    }
}
