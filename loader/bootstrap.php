<?php

if (!defined('ABSPATH')) exit;

/**
 * Charge l'autoloader interne.
 */


use Corbidev\ModalAuth\Core\Plugin;

/**
 * Initialise le plugin.
 */
(new Plugin())->init();
