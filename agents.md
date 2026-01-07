# DMI Weather Plugin - Agent Instructions

## Project Overview

WordPress plugin that displays local weather forecast for Dianalund, Denmark in a header bar using DMI’s proxy API.

## Technical Stack

- PHP (WordPress plugin)
- HTML/CSS
- JavaScript (vanilla, no jQuery required)
- DMI Proxy API: `https://dmi.cma.dk/api/weather/`

## Requirements

### Functionality

1. Display weather icon in header bar
1. On click: show popup with weather forecast
1. Show 4 hours at a time from 12-hour forecast
1. Horizontal scroll to view remaining hours
1. Update data every hour
1. Use WordPress Transient API for caching

### Data Display (per hour)

- Temperature (°C)
- Wind speed (m/s)
- Precipitation (mm)
- Wind chill (°C) - only when temp ≤10°C and wind >1.3 m/s
- Weather icon (SVG)

### Location

- **City**: Dianalund
- **Postal code**: 4293 (alternative identifier)
- **API endpoint**: `https://dmi.cma.dk/api/weather/forecast/dianalund?hours=12`

### Visual Design

- Simple, minimal design
- Black/white SVG weather icons
- No fancy navigation (arrows, buttons) - just scroll
- Responsive layout
- Header icon subtle and unobtrusive

## API Documentation

### Endpoint

```
GET https://dmi.cma.dk/api/weather/forecast/dianalund?hours=12
```

### Response Structure

```json
{
  "location": {
    "name": "dianalund",
    "coordinates": {
      "lat": 55.5239,
      "lon": 11.4739
    }
  },
  "generated_at": "2025-01-15T12:00:00+00:00",
  "forecast": [
    {
      "timestamp": "2025-01-15T13:00:00+00:00",
      "temperature": 14.5,
      "wind_speed": 6.0,
      "wind_direction": 190.0,
      "wind_chill": 3.8,
      "precipitation": 0.5,
      "cloud_cover": 50.0
    }
  ]
}
```

### API Features

- No API key required
- Cached for 30 minutes on server side
- Wind chill automatically calculated when applicable
- Accepts city name, postal code, or coordinates

## Plugin Structure

```
dmi-weather/
├── dmi-weather.php          # Main plugin file
├── includes/
│   ├── class-api.php        # API calls and caching
│   ├── class-icons.php      # SVG weather icons
│   └── class-widget.php     # Header widget/display
├── assets/
│   ├── css/
│   │   └── style.css        # Plugin styles
│   └── js/
│       └── script.js        # AJAX and interactions
└── README.md                # Installation and usage
```

## Implementation Details

### 1. Main Plugin File (dmi-weather.php)

```php
/**
 * Plugin Name: DMI Weather Dianalund
 * Description: Displays local weather forecast for Dianalund from DMI
 * Version: 1.0.0
 * Author: Your Name
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

// Initialize plugin
function dmi_weather_init() {
    // Enqueue styles and scripts
    // Register AJAX handlers
    // Add action hooks
}
add_action('init', 'dmi_weather_init');
```

### 2. API Class (includes/class-api.php)

```php
class DMI_Weather_API {
    
    private $api_url = 'https://dmi.cma.dk/api/weather/forecast/dianalund';
    private $cache_key = 'dmi_weather_data';
    private $cache_expiration = 3600; // 1 hour
    
    public function get_forecast() {
        // Check transient cache first
        // If expired/empty, fetch from API
        // Parse response
        // Store in transient
        // Return formatted data
    }
    
    private function fetch_from_api() {
        // Use wp_remote_get()
        // Error handling
        // Validate response
    }
    
    private function parse_forecast_data($data) {
        // Extract relevant fields
        // Format for frontend
        // Handle missing wind_chill
    }
}
```

### 3. Icons Class (includes/class-icons.php)

```php
class DMI_Weather_Icons {
    
    public static function get_icon($condition, $precipitation = 0) {
        // Determine weather type from data
        // Return appropriate SVG
        // Icons needed:
        // - sun (clear)
        // - cloud (cloudy)
        // - cloud-sun (partly cloudy)
        // - rain (precipitation > 0.5mm)
        // - snow (winter + precipitation)
    }
    
    private static function svg_sun() {
        return '<svg>...</svg>';
    }
    
    // Additional icon methods...
}
```

### 4. Widget/Display Class (includes/class-widget.php)

