<?php
/**
 * Plugin Name: DMI Weather Dianalund
 * Description: Displays local weather forecast for Dianalund from DMI
 * Version: 1.0.0
 * Author: WebJax
 * Text Domain: dmi-weather
 */

// Security check
if (!defined('ABSPATH')) exit;

// Define constants
define('DMI_WEATHER_VERSION', '1.0.0');
define('DMI_WEATHER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DMI_WEATHER_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once DMI_WEATHER_PLUGIN_DIR . 'includes/class-api.php';
require_once DMI_WEATHER_PLUGIN_DIR . 'includes/class-icons.php';
require_once DMI_WEATHER_PLUGIN_DIR . 'includes/class-widget.php';

/**
 * Initialize plugin
 */
function dmi_weather_init() {
    // Enqueue styles and scripts
    add_action('wp_enqueue_scripts', 'dmi_weather_enqueue_assets');
    
    // Register AJAX handlers for logged-in users
    add_action('wp_ajax_get_weather', 'dmi_weather_ajax_handler');
    
    // Register AJAX handlers for non-logged-in users
    add_action('wp_ajax_nopriv_get_weather', 'dmi_weather_ajax_handler');
    
    // Register shortcode
    add_shortcode('dmi_weather', 'dmi_weather_shortcode');
}
add_action('init', 'dmi_weather_init');

/**
 * Enqueue plugin assets
 */
function dmi_weather_enqueue_assets() {
    wp_enqueue_style(
        'dmi-weather-style',
        DMI_WEATHER_PLUGIN_URL . 'assets/css/style.css',
        array(),
        DMI_WEATHER_VERSION
    );
    
    wp_enqueue_script(
        'dmi-weather-script',
        DMI_WEATHER_PLUGIN_URL . 'assets/js/script.js',
        array(),
        DMI_WEATHER_VERSION,
        true
    );
    
    // Pass AJAX URL to JavaScript
    wp_localize_script('dmi-weather-script', 'dmiWeather', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('dmi_weather_nonce')
    ));
}

/**
 * AJAX handler for weather data
 */
function dmi_weather_ajax_handler() {
    check_ajax_referer('dmi_weather_nonce', 'nonce');
    
    $api = new DMI_Weather_API();
    $forecast = $api->get_forecast();
    
    if ($forecast) {
        wp_send_json_success($forecast);
    } else {
        wp_send_json_error(array('message' => 'Failed to fetch weather data'));
    }
}

/**
 * Shortcode to display weather widget
 */
function dmi_weather_shortcode() {
    $widget = new DMI_Weather_Widget();
    return $widget->render();
}
