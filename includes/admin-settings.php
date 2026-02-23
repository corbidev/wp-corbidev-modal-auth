<?php
if (!defined('ABSPATH')) exit;

function wpma_get_option_show_floating_button() {
    return (bool) get_option('wpma_show_floating_button', 0);
}

function wpma_get_option_floating_position() {
    $value = get_option('wpma_floating_position', 'bottom-right');
    $value = is_string($value) ? $value : 'bottom-right';
    $allowed = ['bottom-right', 'bottom-left', 'top-right', 'top-left'];
    return in_array($value, $allowed, true) ? $value : 'bottom-right';
}

function wpma_get_option_floating_size() {
    $value = get_option('wpma_floating_size', 'md');
    $value = is_string($value) ? $value : 'md';
    $allowed = ['sm', 'md', 'lg'];
    return in_array($value, $allowed, true) ? $value : 'md';
}

function wpma_get_option_floating_size_mobile() {
    $allowed = ['sm', 'md', 'lg'];

    $value = get_option('wpma_floating_size_mobile', '');
    $value = is_string($value) ? $value : '';
    if ($value !== '' && in_array($value, $allowed, true)) {
        return $value;
    }

    $legacy = get_option('wpma_floating_size', 'md');
    $legacy = is_string($legacy) ? $legacy : 'md';
    return in_array($legacy, $allowed, true) ? $legacy : 'md';
}

function wpma_get_option_floating_size_tablet() {
    $allowed = ['sm', 'md', 'lg'];

    $value = get_option('wpma_floating_size_tablet', '');
    $value = is_string($value) ? $value : '';
    if ($value !== '' && in_array($value, $allowed, true)) {
        return $value;
    }

    $legacy = get_option('wpma_floating_size', 'md');
    $legacy = is_string($legacy) ? $legacy : 'md';
    return in_array($legacy, $allowed, true) ? $legacy : 'md';
}

function wpma_get_option_floating_size_desktop() {
    $allowed = ['sm', 'md', 'lg'];

    $value = get_option('wpma_floating_size_desktop', '');
    $value = is_string($value) ? $value : '';
    if ($value !== '' && in_array($value, $allowed, true)) {
        return $value;
    }

    $legacy = get_option('wpma_floating_size', 'md');
    $legacy = is_string($legacy) ? $legacy : 'md';
    return in_array($legacy, $allowed, true) ? $legacy : 'md';
}

function wpma_get_option_floating_custom_styles() {
    $value = get_option('wpma_floating_custom_classes', '');
    return is_string($value) ? $value : '';
}

function wpma_get_option_floating_label_login() {
    $value = get_option('wpma_floating_label_login', 'Connexion');
    return is_string($value) && $value !== '' ? $value : 'Connexion';
}

function wpma_get_option_floating_label_logout() {
    $value = get_option('wpma_floating_label_logout', 'Déconnexion');
    return is_string($value) && $value !== '' ? $value : 'Déconnexion';
}

function wpma_get_option_show_label_mobile() {
    $value = get_option('wpma_show_label_mobile', 1);
    return (bool) $value;
}

function wpma_get_option_show_label_tablet() {
    $value = get_option('wpma_show_label_tablet', 1);
    return (bool) $value;
}

function wpma_get_option_show_label_desktop() {
    $value = get_option('wpma_show_label_desktop', 1);
    return (bool) $value;
}