```php
class DMI_Weather_Widget {
    
    public function render_header_icon() {
        // Output weather icon for header
        // Add click handler
    }
    
    public function render_forecast_popup() {
        // Output popup container
        // Display 4 hours initially
        // Add scroll container for remaining hours
    }
    
    public function ajax_get_weather() {
        // AJAX handler
        // Get forecast data
        // Return JSON
    }
}
```

### 5. JavaScript (assets/js/script.js)

```javascript
document.addEventListener('DOMContentLoaded', function() {
    
    // Toggle popup on icon click
    const weatherIcon = document.querySelector('.dmi-weather-icon');
    const weatherPopup = document.querySelector('.dmi-weather-popup');
    
    weatherIcon.addEventListener('click', function() {
        // Toggle popup visibility
        // Fetch data if needed
    });
    
    // Close popup on outside click
    document.addEventListener('click', function(e) {
        // Check if click outside
    });
    
    // Fetch weather data
    function fetchWeather() {
        // AJAX call to WordPress
        // Update popup content
    }
    
    // Auto-refresh every hour
    setInterval(fetchWeather, 3600000);
});
```

### 6. CSS (assets/css/style.css)

```css
/* Header Icon */
.dmi-weather-icon {
    cursor: pointer;
    width: 32px;
    height: 32px;
    display: inline-block;
}

/* Popup Container */
.dmi-weather-popup {
    position: absolute;
    display: none;
    background: white;
    border: 1px solid #ccc;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 15px;
    z-index: 9999;
}

.dmi-weather-popup.active {
    display: block;
}

/* Forecast Grid */
.weather-forecast {
    display: flex;
    overflow-x: auto;
    gap: 15px;
    max-width: 400px;
}

.weather-hour {
    flex: 0 0 90px;
    text-align: center;
}

/* Responsive scroll */
.weather-forecast::-webkit-scrollbar {
    height: 6px;
}

.weather-forecast::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}
```

## Data Handling

### Caching Strategy

1. Fetch from API on first load
1. Store in WordPress transient for 1 hour
1. Serve from cache for subsequent requests
1. Auto-refresh in background after expiration

### Error Handling

1. Check API response status
1. Validate JSON structure
1. Fallback message if API fails
1. Log errors for debugging

### Wind Chill Logic

- Only display if present in API response
- API includes it when temp ≤10°C and wind >1.3 m/s
- Show in parentheses: “5°C (feels like 2°C)”

## Weather Icon Mapping

Determine icon based on:

1. **Precipitation** > 0.5mm → rain icon
1. **Cloud cover** > 75% → cloudy
1. **Cloud cover** 25-75% → partly cloudy
1. **Cloud cover** < 25% → sun

## WordPress Integration

### Hooks to Use

```php
// Enqueue assets
add_action('wp_enqueue_scripts', 'dmi_weather_enqueue_assets');

// AJAX for logged-in users
add_action('wp_ajax_get_weather', 'dmi_weather_ajax_handler');

// AJAX for non-logged-in users
add_action('wp_ajax_nopriv_get_weather', 'dmi_weather_ajax_handler');

// Add to header (theme-dependent)
add_action('wp_head', 'dmi_weather_add_to_header');
// OR use shortcode: [dmi_weather]
```

### Shortcode Option

```php
add_shortcode('dmi_weather', 'dmi_weather_shortcode');

function dmi_weather_shortcode() {
    // Render weather widget
    // Return HTML
}
```

## Testing Checklist

- [ ] API connection works
- [ ] Data caches correctly for 1 hour
- [ ] Icon displays in header
- [ ] Popup opens/closes on click
- [ ] Shows 4 hours initially
- [ ] Can scroll to see all 12 hours
- [ ] Wind chill displays when applicable
- [ ] SVG icons render correctly
- [ ] Responsive on mobile
- [ ] AJAX refresh works
- [ ] Error handling for API failures

## Deployment

1. Upload plugin folder to `/wp-content/plugins/`
1. Activate in WordPress admin
1. Add to header via theme function or shortcode
1. Test display and functionality
1. Monitor error logs for API issues

## Future Enhancements (Optional)

- Admin settings page for location
- Multiple location support
- Color-coded temperature ranges
- Animation on icon
- Touch swipe for mobile scroll
- Dark mode support
- Translation ready (i18n)

## Notes

- Plugin assumes modern WordPress (5.0+)
- Requires PHP 7.0 or higher
- No external dependencies
- Lightweight and performant
- Privacy-friendly (no user tracking)
