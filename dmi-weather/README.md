# DMI Weather Dianalund - WordPress Plugin

A WordPress plugin that displays local weather forecast for Dianalund, Denmark using DMI's proxy API.

## Features

- 🌤️ Real-time weather data from DMI (Danish Meteorological Institute)
- 📊 12-hour forecast display
- 💨 Wind speed and direction
- 🌧️ Precipitation information
- ❄️ Wind chill (when applicable)
- 🔄 Auto-refresh every hour
- 💾 Smart caching using WordPress Transients
- 📱 Responsive design
- 🎨 Clean, minimal UI with SVG icons

## Installation

### Manual Installation

1. Download the plugin files or clone this repository
2. Upload the `dmi-weather` folder to `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Add the weather widget to your site using one of the methods below

### Using the Plugin

#### Method 1: Shortcode

Add the weather widget anywhere in your content using the shortcode:

```
[dmi_weather]
```

This can be placed in:
- Posts
- Pages
- Text widgets
- Template files using `<?php echo do_shortcode('[dmi_weather]'); ?>`

#### Method 2: Direct Template Integration

Add to your theme's header.php or any template file:

```php
<?php
if (function_exists('dmi_weather_shortcode')) {
    echo dmi_weather_shortcode();
}
?>
```

## How It Works

1. **Click the weather icon** in the header to open the forecast popup
2. **View 4 hours at a time** - scroll horizontally to see all 12 hours
3. **Data updates automatically** every hour
4. **Cached for performance** - reduces API calls

## Weather Data Display

For each hour, the plugin shows:
- Time (HH:MM format)
- Weather icon (sun, partly cloudy, cloudy, rain)
- Temperature in °C
- Wind chill (only when temp ≤10°C and wind >1.3 m/s)
- Wind speed in m/s
- Precipitation in mm

## Technical Details

### API Endpoint

Uses DMI's public proxy API:
```
https://dmi.cma.dk/api/weather/forecast/dianalund?hours=12
```

### Caching

- Weather data is cached for 1 hour using WordPress Transients
- Automatic cache refresh on expiration
- Reduces server load and API requests

### Weather Icons

Icons are determined by:
- **Rain**: Precipitation > 0.5mm
- **Cloudy**: Cloud cover > 75%
- **Partly Cloudy**: Cloud cover 25-75%
- **Sun**: Cloud cover < 25%

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- Active internet connection for API access

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile responsive
- Touch-friendly interface

## Customization

### Styling

The plugin styles can be customized by overriding the CSS in your theme:

```css
.dmi-weather-icon {
    /* Customize header icon */
}

.dmi-weather-popup {
    /* Customize popup appearance */
}
```

### Location

To change the location, modify the API URL in `includes/class-api.php`:

```php
private $api_url = 'https://dmi.cma.dk/api/weather/forecast/YOUR_CITY';
```

Supported location formats:
- City name: `dianalund`
- Postal code: `4293`
- Coordinates: `lat=55.5239&lon=11.4739`

## File Structure

```
dmi-weather/
├── dmi-weather.php          # Main plugin file
├── includes/
│   ├── class-api.php        # API calls and caching
│   ├── class-icons.php      # SVG weather icons
│   └── class-widget.php     # Widget display
├── assets/
│   ├── css/
│   │   └── style.css        # Plugin styles
│   └── js/
│       └── script.js        # AJAX and interactions
└── README.md                # This file
```

## Troubleshooting

### Weather data not loading

1. Check if the API is accessible: Visit https://dmi.cma.dk/api/weather/forecast/dianalund?hours=12
2. Check WordPress error logs for API connection issues
3. Clear the cache by deactivating and reactivating the plugin

### Popup not appearing

1. Check browser console for JavaScript errors
2. Ensure no conflicts with other plugins
3. Verify jQuery is loaded (though not required by this plugin)

### Styling issues

1. Check for CSS conflicts with your theme
2. Increase CSS specificity if needed
3. Clear browser cache

## Privacy

This plugin:
- Does not collect user data
- Does not use cookies
- Does not track users
- Only fetches public weather data from DMI

## Support

For issues, questions, or contributions:
- Check the documentation above
- Review the code comments
- Contact the plugin author

## License

This plugin is licensed under the GPL v2 or later.

## Credits

- Weather data provided by DMI (Danish Meteorological Institute)
- Icons based on Feather Icons design system
- Developed for local weather display in Dianalund, Denmark

## Changelog

### Version 1.0.0
- Initial release
- 12-hour weather forecast
- Auto-refresh functionality
- Responsive design
- WordPress Transient caching
- SVG weather icons
