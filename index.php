<?php

/**
 * Plugin Name: SBWC Google Tag Manager (Pixel) & FB Conversion API Settings
 * Description: Settings for and implementation of the above Google Tag Manager (Pixel) & FB Conversion API
 * Version: 1.0.0
 * Author: WC Bessinger
 */

if (!defined('ABSPATH')) :
    exit();
endif;

// constants
if (!defined('SBWC_GTMP_PATH')) :
    define('SBWC_GTMP_PATH', plugin_dir_path(__FILE__));
endif;

if (!defined('SBWC_GTMP_URL')) :
    define('SBWC_GTMP_URL', plugin_dir_url(__FILE__));
endif;

// init
add_action('plugins_loaded', 'sbwc_gtmp_init');

function sbwc_gtmp_init()
{
    // core class
    include SBWC_GTMP_PATH . 'class/SBWC_GTMP_Admin.php';
}
