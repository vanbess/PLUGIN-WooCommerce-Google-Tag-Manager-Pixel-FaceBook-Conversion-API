<?php

// Core class

class SBWC_GTMP_Admin
{

    /**
     * Class init
     *
     * @return void
     */
    public static function init()
    {
        // admin admin page
        add_action('admin_menu', [__CLASS__, 'sbwc_gtag_admin_settings']);

        // admin scripts
        add_action('admin_head', [__CLASS__, 'sbwc_gtag_scripts']);

        // compile gtm4wp data layer
        add_filter('gtm4wp_compile_datalayer', [__CLASS__, 'sbwc_gtag_add_gtm_currency_code'], 999);

        // add fb payment event
        add_filter('woocommerce_checkout_posted_data', [__CLASS__, 'sbwc_gtag_add_fb_payment_event'], 1, 997);

        // add fb atc event
        add_filter('woocommerce_add_to_cart_product_id', [__CLASS__, 'sbwc_gtag_add_fb_atc_event'], 1, 997);
    }

    /**
     * Register admin settings page
     *
     * @return void
     */
    public static function sbwc_gtag_admin_settings()
    {
        add_menu_page(
            __('Google Tag Manager (Pixel) & FB Conversion API Settings', 'default'),
            __('Google Pixel Settings', 'default'),
            'manage_options',
            'sbwc-gtag-manager-settings',
            [__CLASS__, 'admin_settings_render'],
            'dashicons-google',
            10
        );
    }

    /**
     * Admin scripts
     *
     * @return void
     */
    public static function sbwc_gtag_scripts()
    {
        wp_enqueue_script('sbwc-gtag-css', self::css(), [], false);
    }

    /**
     * Render admin settings
     *
     * @return void
     */
    public static function admin_settings_render()
    {
        // retrieve title
        global $title;

?>

        <div id="sbwc-gtag-settings-cont">

            <h2 id="sbwc-gtag-settings-head"><?php _e($title, 'default'); ?></h2>

            <div id="sbwc-gtag-settings-form-cont">

                <?php
                // save/update submitted settings
                self::admin_settings_save();
                ?>

                <p>
                    <i>
                        <b>
                            <?php _e('<u>NOTE:</u> To disable any of the below, simply leave the corresponding input empty and save.', 'default'); ?>
                        </b>
                    </i>
                </p>

                <form id="sbwc-gtag-settings-form" action="" method="post">

                    <!-- fbpixel id -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_fbpixel_id"><?php _e('Facebook Pixel ID', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_fbpixel_id" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_fbpixel_id'); ?>">
                    </div>

                    <!-- adword pixel -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_adword_pixel_id"><?php _e('Google Adword Pixel ID', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_adword_pixel" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_adword_pixel'); ?>">
                    </div>

                    <!-- adword label -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_adword_label"><?php _e('Google Adword Label', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_adword_label" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_adword_label'); ?>">
                    </div>

                    <!-- yahoo jp id -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_yahoo_jp_id"><?php _e('Yahoo Japan ID', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_yahoo_jp_id" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_yahoo_jp_id'); ?>">
                    </div>

                    <!-- yahoo jp label -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_yahoo_jp_label"><?php _e('Yahoo Japan Label', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_yahoo_jp_label" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_yahoo_jp_label'); ?>">
                    </div>

                    <!-- yahoo jp rt id -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_yahoo_jp_rt_id"><?php _e('Yahoo Japan Retargeting ID', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_yahoo_jp_rt_id" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_yahoo_jp_rt_id'); ?>">
                    </div>

                    <!-- yahoo ydn conv io -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_yahoo_ydn_conv_io"><?php _e('Yahoo Developer Network Conversion IO', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_yahoo_ydn_conv_io" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_yahoo_ydn_conv_io'); ?>">
                    </div>

                    <!-- yahoo ydn conv label -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_yahoo_ydn_conv_label"><?php _e('Yahoo Developer Network Conversion Label', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_yahoo_ydn_conv_label" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_yahoo_ydn_conv_label'); ?>">
                    </div>

                    <!-- yahoo retargeting id -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_yahoo_rt_id"><?php _e('Yahoo Retargeting ID', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_yahoo_rt_id" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_yahoo_rt_id'); ?>">
                    </div>

                    <!-- gemini pixel id -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_gemini_pixel_id"><?php _e('Gemini Pixel ID', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_gemini_pixel_id" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_gemini_pixel_id'); ?>">
                    </div>

                    <!-- pinterest pixel -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_pinterest_pixel_id"><?php _e('Pinterest Pixel ID', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_pinterest_pixel_id" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_pinterest_pixel_id'); ?>">
                    </div>

                    <!-- taboola id -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_taboola_pixel_id"><?php _e('Taboola Pixel ID', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_taboola_pixel_id" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_taboola_pixel_id'); ?>">
                    </div>

                    <!-- twitter pixel -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_twitter_pixel_id"><?php _e('Twitter Pixel ID', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_twitter_pixel_id" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_twitter_pixel_id'); ?>">
                    </div>

                    <!-- tiktok pixel id -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_tiktok_pixel_id"><?php _e('Tiktok Pixel ID', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_tiktok_pixel_id" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_tiktok_pixel_id'); ?>">
                    </div>

                    <!-- outbrain id -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_outbrain_pixel_id"><?php _e('Outbrain Pixel ID', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_outbrain_pixel_id" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_outbrain_pixel_id'); ?>">
                    </div>

                    <!-- bing uet -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_bing_uet"><?php _e('Bing Universal Event Tracking ID', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_bing_uet" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_bing_uet'); ?>">
                    </div>

                    <!-- quora pixel id -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_quora_pixel_id"><?php _e('Quora Pixel ID', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_quora_pixel_id" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_quora_pixel_id'); ?>">
                    </div>

                    <!-- snap pixel id -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_snap_pixel_id"><?php _e('Snap Pixel ID', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_snap_pixel_id" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_snap_pixel_id'); ?>">
                    </div>

                    <!-- totest id -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_totest_id"><?php _e('Totest ID', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_totest_id" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_totest_id'); ?>">
                    </div>

                    <!-- mautic url -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_mautic_url"><?php _e('Mautic URL', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_mautic_url" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_mautic_url'); ?>">
                    </div>

                    <!-- Facebook Conversion API key (FB access token) -->
                    <div>
                        <label class="sbwc-gtag-settings-input-label" for="sbwc_gtag_fb_access_token"><?php _e('Facebook Conversion API Key (FB access token)', 'default'); ?></label>
                        <input type="text" name="sbwc_gtag_fb_access_token" class="sbwc-gtag-settings-input" value="<?php echo get_option('sbwc_gtag_fb_access_token'); ?>" style="width: 60%">
                    </div>

                    <!-- submit -->
                    <div>
                        <input class="button button-primary button-large" type="submit" name="sbwc-gtag-save-settings" value="<?php _e('Save Settings', 'defualt'); ?>">
                    </div>
                </form>
            </div>
        </div>


        <?php }

