<?php
/*
PayBox is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

PayBox is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with PayBox . If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

/**
 * Plugin Name: PayBox
 * Description: Receive payments using the PayBox payments provider.
 * Author: PayBox
 * Author URI: https://paybox.money/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version: 1.9.2
 * WC tested up to: 5.8.2
 * WC requires at least: 2.6
 *
 * Copyright (c) 2014-2017 WooCommerce
 *
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WC Detection
 */
if (!function_exists('is_woocommerce_active')) {
    function is_woocommerce_active()
    {
        return in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
    }
}

/**
 * Initialize the gateway.
 * @since 1.0.0
 */
function paybox_init()
{
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    define('WC_GATEWAY_PAYBOX_VERSION', '1.9.2');

    require_once(plugin_basename('paybox/includes/class-wc-paybox-payment-gateway.php'));
    load_plugin_textdomain('paybox-payment-gateway', false, trailingslashit(dirname(plugin_basename(__FILE__))));
    add_filter('woocommerce_payment_gateways', 'paybox_add_gateway');
}

add_action('plugins_loaded', 'paybox_init', 0);

function paybox_plugin_links($links)
{
    $settings_url = add_query_arg(
        array(
            'page'    => 'wc-settings',
            'tab'     => 'checkout',
            'section' => 'wc_paybox_payment_gateway',
        ),
        admin_url('admin.php')
    );

    $plugin_links = array(
        '<a href="' . esc_url($settings_url) . '">' . __('Settings', 'paybox-payment-gateway') . '</a>'
    );

    return array_merge($plugin_links, $links);
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'paybox_plugin_links');

/**
 * Add the gateway to WooCommerce
 * @since 1.0.0
 */
function paybox_add_gateway($methods)
{
    $methods[] = 'WC_Paybox_Payment_Gateway';

    return $methods;
}
