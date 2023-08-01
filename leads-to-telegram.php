<?php
/**
 * Plugin Name: Leads to Telegram
 * Version: 1.0.0
 * Plugin URI: http://no-url.yet/
 * Description: Send leads to telegram and copy to email
 * Author: Constantine
 * Author URI: 
 * Requires at least: 6.0
 *
 * Text Domain: k_telegram
 * Domain Path: /lang/
 *
 * @package Leads-to-telegram
 * @author Constantine
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Load plugin components
require_once 'constants.php';
require_once 'settings.php';
require_once 'shortcodes-form.php';
require_once 'shortcodes-other.php';
require_once 'process.php';


function k_telegram_load_textdomain() {
    load_plugin_textdomain('k_telegram', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/');
}
add_action('plugins_loaded', 'k_telegram_load_textdomain');