    /**
     * Save admin settings via ajax
     *
     * @return void
     */
    public static function admin_settings_save()
    {

        // if save is triggered
        if (isset($_POST['sbwc-gtag-save-settings'])) : ?>
            <div class="notice notice-success is-dismissible">
                <p><i><b><?php _e('Settings saved', 'default'); ?></b></i></p>
            </div>
        <?php

            // update data
            update_option('sbwc_gtag_fbpixel_id', $_POST['sbwc_gtag_fbpixel_id']);
            update_option('sbwc_gtag_adword_pixel', $_POST['sbwc_gtag_adword_pixel']);
            update_option('sbwc_gtag_adword_label', $_POST['sbwc_gtag_adword_label']);
            update_option('sbwc_gtag_yahoo_jp_id', $_POST['sbwc_gtag_yahoo_jp_id']);
            update_option('sbwc_gtag_yahoo_jp_label', $_POST['sbwc_gtag_yahoo_jp_label']);
            update_option('sbwc_gtag_yahoo_jp_rt_id', $_POST['sbwc_gtag_yahoo_jp_rt_id']);
            update_option('sbwc_gtag_yahoo_ydn_conv_io', $_POST['sbwc_gtag_yahoo_ydn_conv_io']);
            update_option('sbwc_gtag_yahoo_ydn_conv_label', $_POST['sbwc_gtag_yahoo_ydn_conv_label']);
            update_option('sbwc_gtag_yahoo_rt_id', $_POST['sbwc_gtag_yahoo_rt_id']);
            update_option('sbwc_gtag_gemini_pixel_id', $_POST['sbwc_gtag_gemini_pixel_id']);
            update_option('sbwc_gtag_pinterest_pixel_id', $_POST['sbwc_gtag_pinterest_pixel_id']);
            update_option('sbwc_gtag_taboola_pixel_id', $_POST['sbwc_gtag_taboola_pixel_id']);
            update_option('sbwc_gtag_twitter_pixel_id', $_POST['sbwc_gtag_twitter_pixel_id']);
            update_option('sbwc_gtag_tiktok_pixel_id', $_POST['sbwc_gtag_tiktok_pixel_id']);
            update_option('sbwc_gtag_bing_uet', $_POST['sbwc_gtag_bing_uet']);
            update_option('sbwc_gtag_outbrain_pixel_id', $_POST['sbwc_gtag_outbrain_pixel_id']);
            update_option('sbwc_gtag_quora_pixel_id', $_POST['sbwc_gtag_quora_pixel_id']);
            update_option('sbwc_gtag_snap_pixel_id', $_POST['sbwc_gtag_snap_pixel_id']);
            update_option('sbwc_gtag_totest_id', $_POST['sbwc_gtag_totest_id']);
            update_option('sbwc_gtag_mautic_url', $_POST['sbwc_gtag_mautic_url']);
            update_option('sbwc_gtag_fb_access_token', $_POST['sbwc_gtag_fb_access_token']);

        endif;
    }

