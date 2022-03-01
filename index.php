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

// if Google Tag Manager for WordPress not present, display error message and don't load plugin files
if (!defined('GTM4WP_PATH')) :

    function sbwc_gtmp_gtm4wp_error()
    { ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('<b>Google Tag Manager for WordPress needs to be installed and activated in order for SBWC Google Tag Manager (Pixel) & FB Conversion API Settings plugin to work.</b>', 'default'); ?></p>
        </div>
<?php }

    add_action('admin_notices', 'sbwc_gtmp_gtm4wp_error');

// if Google Tag Manager for WordPress present, load plugin files
elseif (defined('GTM4WP_PATH')) :

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

endif;
