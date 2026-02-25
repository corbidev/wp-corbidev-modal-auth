<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<style>
    #corbidev-modal-auth-admin-shell {
        position: relative;
        min-height: 280px;
    }

    #corbidev-modal-auth-admin-app {
        opacity: 0;
        transition: opacity 0.18s ease;
    }

    #corbidev-modal-auth-admin-shell.is-ready #corbidev-modal-auth-admin-app {
        opacity: 1;
    }

    .cda-admin-loader {
        position: absolute;
        inset: 0;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.82);
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

<div id="corbidev-modal-auth-admin-shell" class="cda-admin-shell is-loading">
    <div id="corbidev-modal-auth-admin-loading" class="cda-admin-loader" role="status" aria-live="polite">
        <span class="cda-admin-loader__spinner" aria-hidden="true"></span>
        <span>Chargement de l'interface...</span>
    </div>
    <div id="corbidev-modal-auth-admin-app" aria-busy="true"></div>
</div>