add_action('admin_init', function () {
    register_setting(
        'wpma_settings',
        'wpma_show_floating_button',
        [
            'type'              => 'boolean',
            'sanitize_callback' => function ($value) {
                return $value ? 1 : 0;
            },
            'default'           => 0,
        ]
    );

    register_setting(
        'wpma_settings',
        'wpma_floating_position',
        [
            'type'              => 'string',
            'sanitize_callback' => function ($value) {
                if (!isset($_POST['wpma_floating_position'])) {
                    $current = get_option('wpma_floating_position', 'bottom-right');
                    $current = is_string($current) ? $current : 'bottom-right';
                    $allowed = ['bottom-right', 'bottom-left', 'top-right', 'top-left'];
                    return in_array($current, $allowed, true) ? $current : 'bottom-right';
                }
                $value = is_string($value) ? $value : '';
                $allowed = ['bottom-right', 'bottom-left', 'top-right', 'top-left'];
                return in_array($value, $allowed, true) ? $value : 'bottom-right';
            },
            'default'           => 'bottom-right',
        ]
    );

    register_setting(
        'wpma_settings',
        'wpma_floating_size',
        [
            'type'              => 'string',
            'sanitize_callback' => function ($value) {
                if (!isset($_POST['wpma_floating_size'])) {
                    $current = get_option('wpma_floating_size', 'md');
                    $current = is_string($current) ? $current : 'md';
                    $allowed = ['sm', 'md', 'lg'];
                    return in_array($current, $allowed, true) ? $current : 'md';
                }
                $value = is_string($value) ? $value : '';
                $allowed = ['sm', 'md', 'lg'];
                return in_array($value, $allowed, true) ? $value : 'md';
            },
            'default'           => 'md',
        ]
    );

    register_setting(
        'wpma_settings',
        'wpma_floating_size_mobile',
        [
            'type'              => 'string',
            'sanitize_callback' => function ($value) {
                $allowed = ['sm', 'md', 'lg'];

                if (!isset($_POST['wpma_floating_size_mobile'])) {
                    $current = get_option('wpma_floating_size_mobile', '');
                    $current = is_string($current) ? $current : '';
                    if ($current !== '' && in_array($current, $allowed, true)) {
                        return $current;
                    }
                    $legacy = get_option('wpma_floating_size', 'md');
                    $legacy = is_string($legacy) ? $legacy : 'md';
                    return in_array($legacy, $allowed, true) ? $legacy : 'md';
                }

                $value = is_string($value) ? $value : '';
                return in_array($value, $allowed, true) ? $value : 'md';
            },
            'default'           => 'md',
        ]
    );

    register_setting(
        'wpma_settings',
        'wpma_floating_size_tablet',
        [
            'type'              => 'string',
            'sanitize_callback' => function ($value) {
                $allowed = ['sm', 'md', 'lg'];

                if (!isset($_POST['wpma_floating_size_tablet'])) {
                    $current = get_option('wpma_floating_size_tablet', '');
                    $current = is_string($current) ? $current : '';
                    if ($current !== '' && in_array($current, $allowed, true)) {
                        return $current;
                    }
                    $legacy = get_option('wpma_floating_size', 'md');
                    $legacy = is_string($legacy) ? $legacy : 'md';
                    return in_array($legacy, $allowed, true) ? $legacy : 'md';
                }

                $value = is_string($value) ? $value : '';
                return in_array($value, $allowed, true) ? $value : 'md';
            },
            'default'           => 'md',
        ]
    );

    register_setting(
        'wpma_settings',
        'wpma_floating_size_desktop',
        [
            'type'              => 'string',
            'sanitize_callback' => function ($value) {
                $allowed = ['sm', 'md', 'lg'];

                if (!isset($_POST['wpma_floating_size_desktop'])) {
                    $current = get_option('wpma_floating_size_desktop', '');
                    $current = is_string($current) ? $current : '';
                    if ($current !== '' && in_array($current, $allowed, true)) {
                        return $current;
                    }
                    $legacy = get_option('wpma_floating_size', 'md');
                    $legacy = is_string($legacy) ? $legacy : 'md';
                    return in_array($legacy, $allowed, true) ? $legacy : 'md';
                }

                $value = is_string($value) ? $value : '';
                return in_array($value, $allowed, true) ? $value : 'md';
            },
            'default'           => 'md',
        ]
    );

    register_setting(
        'wpma_settings',
        'wpma_floating_custom_classes',
        [
            'type'              => 'string',
            'sanitize_callback' => function ($value) {
                if (!isset($_POST['wpma_floating_custom_classes'])) {
                    $current = get_option('wpma_floating_custom_classes', '');
                    return is_string($current) ? $current : '';
                }
                if (function_exists('sanitize_textarea_field')) {
                    $value = sanitize_textarea_field($value);
                } else {
                    $value = is_string($value) ? $value : '';
                }
                return $value;
            },
            'default'           => '',
        ]
    );

    register_setting(
        'wpma_settings',
        'wpma_floating_label_login',
        [
            'type'              => 'string',
            'sanitize_callback' => function ($value) {
                if (!isset($_POST['wpma_floating_label_login'])) {
                    $current = get_option('wpma_floating_label_login', 'Connexion');
                    return is_string($current) ? $current : 'Connexion';
                }
                return sanitize_text_field($value);
            },
            'default'           => 'Connexion',
        ]
    );

    register_setting(
        'wpma_settings',
        'wpma_floating_label_logout',
        [
            'type'              => 'string',
            'sanitize_callback' => function ($value) {
                if (!isset($_POST['wpma_floating_label_logout'])) {
                    $current = get_option('wpma_floating_label_logout', 'Déconnexion');
                    return is_string($current) ? $current : 'Déconnexion';
                }
                return sanitize_text_field($value);
            },
            'default'           => 'Déconnexion',
        ]
    );

    register_setting(
        'wpma_settings',
        'wpma_show_label_mobile',
        [
            'type'              => 'boolean',
            'sanitize_callback' => function ($value) {
                return $value ? 1 : 0;
            },
            'default'           => 1,
        ]
    );

    register_setting(
        'wpma_settings',
        'wpma_show_label_tablet',
        [
            'type'              => 'boolean',
            'sanitize_callback' => function ($value) {
                return $value ? 1 : 0;
            },
            'default'           => 1,
        ]
    );

    register_setting(
        'wpma_settings',
        'wpma_show_label_desktop',
        [
            'type'              => 'boolean',
            'sanitize_callback' => function ($value) {
                return $value ? 1 : 0;
            },
            'default'           => 1,
        ]
    );
});

