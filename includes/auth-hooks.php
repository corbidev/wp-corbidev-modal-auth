<?php
if (!defined('ABSPATH')) exit;

add_action('init', function () {
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    $path        = parse_url($request_uri, PHP_URL_PATH) ?? '';

    // Intercepter à la fois wp-login.php et wp-login (sans extension)
    if (strpos($request_uri, 'wp-login.php') !== false || preg_match('~/wp-login/?$~', $path)) {
        $action = $_GET['action'] ?? '';

        // Laisser passer certaines actions natives critiques de wp-login :
        // - logout : déjà géré ci-dessous
        // - rp / resetpass : liens de réinitialisation envoyés par e‑mail
        if (in_array($action, ['rp', 'resetpass'], true)) {
            return;
        }

        if (is_user_logged_in() && $action === 'logout') {
            return;
        }

        // Redirige vers la page d'accueil avec un indicateur pour ouvrir le modal
        $target = add_query_arg('wpma_login', '1', home_url());
        wp_redirect($target);
        exit;
    }
});

// Après déconnexion classique WordPress, renvoyer simplement vers la page d'accueil
// sans ouverture automatique du modal
add_filter('logout_redirect', fn()=>home_url());
