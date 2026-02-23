<?php
/**
 * Plugin Name: WP Modal Auth
 * Description: Authentification WordPress en modal moderne (Tailwind).
 * Version: 1.4.2
 * Author: Corbidev
 */
if (!defined('ABSPATH')) exit;

define('WPMA_PATH', plugin_dir_path(__FILE__));
define('WPMA_URL', plugin_dir_url(__FILE__));

require_once WPMA_PATH.'includes/admin-settings.php';
require_once WPMA_PATH.'includes/auth-hooks.php';
require_once WPMA_PATH.'includes/ajax-auth.php';

// Habillage des écrans wp-login (connexion, mot de passe oublié, réinitialisation)
add_action('login_head', function () {
    // On applique le style sur tous les écrans de connexion/réinitialisation
    // Icône dédiée "changement de mot de passe"
    $logo_url = esc_url(WPMA_URL.'assets/images/wpma-password.svg');
    ?>
<style>
body.login {
    background: radial-gradient(circle at top, #1d283a 0, #020617 45%, #020617 100%);
    color: #e5e7eb;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}

body.login #login {
    width: 100%;
    max-width: 420px;
    padding: 2.5rem 2.25rem 2.25rem;
    margin-top: 4vh;
    border-radius: 1.5rem;
    background: rgba(15, 23, 42, 0.98);
    box-shadow: 0 20px 35px rgba(15, 23, 42, 0.9);
    border: 1px solid rgba(148, 163, 184, 0.35);
}

body.login #login h1 {
    margin-bottom: 1.75rem;
}

body.login #login h1 a {
    background-image: url('<?php echo $logo_url; ?>');
    background-size: 72px 72px;
    background-position: center;
    background-repeat: no-repeat;
    width: 72px;
    height: 72px;
    border-radius: 9999px;
    box-shadow: 0 6px 15px rgba(15, 23, 42, 0.9);
    margin: 0 auto;
}

body.login form {
    margin-top: 0;
    padding: 1.5rem 1.75rem 1.75rem;
    border-radius: 1.25rem;
    background: radial-gradient(circle at top, rgba(30, 64, 175, 0.25), rgba(15, 23, 42, 0.95));
    border: 1px solid rgba(148, 163, 184, 0.35);
    box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.9);
}

/* Message explicatif (ex: "Saisissez votre nouveau mot de passe ...") */
body.login #login .message {
    background: transparent;
    border: none;
    box-shadow: none;
    color: #e5e7eb;
    margin: 0 0 1.25rem 0;
    padding: 0 0.25rem 0.75rem 0.25rem;
    font-size: 0.85rem;
    text-align: center;
    line-height: 1.5;
}

body.login label {
    color: #e5e7eb;
    font-size: 0.8rem;
    font-weight: 500;
}

body.login .input,
body.login input[type="password"],
body.login input[type="text"],
body.login input[type="email"] {
    background-color: rgba(15, 23, 42, 0.85);
    border-radius: 0.75rem !important;
    border: 1px solid rgba(51, 65, 85, 0.85);
    color: #e5e7eb;
    padding: 0.6rem 0.8rem;
    box-shadow: none;
}

body.login .input:focus,
body.login input[type="password"]:focus,
body.login input[type="text"]:focus,
body.login input[type="email"]:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 1px #6366f1;
    outline: none;
}

body.login .button-primary {
    background: linear-gradient(to right, #4f46e5, #6366f1);
    border: none;
    border-radius: 9999px;
    box-shadow: 0 10px 20px rgba(79, 70, 229, 0.55);
    text-shadow: none;
    font-weight: 600;
    padding: 0.6rem 1.75rem;
}

body.login .button-primary:hover,
body.login .button-primary:focus {
    background: linear-gradient(to right, #6366f1, #818cf8);
    box-shadow: 0 14px 28px rgba(79, 70, 229, 0.7);
}

/* Bouton "Générer un mot de passe" : même style que button-primary button-large */
body.login #wp-generate-pw,
body.login .wp-generate-pw {
    min-height: 32px;
    line-height: 2.30769231;
    padding: 0 12px;
    background: linear-gradient(to right, #4f46e5, #6366f1);
    border: none;
    border-radius: 9999px;
    box-shadow: 0 10px 20px rgba(79, 70, 229, 0.55);
    text-shadow: none;
    font-weight: 600;
    color: #e5e7eb;
}

body.login #wp-generate-pw:hover,
body.login #wp-generate-pw:focus,
body.login .wp-generate-pw:hover,
body.login .wp-generate-pw:focus {
    background: linear-gradient(to right, #6366f1, #818cf8);
    box-shadow: 0 14px 28px rgba(79, 70, 229, 0.7);
}

body.login #backtoblog a,
body.login #nav a {
    color: #9ca3af;
}

body.login #backtoblog a:hover,
body.login #nav a:hover {
    color: #e5e7eb;
}
</style>
<?php
});