add_action('admin_menu', function () {
    add_options_page(
        'WP Modal Auth',
        'WP Modal Auth',
        'manage_options',
        'wpma-settings',
        'wpma_render_settings_page'
    );
});

add_action('wp_ajax_wpma_save_checkbox', function () {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('forbidden');
    }

    check_ajax_referer('wpma_admin_nonce', 'nonce');

    $option = isset($_POST['option']) ? sanitize_key(wp_unslash($_POST['option'])) : '';
    $value  = isset($_POST['value']) && (int) $_POST['value'] === 1 ? 1 : 0;

    $allowed = [
        'wpma_show_floating_button',
        'wpma_show_label_mobile',
        'wpma_show_label_tablet',
        'wpma_show_label_desktop',
    ];

    if (!in_array($option, $allowed, true)) {
        wp_send_json_error('invalid_option');
    }

    update_option($option, $value);

    wp_send_json_success([
        'option' => $option,
        'value'  => $value,
    ]);
});

function wpma_render_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $active_tab = 'general';
    if (!empty($_POST['wpma_active_tab'])) {
        $posted_tab = sanitize_key(wp_unslash($_POST['wpma_active_tab']));
        if (in_array($posted_tab, ['general', 'floating'], true)) {
            $active_tab = $posted_tab;
        }
    }

    $nonce = wp_create_nonce('wpma_admin_nonce');
    ?>
<style>
.wpma-toast {
    position: fixed;
    left: 50%;
    transform: translateX(-50%) translateY(-8px);
    top: 32px;
    z-index: 100000;
    min-width: 240px;
    max-width: 520px;
    padding: 6px 14px;
    border-radius: 999px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.45);
    background: transparent;
    color: #f9fafb;
    font-size: 13px;
    line-height: 1.4;
    opacity: 0;
    transform-origin: top center;
    transition: opacity 180ms ease-out, transform 180ms ease-out;
}

