<?php
if (!defined('ABSPATH')) exit;

add_action('wp_ajax_nopriv_wpma_login', function () {
    check_ajax_referer('wpma_nonce','nonce');
    $user = wp_signon([
        'user_login'=>sanitize_text_field($_POST['login'] ?? ''),
        'user_password'=>$_POST['password'] ?? '',
        'remember'=>!empty($_POST['remember'])
    ]);
    if (is_wp_error($user)) wp_send_json_error($user->get_error_message());
    wp_send_json_success();
});

add_action('wp_ajax_wpma_logout', function () {
    check_ajax_referer('wpma_nonce','nonce');
    wp_logout();
    wp_send_json_success();
});

add_action('wp_ajax_nopriv_wpma_forgot', function () {
    check_ajax_referer('wpma_nonce','nonce');
    $user = get_user_by('email', sanitize_email($_POST['email'] ?? ''));
    if (!$user) wp_send_json_error('Utilisateur introuvable');

    // Laisse scribo-smtp branché et utilise l’API standard
    $result = retrieve_password($user->user_login);

    if (is_wp_error($result) || !$result) {
        wp_send_json_error('Une erreur est survenue lors de l\'envoi de l\'email.');
    }

    wp_send_json_success();
});
