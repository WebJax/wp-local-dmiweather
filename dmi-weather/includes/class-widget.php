<?php
/**
 * DMI Weather Widget
 * 
 * Handles display of weather widget and popup
 */

if (!defined('ABSPATH')) exit;

class DMI_Weather_Widget {
    
    /**
     * Render the complete weather widget
     * 
     * @return string HTML markup
     */
    public function render() {
        ob_start();
        ?>
        <div class="dmi-weather-container">
            <?php echo $this->render_header_icon(); ?>
            <?php echo $this->render_forecast_popup(); ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render header icon
     * 
     * @return string HTML markup
     */
    public function render_header_icon() {
        ob_start();
        ?>
        <div class="dmi-weather-icon" id="dmi-weather-icon" title="Click to view weather forecast">
            <?php echo DMI_Weather_Icons::get_header_icon(32); ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render forecast popup container
     * 
     * @return string HTML markup
     */
    public function render_forecast_popup() {
        ob_start();
        ?>
        <div class="dmi-weather-popup" id="dmi-weather-popup">
            <div class="weather-popup-header">
                <h3>Weather Forecast - Dianalund</h3>
                <button class="weather-popup-close" id="dmi-weather-close">&times;</button>
            </div>
            <div class="weather-forecast-container">
                <div class="weather-forecast" id="dmi-weather-forecast">
                    <div class="weather-loading">Loading weather data...</div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render individual hour forecast
     * 
     * @param array $hour_data Hour forecast data
     * @return string HTML markup
     */
    public static function render_hour($hour_data) {
        ob_start();
        ?>
        <div class="weather-hour">
            <div class="weather-time"><?php echo esc_html($hour_data['time']); ?></div>
            <div class="weather-icon">
                <?php echo DMI_Weather_Icons::get_icon($hour_data['icon'], 48); ?>
            </div>
            <div class="weather-temp">
                <?php 
                echo esc_html($hour_data['temperature']) . '°C';
                
                // Show wind chill if present
                if (isset($hour_data['wind_chill'])) {
                    echo '<br><span class="feels-like">(feels like ' . esc_html($hour_data['wind_chill']) . '°C)</span>';
                }
                ?>
            </div>
            <div class="weather-wind">
                <span class="weather-label">Wind:</span> <?php echo esc_html($hour_data['wind_speed']); ?> m/s
            </div>
            <div class="weather-precipitation">
                <span class="weather-label">Rain:</span> <?php echo esc_html($hour_data['precipitation']); ?> mm
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