    /**
     * Google Tag Manager - Add Currency
     *
     * @param array $gtm4wp_datalayer_data
     * @return array $gtm4wp_datalayer_data
     */
    public static function sbwc_gtag_add_gtm_currency_code($gtm4wp_datalayer_data)
    {

        /**
         * Setup base variables
         */
        $fb_pixel_id          = get_option('sbwc_gtag_fbpixel_id');
        $adword_pixel         = get_option('sbwc_gtag_adword_pixel');
        $adword_label         = get_option('sbwc_gtag_adword_label');
        $yahoo_jp_id          = get_option('sbwc_gtag_yahoo_jp_id');
        $yahoo_jp_label       = get_option('sbwc_gtag_yahoo_jp_label');
        $yahoo_jp_rt_id       = get_option('sbwc_gtag_yahoo_jp_rt_id');
        $yahoo_ydn_conv_io    = get_option('sbwc_gtag_yahoo_ydn_conv_io');
        $yahoo_ydn_conv_label = get_option('sbwc_gtag_yahoo_ydn_conv_label');
        $yahoo_rt_id          = get_option('sbwc_gtag_yahoo_rt_id');
        $gemini_pixel_id      = get_option('sbwc_gtag_gemini_pixel_id');
        $pinterest_pixel_id   = get_option('sbwc_gtag_pinterest_pixel_id');
        $taboola_pixel_id     = get_option('sbwc_gtag_taboola_pixel_id');
        $twitter_pixel_id     = get_option('sbwc_gtag_twitter_pixel_id');
        $tiktok_pixel_id      = get_option('sbwc_gtag_tiktok_pixel_id');
        $bing_uet             = get_option('sbwc_gtag_bing_uet');
        $outbrain_pixel_id    = get_option('sbwc_gtag_outbrain_pixel_id');
        $quora_pixel_id       = get_option('sbwc_gtag_quora_pixel_id');
        $snap_pixel_id        = get_option('sbwc_gtag_snap_pixel_id');
        $totest_id            = get_option('sbwc_gtag_totest_id');
        $mautic_url           = get_option('sbwc_gtag_mautic_url');
        $fb_access_token      = get_option('sbwc_gtag_fb_access_token');

        /**
         * FB pixel id
         */
        if ($fb_pixel_id && !empty($fb_pixel_id)) :
            $gtm4wp_datalayer_data['fbpixel_id'] = $fb_pixel_id;
        endif;

        /**
         * Google Adword
         */
        if ($adword_pixel && !empty($adword_pixel)) :
            $gtm4wp_datalayer_data['adword_pixel'] = $adword_pixel;
        endif;

        if ($adword_label && !empty($adword_label)) :
            $gtm4wp_datalayer_data['adword_label'] = $adword_label;
        endif;

        /**
         * Yahoo Japan
         */
        if ($yahoo_jp_id && !empty($yahoo_jp_id)) :
            $gtm4wp_datalayer_data['yahoojp_id'] = $yahoo_jp_id;
        endif;

        if ($yahoo_jp_label && !empty($yahoo_jp_label)) :
            $gtm4wp_datalayer_data['yahoojp_label'] = $yahoo_jp_label;
        endif;

        if ($yahoo_jp_rt_id && !empty($yahoo_jp_rt_id)) :
            $gtm4wp_datalayer_data['yahoojp_rt_id'] = $yahoo_jp_rt_id;
        endif;

        /**
         * Yahoo YDN
         */
        if ($yahoo_ydn_conv_io && !empty($yahoo_ydn_conv_io)) :
            $gtm4wp_datalayer_data['yahoo_ydn_conv_io'] = $yahoo_ydn_conv_io;
        endif;

        if ($yahoo_ydn_conv_label && !empty($yahoo_ydn_conv_label)) :
            $gtm4wp_datalayer_data['yahoo_ydn_conv_label'] = $yahoo_ydn_conv_label;
        endif;

        if ($yahoo_rt_id && !empty($yahoo_rt_id)) :
            $gtm4wp_datalayer_data['yahoo_retargeting_id'] = $yahoo_rt_id;
        endif;

        /**
         * Gemini
         */
        if ($gemini_pixel_id && !empty($gemini_pixel_id)) :
            $gtm4wp_datalayer_data['gemini_pixel_id'] = $gemini_pixel_id;
        endif;

        /**
         * Pinterest
         */
        if ($pinterest_pixel_id && !empty($pinterest_pixel_id)) :
            $gtm4wp_datalayer_data['pinterest_pixel'] = $pinterest_pixel_id;
        endif;

        /**
         * Taboola
         */
        if ($taboola_pixel_id && !empty($taboola_pixel_id)) :
            $gtm4wp_datalayer_data['taboola_id'] = $taboola_pixel_id;
        endif;

        /**
         * Twitter
         */
        if ($twitter_pixel_id && !empty($twitter_pixel_id)) :
            $gtm4wp_datalayer_data['twitter_pixel'] = $twitter_pixel_id;
        endif;

        /**
         * Tiktok
         */
        if ($tiktok_pixel_id && !empty($tiktok_pixel_id)) :
            $gtm4wp_datalayer_data['tiktok_pixel_id'] = $tiktok_pixel_id;
        endif;

        /**
         * Outbrain
         */
        if ($outbrain_pixel_id && !empty($outbrain_pixel_id)) :
            $gtm4wp_datalayer_data['outbrain_id'] = $outbrain_pixel_id;
        endif;

        /**
         * Bing UET
         */
        if ($bing_uet && !empty($bing_uet)) :
            $gtm4wp_datalayer_data['bing_uet'] = $bing_uet;
        endif;

        /**
         * Quora
         */
        if ($quora_pixel_id && !empty($quora_pixel_id)) :
            $gtm4wp_datalayer_data['quora_pixel_id'] = $quora_pixel_id;
        endif;

        /**
         * Snap
         */
        if ($snap_pixel_id && !empty($snap_pixel_id)) :
            $gtm4wp_datalayer_data['snap_pixel_id'] = $snap_pixel_id;
        endif;

        /**
         * Totest
         */
        if ($totest_id && !empty($totest_id)) :
            $gtm4wp_datalayer_data['totest_id'] = $totest_id;
        endif;

        /**
         * Mautic URL
         */
        if ($mautic_url && !empty($mautic_url)) :
            $gtm4wp_datalayer_data['mautic_url'] = $mautic_url;
        endif;

        /**
         * Retrieve currency code
         */
        $gtm4wp_datalayer_data['ecomm_currencycode'] = get_woocommerce_currency();

        /**
         * Setup event source url
         */
        $event_source_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        /**
         * Setup USD and JPY amounts
         */
        if (isset($gtm4wp_datalayer_data['ecomm_totalvalue']) && $gtm4wp_datalayer_data['ecomm_totalvalue']) :

            // USD setup
            $atts = array(
                'price'         => $gtm4wp_datalayer_data['ecomm_totalvalue'],
                'currency_from' => get_woocommerce_currency(),
                'currency'      => "USD",
            );

            $amount_usd = floatval(preg_replace('#[^\d.]#', '', alg_convert_price($atts)));
            $gtm4wp_datalayer_data['usd_amount'] = $amount_usd;

            // JPY setup
            $jpy_atts = array(
                'price'         => $gtm4wp_datalayer_data['ecomm_totalvalue'],
                'currency_from' => get_woocommerce_currency(),
                'currency'      => "JPY",
            );

            $amount_jpy = floatval(preg_replace('#[^\d.]#', '', alg_convert_price($jpy_atts)));
            $gtm4wp_datalayer_data['jpy_amount'] = $amount_jpy;

        endif;

        /**
         * setup country
         */
        $gtm4wp_datalayer_data['country'] = $_SERVER['HTTP_CF_IPCOUNTRY'];

        /**
         * setup domain name
         */
        $url         = $_SERVER['SERVER_NAME'];
        $url         = (substr($url, 0, 4) != 'http') ? 'http: //' . $url : $url;
        $url         = preg_replace('~\.(com|info|net|io|us|org|me|co\.uk|ca|mobi)\b~i', '', parse_url($url)['host']);
        $domain_name = substr($url, strrpos($url, '.'));

        $gtm4wp_datalayer_data['sitename'] = $domain_name;

        /**
         * setup ecomm page type
         */
        $post_id = get_queried_object_id();

        if ($post_id) :

            $lp_setting = get_post_meta($post_id, 'is_it_a_landing_page', true);

            if ($lp_setting == 'Yes') :
                $gtm4wp_datalayer_data['ecomm_pagetype'] = "landingpage";
            endif;

        endif;

        /**
         * setup visitor source
         */
        if ((isset($_GET['srcid']) && $_GET['srcid'] == "5cb7cb421aef8c000187b8b3") || (isset($_COOKIE['srcid']) && $_COOKIE['srcid'] == "5cb7cb421aef8c000187b8b3")) :
            $gtm4wp_datalayer_data['visitorSource'] = "outbrain";
        elseif ((isset($_GET['srcid']) && $_GET['srcid'] == "5cb9c66a1aef8c000187bb75") || (isset($_COOKIE['srcid']) && $_COOKIE['srcid'] == "5cb9c66a1aef8c000187bb75")) :
            $gtm4wp_datalayer_data['visitorSource'] = "facebook";
        endif;

        /**
         * setup product category if applicable
         */
        if (is_product() && isset($gtm4wp_datalayer_data['postID'])) :
            $gtm4wp_datalayer_data['ecomm_prodcat'] = gtm4wp_get_product_category($gtm4wp_datalayer_data['postID']);
        endif;

        /**
         * setup checkout product data
         */
        if (isset($gtm4wp_datalayer_data['ecommerce']['checkout']['products']) && is_array($gtm4wp_datalayer_data['ecommerce']['checkout']['products'])) :

            foreach ($gtm4wp_datalayer_data['ecommerce']['checkout']['products'] as $tk_product) :

                $gtm4wp_datalayer_data['tk_contents'][] = array(
                    'content_id' => $tk_product['id'],
                    'content_type' => 'product',
                    'quantity' => $tk_product['quantity'],
                );

                $gtm4wp_datalayer_data['yj_items'][] = array(
                    'item_id' => $tk_product['id'],
                    'category_id' => 'product',
                    'quantity' => $tk_product['quantity'],
                );

            endforeach;

        endif;

        /**
         * setup event id
         */
        if (WC()->session->get_customer_id()) {
            $gtm4wp_datalayer_data['event_id'] = $domain_name . ".cus." . WC()->session->get_customer_id();
        }

        /**
         * If is order received page
         */
        if (is_order_received_page()) :

            // start session if not started
            if (!session_id()) :
                session_start();
            endif;

            // setup order id
            $order_id = empty($_GET["order"]) ? ($GLOBALS["wp"]->query_vars["order-received"] ? $GLOBALS["wp"]->query_vars["order-received"] : 0) : absint($_GET["order"]);

            // if order id not 0
            if ($order_id > 0) :

                // retrieve order object
                $order = wc_get_order($order_id);

                // if order object retrieved
                if ($order) :

                    // retrieve order number
                    $order_number = $order->get_order_number();

                    // add address data to data layer
                    $gtm4wp_datalayer_data['orderEmail']       = $order->get_billing_email();
                    $gtm4wp_datalayer_data['orderShipFn']      = hash("sha256", $order->get_billing_first_name());
                    $gtm4wp_datalayer_data['orderShipLn']      = hash("sha256", $order->get_billing_last_name());
                    $gtm4wp_datalayer_data['orderShipCity']    = hash("sha256", $order->get_shipping_city());
                    $gtm4wp_datalayer_data['orderShipState']   = hash("sha256", $order->get_shipping_state());
                    $gtm4wp_datalayer_data['orderShipZip']     = hash("sha256", $order->get_shipping_postcode());
                    $gtm4wp_datalayer_data['orderShipCountry'] = hash("sha256", $order->get_shipping_country());
                    $gtm4wp_datalayer_data['orderShipPhone']   = hash("sha256", preg_replace("[^0-9]", "", $order->get_billing_phone()));
                    $gtm4wp_datalayer_data['fb_event_id']      = "nordace." . $order_number;
                    $gtm4wp_datalayer_data['orderNumber']      = $order_number;

                    //Facebook S2S Pixel Update
                    $event_data = array();

                    $hashed_em                          = hash("sha256", $order->get_billing_email());
                    $gtm4wp_datalayer_data['hashed_em'] = $hashed_em;
                    $hashed_ph                          = hash("sha256", preg_replace("[^0-9]", "", $order->get_billing_phone()));
                    $hashed_country                     = hash("sha256", strtolower($order->get_shipping_country()));
                    $fbc                                = $_COOKIE['_fbc'];
                    $fbp                                = $_COOKIE['_fbp'];

                    if (!$fbc) :

                        $order_fbclid = get_post_meta($order_id, '_order_fbclid', true);

                        if ($order_fbclid != "") :
                            $fbc = $order_fbclid;
                        elseif (isset($_SESSION['fbclid']) && $_SESSION['fbclid'] != "") :
                            $fbc = $_SESSION['fbclid'];
                        elseif (isset($_COOKIE['fbclid']) && $_COOKIE['fbclid'] != "") :
                            $fbc = $_COOKIE['fbclid'];
                        endif;

                    endif;

                    $skus           = array();
                    $order_currency = $order->get_currency();
                    $order_value    = $order->get_total();

                    // Loop through ordered items
                    foreach ($order->get_items() as $item) :

                        // retrieve product object
                        $product = wc_get_product($item->get_product_id());

                        // retrieve product sku
                        if ($product->get_sku() != "") :
                            $skus[] = $product->get_sku();
                        endif;

                    endforeach;

                    // DEBUG/TEST
                    file_put_contents(SBWC_GTMP_PATH . 'logs/debug_fbpixel.txt', "URL: " . $event_source_url . " \n", FILE_APPEND);
                    file_put_contents(SBWC_GTMP_PATH . 'logs/server_data.txt', print_r($_SERVER, true), FILE_APPEND);

                    $event_data = array(array(
                        'event_name'       => 'Purchase',
                        'action_source'    => 'website',
                        'event_source_url' => $event_source_url,
                        'event_time'       => time(),
                        'event_id'         => $domain_name . "." . $order_number,
                        'user_data'        => array(
                            'client_ip_address' => $_SERVER['REMOTE_ADDR'],
                            'client_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                            'em'                => $hashed_em,
                            'ph'                => $hashed_ph,
                            'ln'                => $gtm4wp_datalayer_data['orderShipLn'],
                            'fn'                => $gtm4wp_datalayer_data['orderShipFn'],
                            'ct'                => $gtm4wp_datalayer_data['orderShipCity'],
                            'st'                => $gtm4wp_datalayer_data['orderShipState'],
                            'zp'                => $gtm4wp_datalayer_data['orderShipZip'],
                            'country'           => $hashed_country,
                            'fbp'               => $fbp,
                        ),
                        'custom_data' => array(
                            'value'        => $order_value,
                            'currency'     => $order_currency,
                            'content_ids'  => $skus,
                            'content_type' => 'product',
                        ),
                    ));

                    if ($fbc != "") :
                        $event_data[0]['user_data']['fbc'] = $fbc;
                    endif;

                    // self::sbwc_send_fb_api_request($event_data, $fb_access_token, $gtm4wp_datalayer_data['fbpixel_id']);
                    self::sbwc_send_fb_api_request($event_data, $fb_access_token, $fb_pixel_id);

                    // End of FB S2S

                    // Start Redtrack S2S
                    /** postback to redtrack **/
                    $ordermeta_clkid  = get_post_meta($order->get_id(), '_order_clkid', true);
                    $clkid_updated    = get_post_meta($order->get_id(), '_clkid_updated', true);
                    $wc_session_clkid = WC()->session->get('clkid');

                    if ($clkid_updated != 1 && (!empty($ordermeta_clkid) || isset($_SESSION['clkid']) || isset($_COOKIE['clkid']) || $wc_session_clkid)) :

                        $clkid = "";

                        if ($ordermeta_clkid) :
                            $clkid = $ordermeta_clkid;
                        elseif ($wc_session_clkid) :
                            $clkid = $wc_session_clkid;
                        elseif (isset($_SESSION['clkid'])) :
                            $clkid = $_SESSION['clkid'];
                        elseif ($_COOKIE['clkid']) :
                            $clkid = $_COOKIE['clkid'];
                        endif;

                        wp_remote_get('https://sel.rdtk.io/postback?clickid=' . $clkid . '&sum=' . $amount_usd . '&sub1=' . $order_number);

                        /**
                         * DEBUG/TEST
                         */
                        file_put_contents(SBWC_GTMP_PATH . 'logs/debug_redtrack.txt', 'Successfully Posted to RedTrack: ' . $clkid . ' & Order ID ' . $order_number . ' & Amount is ' . $amount_usd . "\n", FILE_APPEND);

                        $order->update_meta_data('_clkid_updated', 1);

                    endif;
                endif;
            endif;
        endif;

        /**
         * DEBUG/TEST
         */
        file_put_contents(SBWC_GTMP_PATH . 'logs/gtm4wp_datalayer_data.txt', print_r($gtm4wp_datalayer_data, true), FILE_APPEND);

        return $gtm4wp_datalayer_data;
    }

