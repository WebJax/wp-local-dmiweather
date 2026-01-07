/**
 * DMI Weather Plugin - JavaScript
 * 
 * Handles popup interaction and AJAX data fetching
 */

document.addEventListener('DOMContentLoaded', function() {
    
    const weatherIcon = document.getElementById('dmi-weather-icon');
    const weatherPopup = document.getElementById('dmi-weather-popup');
    const weatherClose = document.getElementById('dmi-weather-close');
    const weatherForecast = document.getElementById('dmi-weather-forecast');
    
    if (!weatherIcon || !weatherPopup) {
        return; // Widget not present on this page
    }
    
    let isPopupOpen = false;
    let weatherData = null;
    
    /**
     * Toggle popup visibility
     */
    function togglePopup(show) {
        if (show) {
            weatherPopup.classList.add('active');
            isPopupOpen = true;
            
            // Fetch weather data if not already loaded
            if (!weatherData) {
                fetchWeather();
            }
        } else {
            weatherPopup.classList.remove('active');
            isPopupOpen = false;
        }
    }
    
    /**
     * Fetch weather data via AJAX
     */
    function fetchWeather() {
        weatherForecast.innerHTML = '<div class="weather-loading">Loading weather data...</div>';
        
        const formData = new FormData();
        formData.append('action', 'get_weather');
        formData.append('nonce', dmiWeather.nonce);
        
        fetch(dmiWeather.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                weatherData = data.data;
                renderForecast(weatherData);
            } else {
                weatherForecast.innerHTML = '<div class="weather-error">Failed to load weather data. Please try again later.</div>';
            }
        })
        .catch(error => {
            console.error('Weather fetch error:', error);
            weatherForecast.innerHTML = '<div class="weather-error">Failed to load weather data. Please try again later.</div>';
        });
    }
    
    /**
     * Render forecast data
     */
    function renderForecast(data) {
        if (!data.forecast || data.forecast.length === 0) {
            weatherForecast.innerHTML = '<div class="weather-error">No forecast data available.</div>';
            return;
        }
        
        let html = '';
        
        // Render all hours (show 4 initially, scroll for more)
        data.forecast.forEach(hour => {
            html += `
                <div class="weather-hour">
                    <div class="weather-time">${escapeHtml(hour.time)}</div>
                    <div class="weather-icon">
                        ${getIconSvg(hour.icon)}
                    </div>
                    <div class="weather-temp">
                        ${escapeHtml(hour.temperature)}°C
                        ${hour.wind_chill ? '<br><span class="feels-like">(feels like ' + escapeHtml(hour.wind_chill) + '°C)</span>' : ''}
                    </div>
                    <div class="weather-wind">
                        <span class="weather-label">Wind:</span> ${escapeHtml(hour.wind_speed)} m/s
                    </div>
                    <div class="weather-precipitation">
                        <span class="weather-label">Rain:</span> ${escapeHtml(hour.precipitation)} mm
                    </div>
                </div>
            `;
        });
        
        weatherForecast.innerHTML = html;
    }
    
    /**
     * Get SVG icon markup
     */
    function getIconSvg(iconType) {
        const icons = {
            'sun': '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>',
            'cloudy': '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path></svg>',
            'partly-cloudy': '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2v2"></path><path d="M21 12h2"></path><path d="M18.5 5.5l1.5-1.5"></path><path d="M18.5 18.5l1.5 1.5"></path><path d="M6.5 18.5l-1.5 1.5"></path><path d="M6.5 5.5l-1.5-1.5"></path><path d="M13 22v-2"></path><circle cx="13" cy="12" r="4"></circle><path d="M8.5 17.5a5 5 0 0 0 0-10 7 7 0 0 0 0 10z"></path></svg>',
            'rain': '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="16" y1="13" x2="16" y2="21"></line><line x1="8" y1="13" x2="8" y2="21"></line><line x1="12" y1="15" x2="12" y2="23"></line><path d="M20 16.58A5 5 0 0 0 18 7h-1.26A8 8 0 1 0 4 15.25"></path></svg>'
        };
        
        return icons[iconType] || icons['sun'];
    }
    
    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }
    
    // Event listeners
    weatherIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        togglePopup(true);
    });
    
    if (weatherClose) {
        weatherClose.addEventListener('click', function(e) {
            e.stopPropagation();
            togglePopup(false);
        });
    }
    
    // Close popup when clicking outside
    document.addEventListener('click', function(e) {
        if (isPopupOpen && !weatherPopup.contains(e.target) && e.target !== weatherIcon) {
            togglePopup(false);
        }
    });
    
    // Prevent popup from closing when clicking inside it
    weatherPopup.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Auto-refresh weather data every hour
    setInterval(function() {
        if (weatherData) {
            fetchWeather();
        }
    }, 3600000); // 1 hour in milliseconds
});