.wpma-toast.wpma-toast-visible {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

.wpma-toast.wpma-toast-hide {
    opacity: 0;
    transform: translateX(-50%) translateY(-6px) scale(0.97);
}

body.admin-bar .wpma-toast {
    top: 64px;
}

.wpma-toast-success {
    background: #16a34a;
    border: 1px solid #22c55e;
    color: #ecfdf5;
}

.wpma-toast-error {
    background: rgba(185, 28, 28, 0.96);
    border: 1px solid rgba(248, 113, 113, 0.9);
    color: #fef2f2;
}

.wpma-toast-message {
    flex: 1;
}

.wpma-toast-close {
    background: transparent;
    border: none;
    color: inherit;
    cursor: pointer;
    font-size: 15px;
    line-height: 1;
    padding: 2px 4px;
}

/* Grille responsive pour les tailles Mobile / Tablette / Ordinateur */
.wpma-size-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 24px;
    /* espace réduit entre les blocs */
    align-items: flex-start;
    max-width: 520px;
    /* évite un étirement sur toute la largeur du tableau */
}

.wpma-size-col {
    flex: 1 1 100%;
    min-width: 0;
}

@media (min-width: 782px) {
    .wpma-size-col {
        flex: 0 0 auto;
        margin-right: 35px;
        /* largeur auto + léger margin entre chaque colonne */
    }

    .wpma-size-col:last-child {
        margin-right: 0;
    }
}

.wpma-size-col p {
    margin-top: 0;
    margin-bottom: 6px;
}

.wpma-size-col fieldset {
    margin: 0;
}
</style>