    /**
     * Add Facebook conversion payment event
     *
     * @param array $posted_data
     * @return array $posted_data
     */
    public static function sbwc_gtag_add_fb_payment_event($posted_data)
    {
        $fb_access_token = get_option('sbwc_gtag_fb_access_token');
        $fb_pixel_id     = get_option('sbwc_gtag_fbpixel_id');

        if (!WC()->cart->is_empty()) :

            if (isset(WC()->session) && method_exists(WC()->session, 'get_customer_id')) :

                $customer_id           = WC()->session->get_customer_id();
                $gtm4wp_datalayer_data = array();
                $gtm4wp_datalayer_data = (array) apply_filters(GTM4WP_WPFILTER_COMPILE_DATALAYER, $gtm4wp_datalayer_data);
                $fbp                   = $_COOKIE['_fbp'];
                $fbc                   = $_COOKIE['_fbc'];

                if (!$fbc && isset($_SESSION['fbclid']) && $_SESSION['fbclid'] != "") :
                    $fbc = $_SESSION['fbclid'];
                endif;

                //get skus
                $items     = WC()->cart->get_cart();
                $cart_skus = array();

                foreach ($items as $item => $values) :

                    // Retrieve WC_Product object from the product-id:
                    $_woo_product = wc_get_product($values['product_id']);

                    // Get SKU from the WC_Product object:
                    $product_sku = $_woo_product->get_sku();
                    $cart_skus[] = ($product_sku) ? $product_sku : $values['product_id'];

                endforeach;

                // setup event data array
                $atp_event_data = array(array(
                    'event_name'       => 'AddPaymentInfo',
                    'action_source'    => 'website',
                    'event_source_url' => $_SERVER['HTTP_REFERER'],
                    'event_time'       => time(),
                    'event_id'         => $gtm4wp_datalayer_data['event_id'],
                    'user_data'        => array(
                        'client_ip_address' => $_SERVER['REMOTE_ADDR'],
                        'client_user_agent' => $_SERVER['HTTP_USER_AGENT']
                    ),
                    'custom_data' => array(
                        'value'        => WC()->cart->get_cart_contents_total(),
                        'currency'     => $gtm4wp_datalayer_data['ecomm_currencycode'],
                        'content_ids'  => $cart_skus,
                        'content_type' => 'product',
                    ),
                ));

                if ($fbc != "") :
                    $atp_event_data[0]['user_data']['fbc'] = $fbc;
                endif;

                if ($fbp != "") :
                    $atp_event_data[0]['user_data']['fbp'] = $fbp;
                endif;

                if ($gtm4wp_datalayer_data['customerBillingEmail']) :
                    $atp_event_data[0]['user_data']['em'] = hash("sha256", $gtm4wp_datalayer_data['customerBillingEmail']);
                endif;

                if ($gtm4wp_datalayer_data['customerBillingPhone']) :
                    $atp_event_data[0]['user_data']['ph'] = hash("sha256", $gtm4wp_datalayer_data['customerBillingPhone']);
                endif;

                if ($gtm4wp_datalayer_data['customerBillingLastName']) :
                    $atp_event_data[0]['user_data']['ln'] = hash("sha256", $gtm4wp_datalayer_data['customerBillingLastName']);
                endif;

                if ($gtm4wp_datalayer_data['customerBillingFirstName']) :
                    $atp_event_data[0]['user_data']['fn'] = hash("sha256", $gtm4wp_datalayer_data['customerBillingFirstName']);
                endif;

                if ($gtm4wp_datalayer_data['customerBillingPostcode']) :
                    $atp_event_data[0]['user_data']['zp'] = hash("sha256", $gtm4wp_datalayer_data['customerBillingPostcode']);
                endif;

                if ($gtm4wp_datalayer_data['customerBillingPostcode']) :
                    $atp_event_data[0]['user_data']['zp'] = hash("sha256", $gtm4wp_datalayer_data['customerBillingPostcode']);
                endif;

                if ($posted_data['billing_state']) :
                    $atp_event_data[0]['user_data']['st'] = hash("sha256", $posted_data['billing_state']);
                endif;

                if ($posted_data['billing_country']) :
                    $atp_event_data[0]['user_data']['country'] = hash("sha256", strtolower($posted_data['billing_country']));;
                endif;

                // $fb_pixel_id = $gtm4wp_datalayer_data['fbpixel_id'];

                self::sbwc_send_fb_api_request($atp_event_data, $fb_access_token, $fb_pixel_id);

            endif;
        endif;

        return $posted_data;
    }

