<?php
/**
 * Plugin Name:       Corbidev Modal Auth
 * Plugin URI:        https://github.com/CorbiDev/wp-corbidev-modal-auth
 * Depot Github:      wp-corbidev-modal-auth
 * Description:       Modal authentication Vue + Vite + Tailwind.
 * Version:           2.0.5
 * Author:            CorbiDev
 * Author URI:        https://github.com/CorbiDev
 *
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain:       corbidevmodalauth
 * Domain Path:       /languages
 *
 * Requires at least: 6.0
 * Tested up to:      6.5
 * Requires PHP:      8.4
 *
 * Icone:             assets/icons/wp-corbidev-modal-auth.png
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CDA_VERSION', '2.0.0');
define('CDA_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('CDA_PLUGIN_URL', plugin_dir_url(__FILE__));


/*
|--------------------------------------------------------------------------
| Charger uniquement ce qui est nécessaire à l'activation
|--------------------------------------------------------------------------
*/

require_once CDA_PLUGIN_PATH . 'includes/autoload.php';

use Corbidev\ModalAuth\Core\Installer;

register_activation_hook(
    __FILE__,
    [Installer::class, 'activate']
);

/*
|--------------------------------------------------------------------------
| Bootstrap runtime
|--------------------------------------------------------------------------
*/

require_once CDA_PLUGIN_PATH . 'loader/bootstrap.php';