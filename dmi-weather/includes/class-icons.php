<?php
/**
 * DMI Weather Icons
 * 
 * Provides SVG weather icons
 */

if (!defined('ABSPATH')) exit;

class DMI_Weather_Icons {
    
    /**
     * Get weather icon SVG
     * 
     * @param string $icon_type Icon identifier (sun, cloudy, partly-cloudy, rain)
     * @param int $size Icon size in pixels (default: 32)
     * @return string SVG markup
     */
    public static function get_icon($icon_type, $size = 32) {
        $icon_method = 'svg_' . str_replace('-', '_', $icon_type);
        
        if (method_exists(__CLASS__, $icon_method)) {
            return self::$icon_method($size);
        }
        
        // Default to sun icon if type not found
        return self::svg_sun($size);
    }
    
    /**
     * Sun icon
     */
    private static function svg_sun($size = 32) {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5"></circle>
            <line x1="12" y1="1" x2="12" y2="3"></line>
            <line x1="12" y1="21" x2="12" y2="23"></line>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
            <line x1="1" y1="12" x2="3" y2="12"></line>
            <line x1="21" y1="12" x2="23" y2="12"></line>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
        </svg>';
    }
    
    /**
     * Cloudy icon
     */
    private static function svg_cloudy($size = 32) {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path>
        </svg>';
    }
    
    /**
     * Partly cloudy icon (cloud with sun)
     */
    private static function svg_partly_cloudy($size = 32) {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M13 2v2"></path>
            <path d="M21 12h2"></path>
            <path d="M18.5 5.5l1.5-1.5"></path>
            <path d="M18.5 18.5l1.5 1.5"></path>
            <path d="M6.5 18.5l-1.5 1.5"></path>
            <path d="M6.5 5.5l-1.5-1.5"></path>
            <path d="M13 22v-2"></path>
            <circle cx="13" cy="12" r="4"></circle>
            <path d="M22 17.5A5 5 0 0 0 18 8h-1.26A8 8 0 1 0 4 16.5"></path>
        </svg>';
    }
    
    /**
     * Rain icon
     */
    private static function svg_rain($size = 32) {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="16" y1="13" x2="16" y2="21"></line>
            <line x1="8" y1="13" x2="8" y2="21"></line>
            <line x1="12" y1="15" x2="12" y2="23"></line>
            <path d="M20 16.58A5 5 0 0 0 18 7h-1.26A8 8 0 1 0 4 15.25"></path>
        </svg>';
    }
    
    /**
     * Get icon for header (small, simplified)
     */
    public static function get_header_icon($size = 32) {
        // Use partly cloudy as default header icon
        return self::svg_partly_cloudy($size);
    }
}