    /**
     * Add Facebook conversion add to cart event
     *
     * @param int $product_id
     * @return int $product_id
     */
    public static function sbwc_gtag_add_fb_atc_event($product_id)
    {

        $fb_access_token = get_option('sbwc_gtag_fb_access_token');
        $fb_pixel_id     = get_option('sbwc_gtag_fbpixel_id');

        if (isset(WC()->session) && method_exists(WC()->session, 'get_customer_id')) :

            $customer_id           = WC()->session->get_customer_id();
            $gtm4wp_datalayer_data = array();
            $gtm4wp_datalayer_data = (array) apply_filters(GTM4WP_WPFILTER_COMPILE_DATALAYER, $gtm4wp_datalayer_data);
            $fbp                   = $_COOKIE['_fbp'];
            $fbc                   = $_COOKIE['_fbc'];

            if (!$fbc && isset($_SESSION['fbclid']) && $_SESSION['fbclid'] != "") :
                $fbc = $_SESSION['fbclid'];
            endif;

            //get skus
            $_woo_product  = wc_get_product($product_id);
            $product_sku   = $_woo_product->get_sku();
            $content_id    = ($product_sku) ? $product_sku : $product_id;
            $product_price = $_woo_product->get_price();

            $atc_event_data = array(array(
                'event_name'       => 'AddToCart',
                'action_source'    => 'website',
                'event_source_url' => $_SERVER['HTTP_REFERER'],
                'event_time'       => time(),
                'event_id'         => $gtm4wp_datalayer_data['event_id'],
                'user_data'        => array(
                    'client_ip_address' => $_SERVER['REMOTE_ADDR'],
                    'client_user_agent' => $_SERVER['HTTP_USER_AGENT']
                ),
                'custom_data' => array(
                    'value'        => $product_price,
                    'currency'     => $gtm4wp_datalayer_data['ecomm_currencycode'],
                    'content_ids'  => $content_id,
                    'content_type' => 'product',
                ),
            ));

            // if FB pixel id is set
            // if (isset($gtm4wp_datalayer_data['fbpixel_id'])) :
            //     self::sbwc_send_fb_api_request($atc_event_data, $fb_access_token, $gtm4wp_datalayer_data['fbpixel_id']);
            // endif;
            if (isset($fb_pixel_id)) :
                self::sbwc_send_fb_api_request($atc_event_data, $fb_access_token, $fb_pixel_id);
            endif;

        endif;

        return $product_id;
    }