<div class="wrap">
    <h1>WP Modal Auth</h1>

    <h2 class="nav-tab-wrapper">
        <a href="#wpma-tab-general"
            class="nav-tab wpma-nav-tab <?php echo $active_tab === 'general' ? 'nav-tab-active' : ''; ?>"
            data-tab="general">
            Général
        </a>
        <a href="#wpma-tab-floating"
            class="nav-tab wpma-nav-tab <?php echo $active_tab === 'floating' ? 'nav-tab-active' : ''; ?>"
            data-tab="floating">
            Bouton flottant
        </a>
    </h2>

    <form id="wpma-settings-form" method="post" action="options.php">
        <input type="hidden" name="wpma_active_tab" id="wpma_active_tab" value="<?php echo esc_attr($active_tab); ?>">
        <?php settings_fields('wpma_settings'); ?>

        <div id="wpma-tab-general" class="wpma-tab-section"
            style="<?php echo $active_tab === 'general' ? '' : 'display:none;'; ?>">
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Bouton flottant Connexion/Déconnexion</th>
                    <td>
                        <label>
                            <input type="checkbox" name="wpma_show_floating_button" value="1"
                                <?php checked(wpma_get_option_show_floating_button()); ?>>
                            Activer l’affichage d’un bouton flottant Connexion/Déconnexion sur la page d’accueil
                        </label>
                    </td>
                </tr>
            </table>
        </div>

        <div id="wpma-tab-floating" class="wpma-tab-section"
            style="<?php echo $active_tab === 'floating' ? '' : 'display:none;'; ?>">
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Position du bouton</th>
                    <td>
                        <?php $position = wpma_get_option_floating_position(); ?>
                        <fieldset>
                            <label>
                                <input type="radio" name="wpma_floating_position" value="bottom-right"
                                    <?php checked($position, 'bottom-right'); ?>>
                                Bas droite
                            </label><br>
                            <label>
                                <input type="radio" name="wpma_floating_position" value="bottom-left"
                                    <?php checked($position, 'bottom-left'); ?>>
                                Bas gauche
                            </label><br>
                            <label>
                                <input type="radio" name="wpma_floating_position" value="top-right"
                                    <?php checked($position, 'top-right'); ?>>
                                Haut droite
                            </label><br>
                            <label>
                                <input type="radio" name="wpma_floating_position" value="top-left"
                                    <?php checked($position, 'top-left'); ?>>
                                Haut gauche
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Taille du bouton</th>
                    <td>
                        <?php
                            $size_mobile  = wpma_get_option_floating_size_mobile();
                            $size_tablet  = wpma_get_option_floating_size_tablet();
                            $size_desktop = wpma_get_option_floating_size_desktop();
                            ?>
                        <div class="wpma-size-grid">
                            <div class="wpma-size-col">
                                <p><strong>Mobile</strong></p>
                                <fieldset>
                                    <label>
                                        <input type="radio" name="wpma_floating_size_mobile" value="sm"
                                            <?php checked($size_mobile, 'sm'); ?>>
                                        Petit
                                    </label><br>
                                    <label>
                                        <input type="radio" name="wpma_floating_size_mobile" value="md"
                                            <?php checked($size_mobile, 'md'); ?>>
                                        Moyen
                                    </label><br>
                                    <label>
                                        <input type="radio" name="wpma_floating_size_mobile" value="lg"
                                            <?php checked($size_mobile, 'lg'); ?>>
                                        Grand
                                    </label>
                                </fieldset>
                            </div>

                            <div class="wpma-size-col">
                                <p><strong>Tablette</strong></p>
                                <fieldset>
                                    <label>
                                        <input type="radio" name="wpma_floating_size_tablet" value="sm"
                                            <?php checked($size_tablet, 'sm'); ?>>
                                        Petit
                                    </label><br>
                                    <label>
                                        <input type="radio" name="wpma_floating_size_tablet" value="md"
                                            <?php checked($size_tablet, 'md'); ?>>
                                        Moyen
                                    </label><br>
                                    <label>
                                        <input type="radio" name="wpma_floating_size_tablet" value="lg"
                                            <?php checked($size_tablet, 'lg'); ?>>
                                        Grand
                                    </label>
                                </fieldset>
                            </div>

                            <div class="wpma-size-col">
                                <p><strong>Ordinateur</strong></p>
                                <fieldset>
                                    <label>
                                        <input type="radio" name="wpma_floating_size_desktop" value="sm"
                                            <?php checked($size_desktop, 'sm'); ?>>
                                        Petit
                                    </label><br>
                                    <label>
                                        <input type="radio" name="wpma_floating_size_desktop" value="md"
                                            <?php checked($size_desktop, 'md'); ?>>
                                        Moyen
                                    </label><br>
                                    <label>
                                        <input type="radio" name="wpma_floating_size_desktop" value="lg"
                                            <?php checked($size_desktop, 'lg'); ?>>
                                        Grand
                                    </label>
                                </fieldset>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Styles CSS du bouton</th>
                    <td>
                        <textarea name="wpma_floating_custom_classes" rows="4"
                            class="large-text code"><?php echo esc_textarea(wpma_get_option_floating_custom_styles()); ?></textarea>
                        <p class="description">Si renseigné, remplace entièrement les classes CSS par défaut (position,
                            taille, couleurs...). Écrivez ici des propriétés CSS complètes, par exemple&nbsp;:
                            <code>position: fixed; bottom: 1.5rem; right: 1.5rem; border-radius: 9999px; background: #4f46e5;</code>
                        </p>
                        <p class="description">Classes/styles par défaut du bouton et de ses <code>span</code>&nbsp;:
                        </p>
                        <pre><?php echo esc_html("Bouton (classes) :\nfixed bottom-6 right-6 z-50 inline-flex items-center gap-2 rounded-full bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold px-4 py-2 shadow-lg shadow-indigo-600/40 transition-colors durée-150\n\nSpan icône (classes) :\ninline-flex h-6 w-6 items-center justify-center rounded-full bg-indigo-500/80 text-[12px]\n\nSpan texte (classes) :\nml-1 hidden sm:inline lg:inline\n\nExemple de styles CSS équivalents :\nposition: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 50; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 9999px; background-color: #4f46e5; color: #fff; padding: 0.5rem 1rem;"); ?></pre>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Texte bouton (déconnecté)</th>
                    <td>
                        <input type="text" class="regular-text" name="wpma_floating_label_login"
                            value="<?php echo esc_attr(wpma_get_option_floating_label_login()); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Texte bouton (connecté)</th>
                    <td>
                        <input type="text" class="regular-text" name="wpma_floating_label_logout"
                            value="<?php echo esc_attr(wpma_get_option_floating_label_logout()); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Affichage du texte selon le device</th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="checkbox" name="wpma_show_label_mobile" value="1"
                                    <?php checked(wpma_get_option_show_label_mobile()); ?>>
                                Afficher le texte sur mobile
                            </label><br>
                            <label>
                                <input type="checkbox" name="wpma_show_label_tablet" value="1"
                                    <?php checked(wpma_get_option_show_label_tablet()); ?>>
                                Afficher le texte sur tablette
                            </label><br>
                            <label>
                                <input type="checkbox" name="wpma_show_label_desktop" value="1"
                                    <?php checked(wpma_get_option_show_label_desktop()); ?>>
                                Afficher le texte sur ordinateur
                            </label>
                        </fieldset>
                        <p class="description">Le texte est toujours visible si des classes personnalisées du bouton
                            gèrent déjà la visibilité.</p>
                    </td>
                </tr>
            </table>
        </div>

        <?php submit_button(); ?>
    </form>
