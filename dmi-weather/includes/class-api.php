<?php
/**
 * DMI Weather API Handler
 * 
 * Handles API calls to DMI and caching using WordPress Transients
 */

if (!defined('ABSPATH')) exit;

class DMI_Weather_API {
    
    private $api_url = 'https://dmi.cma.dk/api/weather/forecast/dianalund';
    private $cache_key = 'dmi_weather_data';
    private $cache_expiration = 3600; // 1 hour in seconds
    
    /**
     * Get weather forecast (from cache or API)
     * 
     * @return array|false Forecast data or false on failure
     */
    public function get_forecast() {
        // Check transient cache first
        $cached_data = get_transient($this->cache_key);
        
        if ($cached_data !== false) {
            return $cached_data;
        }
        
        // Fetch from API if cache is expired or empty
        $data = $this->fetch_from_api();
        
        if ($data) {
            // Parse and format the data
            $formatted_data = $this->parse_forecast_data($data);
            
            // Store in transient for 1 hour
            set_transient($this->cache_key, $formatted_data, $this->cache_expiration);
            
            return $formatted_data;
        }
        
        return false;
    }
    
    /**
     * Fetch data from DMI API
     * 
     * @return array|false Raw API response or false on failure
     */
    private function fetch_from_api() {
        $url = $this->api_url . '?hours=12';
        
        $response = wp_remote_get($url, array(
            'timeout' => 15,
            'headers' => array(
                'Accept' => 'application/json'
            )
        ));
        
        // Check for errors
        if (is_wp_error($response)) {
            error_log('DMI Weather API Error: ' . $response->get_error_message());
            return false;
        }
        
        $status_code = wp_remote_retrieve_response_code($response);
        
        if ($status_code !== 200) {
            error_log('DMI Weather API returned status code: ' . $status_code);
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        // Validate response structure
        if (!$data || !isset($data['forecast']) || !is_array($data['forecast'])) {
            error_log('DMI Weather API returned invalid data structure');
            return false;
        }
        
        return $data;
    }
    
    /**
     * Parse and format forecast data
     * 
     * @param array $data Raw API response
     * @return array Formatted forecast data
     */
    private function parse_forecast_data($data) {
        $formatted = array(
            'location' => isset($data['location']['name']) ? $data['location']['name'] : 'Dianalund',
            'generated_at' => isset($data['generated_at']) ? $data['generated_at'] : '',
            'forecast' => array()
        );
        
        foreach ($data['forecast'] as $hour) {
            $forecast_item = array(
                'timestamp' => $hour['timestamp'],
                'time' => $this->format_time($hour['timestamp']),
                'temperature' => round($hour['temperature'], 1),
                'wind_speed' => round($hour['wind_speed'], 1),
                'precipitation' => round($hour['precipitation'], 1),
                'cloud_cover' => isset($hour['cloud_cover']) ? $hour['cloud_cover'] : 0,
                'wind_direction' => isset($hour['wind_direction']) ? $hour['wind_direction'] : 0
            );
            
            // Add wind chill if present (API includes it when temp ≤10°C and wind >1.3 m/s)
            if (isset($hour['wind_chill']) && $hour['wind_chill'] !== null) {
                $forecast_item['wind_chill'] = round($hour['wind_chill'], 1);
            }
            
            // Determine weather icon
            $forecast_item['icon'] = $this->determine_weather_icon(
                $forecast_item['precipitation'],
                $forecast_item['cloud_cover']
            );
            
            $formatted['forecast'][] = $forecast_item;
        }
        
        return $formatted;
    }
    
    /**
     * Format timestamp to display time
     * 
     * @param string $timestamp ISO 8601 timestamp
     * @return string Formatted time (e.g., "14:00")
     */
    private function format_time($timestamp) {
        try {
            $datetime = new DateTime($timestamp);
            return $datetime->format('H:i');
        } catch (Exception $e) {
            error_log('DMI Weather: Invalid timestamp format: ' . $timestamp);
            return '--:--';
        }
    }
    
    /**
     * Determine appropriate weather icon
     * 
     * @param float $precipitation Precipitation in mm
     * @param float $cloud_cover Cloud cover percentage
     * @return string Icon identifier
     */
    private function determine_weather_icon($precipitation, $cloud_cover) {
        // Rain takes priority
        if ($precipitation > 0.5) {
            return 'rain';
        }
        
        // Cloud-based conditions
        if ($cloud_cover > 75) {
            return 'cloudy';
        } elseif ($cloud_cover >= 25) {
            return 'partly-cloudy';
        } else {
            return 'sun';
        }
    }
}