    /**
     * Send Facebook API request
     *
     * @param  array $event_data
     * @param  string $fb_access_token
     * @param  string $fb_pixel
     * @return void
     */
    public static function sbwc_send_fb_api_request($event_data, $fb_access_token, $fb_pixel_id)
    {

        $body = array(
            'data' => json_encode($event_data),
            'access_token' => $fb_access_token
        );

        $response = wp_remote_post(
            'https://graph.facebook.com/v9.0/' . $fb_pixel_id . '/events',
            array(
                'method'      => 'POST',
                'timeout'     => 5,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => false,
                'headers'     => array(),
                'body'        => $body,
                'cookies'     => array()
            )
        );

        /**
         * DEBUG/TEST
         */
        file_put_contents(SBWC_GTMP_PATH . 'logs/fb_pixel_atp_event_data.log', "atp_event_data: " . print_r($event_data, true) . " \n\n", FILE_APPEND);
        file_put_contents(SBWC_GTMP_PATH . 'logs/fb_pixel_atp_response_data.log', "atp_response_data: " . print_r($response, true) . " \n\n", FILE_APPEND);

        if (is_wp_error($response)) {

            $error_message = $response->get_error_message();

            /**
             * DEBUG/TEST
             */
            file_put_contents(SBWC_GTMP_PATH . 'logs/fb_pixel_error.log', "Something went wrong: $error_message\n", FILE_APPEND);
        }
    }

    /**
     * CSS for admin page
     *
     * @return void
     */
    public static function css()
    { ?>
        <style>
            .sbwc-gtag-settings-input-label {
                display: block;
                padding-bottom: 5px;
                font-weight: bold;
                padding-left: 2px;
                font-style: italic;
            }

            .sbwc-gtag-settings-input {
                min-width: 350px;
                width: 35%;
                margin-bottom: 15px;
            }

            #sbwc-gtag-settings-head {
                background: white;
                padding: 15px;
                margin-top: 0;
                margin-left: -19px;
                box-shadow: 0px 2px 4px #80808054;
            }
        </style>
<?php }
}

SBWC_GTMP_Admin::init();