</div>
<script>
jQuery(function($) {
    var $tabs = $('.wpma-nav-tab');
    var $sections = $('.wpma-tab-section');
    var $activeInput = $('#wpma_active_tab');
    var $form = $('#wpma-settings-form');

    $tabs.on('click', function(e) {
        e.preventDefault();
        var tab = $(this).data('tab');
        $tabs.removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $sections.hide();
        $('#wpma-tab-' + tab).show();
        if ($activeInput.length) {
            $activeInput.val(tab);
        }
    });

    $form.on('change', 'input[type="checkbox"]', function() {
        var $cb = $(this);
        var option = $cb.attr('name');
        var value = $cb.is(':checked') ? 1 : 0;

        $.post(ajaxurl, {
            action: 'wpma_save_checkbox',
            nonce: '<?php echo esc_js($nonce); ?>',
            option: option,
            value: value
        });
    });

    $form.on('submit', function(e) {
        e.preventDefault();
        var $existing = $('.wpma-toast');
        if ($existing.length) {
            $existing.remove();
        }

        $.post($form.attr('action'), $form.serialize())
            .done(function() {
                var $toast = $(
                    '<div class="wpma-toast wpma-toast-success" role="status" aria-live="polite">' +
                    '<div class="wpma-toast-message">Réglages enregistrés.</div>' +
                    '<button type="button" class="wpma-toast-close" aria-label="Fermer">×</button>' +
                    '</div>');
                $('body').append($toast);

                // animation d'ouverture
                setTimeout(function() {
                    $toast.addClass('wpma-toast-visible');
                }, 10);

                var timer = setTimeout(function() {
                    $toast.addClass('wpma-toast-hide');
                    setTimeout(function() {
                        $toast.remove();
                    }, 220);
                }, 4000);

                $toast.on('click', '.wpma-toast-close', function() {
                    clearTimeout(timer);
                    $toast.addClass('wpma-toast-hide');
                    setTimeout(function() {
                        $toast.remove();
                    }, 200);
                });
            })
            .fail(function() {
                var $toast = $(
                    '<div class="wpma-toast wpma-toast-error" role="status" aria-live="polite">' +
                    '<div class="wpma-toast-message">Erreur lors de l’enregistrement des réglages.</div>' +
                    '<button type="button" class="wpma-toast-close" aria-label="Fermer">×</button>' +
                    '</div>');
                $('body').append($toast);

                setTimeout(function() {
                    $toast.addClass('wpma-toast-visible');
                }, 10);

                var timer = setTimeout(function() {
                    $toast.addClass('wpma-toast-hide');
                    setTimeout(function() {
                        $toast.remove();
                    }, 220);
                }, 6000);

                $toast.on('click', '.wpma-toast-close', function() {
                    clearTimeout(timer);
                    $toast.addClass('wpma-toast-hide');
                    setTimeout(function() {
                        $toast.remove();
                    }, 200);
                });
            });
    });
});
</script>
<?php
}