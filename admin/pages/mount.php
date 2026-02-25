<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<style>
    #corbidev-modal-auth-admin-app {
        display: none;
    }

    #corbidev-modal-auth-admin-app.is-ready {
        display: block;
    }

    .cda-admin-loader {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin: 16px 0;
        color: #334155;
        font-size: 14px;
    }

    .cda-admin-loader__spinner {
        width: 18px;
        height: 18px;
        border-radius: 999px;
        border: 2px solid #cbd5e1;
        border-top-color: #0f5f94;
        animation: cda-admin-spin 0.7s linear infinite;
    }

    @keyframes cda-admin-spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<div id="corbidev-modal-auth-admin-loading" class="cda-admin-loader" role="status" aria-live="polite">
    <span class="cda-admin-loader__spinner" aria-hidden="true"></span>
    <span>Chargement de l'interface...</span>
</div>
<div id="corbidev-modal-auth-admin-app" aria-busy="true"></div>
