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
    gap: 12px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.82);
    color: #334155;
    font-size: 14px;
    perspective: 720px;
    max-width: 48rem;
    border-radius: 1rem;
    padding: .5rem;
}

.cda-admin-loader__spinner-3d {
    position: relative;
    width: 34px;
    height: 34px;
    transform-style: preserve-3d;
    animation: cda-admin-spinner-tilt 2.2s linear infinite;
}

.cda-admin-loader__ring {
    position: absolute;
    inset: 0;
    border-radius: 999px;
    border: 3px solid transparent;
    box-sizing: border-box;
    filter: drop-shadow(0 0 2px rgba(15, 95, 148, 0.22));
}

.cda-admin-loader__ring--x {
    border-top-color: #0f5f94;
    border-bottom-color: #0f5f94;
    animation: cda-admin-ring-x 1.1s linear infinite;
    transform: rotateX(70deg);
}

.cda-admin-loader__ring--y {
    border-left-color: #0284c7;
    border-right-color: #0284c7;
    animation: cda-admin-ring-y 1.35s linear infinite reverse;
    transform: rotateY(70deg);
}

.cda-admin-loader__ring--z {
    border-top-color: #38bdf8;
    border-bottom-color: #38bdf8;
    animation: cda-admin-ring-z 1.65s linear infinite;
    transform: rotateX(70deg) rotateY(70deg);
    opacity: 0.9;
}

@keyframes cda-admin-spinner-tilt {
    0% {
        transform: rotateX(-16deg) rotateY(0deg);
    }

    100% {
        transform: rotateX(-16deg) rotateY(360deg);
    }
}

@keyframes cda-admin-ring-x {
    0% {
        transform: rotateX(70deg) rotateZ(0deg);
    }

    to {
        transform: rotateX(70deg) rotateZ(360deg);
    }
}

@keyframes cda-admin-ring-y {
    0% {
        transform: rotateY(70deg) rotateZ(0deg);
    }

    to {
        transform: rotateY(70deg) rotateZ(360deg);
    }
}

@keyframes cda-admin-ring-z {
    0% {
        transform: rotateX(70deg) rotateY(70deg) rotateZ(0deg);
    }

    to {
        transform: rotateX(70deg) rotateY(70deg) rotateZ(360deg);
    }
}

@media (prefers-reduced-motion: reduce) {

    .cda-admin-loader__spinner-3d,
    .cda-admin-loader__ring {
        animation: none;
    }
}
</style>

<div id="corbidev-modal-auth-admin-shell" class="cda-admin-shell is-loading">
    <div id="corbidev-modal-auth-admin-loading" class="cda-admin-loader" role="status" aria-live="polite">
        <span class="cda-admin-loader__spinner-3d" aria-hidden="true">
            <span class="cda-admin-loader__ring cda-admin-loader__ring--x"></span>
            <span class="cda-admin-loader__ring cda-admin-loader__ring--y"></span>
            <span class="cda-admin-loader__ring cda-admin-loader__ring--z"></span>
        </span>
        <span>Chargement de l'interface...</span>
    </div>
    <div id="corbidev-modal-auth-admin-app" aria-busy="true"></div>
</div>