add_action('wp_enqueue_scripts', function () {

    // CSS Tailwind compilé du plugin (assets/css/styles.css)
    wp_enqueue_style(
        'wpma-styles',
        WPMA_URL.'assets/css/styles.css',
        [],
        '1.4.2'
    );

    // JS du plugin
    wp_enqueue_script(
        'wpma-script',
        WPMA_URL.'assets/js/modal-auth.js',
        ['jquery'],
        '1.2',
        true
    );

    wp_localize_script('wpma-script','WPMA',[
        'ajax'=>admin_url('admin-ajax.php'),
        'nonce'=>wp_create_nonce('wpma_nonce'),
        'home'=>home_url(),
        // Ouvrir automatiquement le modal si on vient de wp-login.php
        'auto_open'=>(!is_user_logged_in() && isset($_GET['wpma_login']))
    ]);
});

add_action('wp_footer', function () {
    if (!is_user_logged_in()) {
        include WPMA_PATH.'includes/templates/modal-auth.php';
    }
});

add_action('wp_footer', function () {
    if (!function_exists('wpma_get_option_show_floating_button') || !wpma_get_option_show_floating_button()) {
        return;
    }

    if (!is_front_page() && !is_home()) {
        return;
    }

    $is_logged_in = is_user_logged_in();

    $position = function_exists('wpma_get_option_floating_position') ? wpma_get_option_floating_position() : 'bottom-right';
    $is_top = false;
    switch ($position) {
        case 'bottom-left':
            $position_classes = 'bottom-6 left-6';
            break;
        case 'top-right':
            $position_classes = 'top-6 right-6';
            $is_top = true;
            break;
        case 'top-left':
            $position_classes = 'top-6 left-6';
            $is_top = true;
            break;
        case 'bottom-right':
        default:
            $position_classes = 'bottom-6 right-6';
            break;
    }

    $size_mobile  = function_exists('wpma_get_option_floating_size_mobile') ? wpma_get_option_floating_size_mobile() : 'md';
    $size_tablet  = function_exists('wpma_get_option_floating_size_tablet') ? wpma_get_option_floating_size_tablet() : $size_mobile;
    $size_desktop = function_exists('wpma_get_option_floating_size_desktop') ? wpma_get_option_floating_size_desktop() : $size_tablet;

    $map_button_size = function ($size) {
        switch ($size) {
            case 'sm':
                return 'text-xs px-3 py-1.5';
            case 'lg':
                return 'text-base px-5 py-2.5';
            case 'md':
            default:
                return 'text-sm px-4 py-2';
        }
    };

    $map_icon_size = function ($size) {
        switch ($size) {
            case 'sm':
                return 'h-5 w-5 text-[11px]';
            case 'lg':
                return 'h-7 w-7 text-[13px]';
            case 'md':
            default:
                return 'h-6 w-6 text-[12px]';
        }
    };

    $prefix_classes = function ($classes, $prefix) {
        $classes = trim((string) $classes);
        if ($classes === '') {
            return '';
        }
        $parts = preg_split('/\s+/', $classes);
        if (!is_array($parts)) {
            return '';
        }
        $prefixed = [];
        foreach ($parts as $part) {
            if ($part === '') {
                continue;
            }
            $prefixed[] = $prefix.$part;
        }
        return implode(' ', $prefixed);
    };

    $btn_mobile  = $map_button_size($size_mobile);
    $btn_tablet  = $map_button_size($size_tablet);
    $btn_desktop = $map_button_size($size_desktop);

    $icon_mobile  = $map_icon_size($size_mobile);
    $icon_tablet  = $map_icon_size($size_tablet);
    $icon_desktop = $map_icon_size($size_desktop);

    $size_classes_button = trim(
        $btn_mobile.' '.
        // Tablette = breakpoint md
        $prefix_classes($btn_tablet, 'md:').' '.
        // Ordinateur = breakpoint lg
        $prefix_classes($btn_desktop, 'lg:')
    );

    $size_classes_icon = trim(
        $icon_mobile.' '.
        // Tablette = breakpoint md
        $prefix_classes($icon_tablet, 'md:').' '.
        // Ordinateur = breakpoint lg
        $prefix_classes($icon_desktop, 'lg:')
    );

    $label_login  = function_exists('wpma_get_option_floating_label_login') ? wpma_get_option_floating_label_login() : 'Connexion';
    $label_logout = function_exists('wpma_get_option_floating_label_logout') ? wpma_get_option_floating_label_logout() : 'Déconnexion';

    $label        = $is_logged_in ? $label_logout : $label_login;
    $action_class = $is_logged_in ? 'wpma-float-logout' : 'wpma-open';

    $custom_styles = function_exists('wpma_get_option_floating_custom_styles') ? wpma_get_option_floating_custom_styles() : '';
    $custom_styles = trim((string) $custom_styles);

    $top_class = $is_top ? ' wpma-top' : '';

    if ($custom_styles !== '') {
        // On garde seulement les classes fonctionnelles (JS), le style inline prend en charge toute la mise en forme.
        $base_classes   = 'wpma-float-btn '.$action_class.$top_class;
        $button_classes = esc_attr($base_classes);
        $button_style   = ' style="'.esc_attr($custom_styles).'"';
    } else {
        $base_classes   = 'fixed '.$position_classes.' z-50 inline-flex items-center gap-2 rounded-full text-white font-semibold transition-colors duration-150 shadow-lg shadow-indigo-600/40 '.$size_classes_button.' '.$action_class.$top_class;
        $button_classes = esc_attr($base_classes);
        $button_style   = ' style="background-color: rgb(1 11 39); box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1), inset 0 0 6px 1px #5c5c5d;"';
    }

    $icon_classes = 'inline-flex '.$size_classes_icon.' items-center justify-center rounded-full';

    $show_label_mobile  = function_exists('wpma_get_option_show_label_mobile') ? wpma_get_option_show_label_mobile() : true;
    $show_label_tablet  = function_exists('wpma_get_option_show_label_tablet') ? wpma_get_option_show_label_tablet() : true;
    $show_label_desktop = function_exists('wpma_get_option_show_label_desktop') ? wpma_get_option_show_label_desktop() : true;

    // Construction explicite des classes d'affichage selon le device
    // Objectif :
    // - Mobile  : < md
    // - Tablette: md <= largeur < lg
    // - Desktop : >= lg
    // On force un display pour chaque plage pour éviter les conflits.
    $label_visibility_classes = [];

    // Mobile (base)
    $label_visibility_classes[] = $show_label_mobile ? 'inline' : 'hidden';

    // Tablette (>= md)
    $label_visibility_classes[] = $show_label_tablet ? 'md:inline' : 'md:hidden';

    // Ordinateur (>= lg)
    $label_visibility_classes[] = $show_label_desktop ? 'lg:inline' : 'lg:hidden';
    $label_span_classes = 'ml-1 '.implode(' ', array_unique($label_visibility_classes));

    echo '<style>body.admin-bar #wpma-float-btn.wpma-top{top:calc(0.8rem + 32px);}</style>';

    echo '<button id="wpma-float-btn" class="'.$button_classes.'"'.$button_style.'>';

    $icon_url = $is_logged_in
        ? WPMA_URL.'assets/images/wpma-logout.svg'
        : WPMA_URL.'assets/images/wpma-login.svg';

    echo '<span class="'.esc_attr($icon_classes).'">'
        .'<img src="'.esc_url($icon_url).'" alt="'.esc_attr($is_logged_in ? 'Déconnexion' : 'Connexion').'" width="32" height="32" loading="lazy" style="width:32px;height:32px;" />'
        .'</span>';

    echo '<span class="'.esc_attr($label_span_classes).'">'.esc_html($label).'</span>';
    echo '</button>';
});