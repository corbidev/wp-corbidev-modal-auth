<?php

namespace Corbidev\ModalAuth\Core;

class Plugin {

    public function init(): void
    {
        (new Rest())->register();
        (new Assets())->register();
        require_once CDA_PLUGIN_PATH . 'public/mount.php';
    }

    (new Admin())->register();
}