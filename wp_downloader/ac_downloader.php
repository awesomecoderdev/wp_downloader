<?php

/**
 * Plugin Initialization
 *
 * @link              https://awesomecoder.dev/
 * @since             1.0.0
 * @package           Awesomecoder
 *
 * @wordpress-plugin
 * Plugin Name:       WP Downloader
 * Plugin URI:        https://awesomecoder.dev/
 * Description:       This is custom plugin that implement youtube and TikTok video download functionality.
 * Version:           1.0.0
 * Author:            Mohammad Ibrahim
 * Author URI:        https://awesomecoder.dev/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       awesomecoder
 * Domain Path:       /languages
 *                                                              __
 *                                                             | |
 *    __ ___      _____  ___  ___  _ __ ___   ___  ___ ___   __| | ___ _ ____
 *   / _` \ \ /\ / / _ \/ __|/ _ \| '_ ` _ \ / _ \/ __/ _ \ / _` |/ _ \ ' __|
 *  | (_| |\ V  V /  __/\__ \ (_) | | | | | |  __/ (_| (_) | (_| |  __/	 |
 *  \__,_| \_/\_/ \___||___/\___/|_| |_| |_|\___|\___\___/ \__,_|\___|__|
 *
 */

use AwesomeCoder\Plugin\AC_Downloader\Core\Plugin;

// If this file is called directly, abort.
!defined('WPINC') ? die : include("plugin.php");

/**
 * set schedules if not scheduled.
 *
 * @since    1.0.0
 */
if (!wp_next_scheduled('tiktok_cron_hook')) {
    wp_schedule_event(time(), 'tiktok_schedules', 'tiktok_cron_hook');
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('AWESOMECODER_AC_DOWNLOADER_VERSION', '1.0.0');
define('AWESOMECODER_AC_DOWNLOADER_URL', plugin_dir_url(__FILE__));
define('AWESOMECODER_AC_DOWNLOADER_PATH', plugin_dir_path(__FILE__));
define('AWESOMECODER_AC_DOWNLOADER_BASENAME', plugin_basename(__FILE__));

/**
 * The activate and deactivation action of the plugin.
 *
 * @link       https://awesomecoder.dev/
 * @since      1.0.0
 *
 * @package    Awesomecoder
 * @subpackage Awesomecoder/controller
 */

register_activation_hook(__FILE__, [Plugin::class, 'activate']);
register_deactivation_hook(__FILE__, [Plugin::class, 'deactivate']);

/**
 * Load core of the plugin.
 *
 * @link       https://awesomecoder.dev/
 * @since      1.0.0
 *
 * @package    Awesomecoder
 * @subpackage Awesomecoder/controller
 */
Plugin::core();
