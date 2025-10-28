<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLX WeatherPro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ONLY minimal CSS for colors and branding - NO positioning */
        :root {
            --mlx-dark-blue: #03045e;
            --mlx-blue: #023e8a;
            --mlx-light-blue: #0077b6;
            --mlx-green: #64b450;
            --mlx-very-pale-blue: #ade8f4;
        }

        body {
            background-color: var(--mlx-dark-blue);
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .weather-app {
            background: rgba(14, 20, 200, 0.12);
            border: 2px solid var(--mlx-green);
        }

        .app-header {
            background: linear-gradient(to right, var(--mlx-blue), var(--mlx-light-blue));
            border-bottom: 2px solid var(--mlx-green);
        }

        .language-btn, .search-box button {
            background: var(--mlx-green);
            border: 1px solid var(--mlx-green);
        }

        .language-btn:hover, .search-box button:hover {
            background: #5aa045;
            border-color: #5aa045;
        }

        .search-box {
            border: 1px solid var(--mlx-green);
        }

        .search-box input {
            background: transparent;
            color: #fff;
        }

        .search-box input::placeholder {
            color: rgba(255, 255, 255, 0.8);
        }

        .current-weather {
            background: rgba(11, 207, 57, 0.12);
            border-right: 1px solid var(--mlx-green);
        }

        .forecast-section {
            background: rgba(255, 255, 255, 0.12);
        }

        .detail-item, .hourly-item, .day-item {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid var(--mlx-green);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .section-title {
            border-bottom: 1px solid var(--mlx-green);
        }

        .text-muted-custom {
            color: var(--mlx-very-pale-blue) !important;
        }

        .day-modal .modal-content {
            background: linear-gradient(135deg, var(--mlx-dark-blue), var(--mlx-blue));
            border: 2px solid var(--mlx-green);
        }

        .close-modal {
            background: var(--mlx-green);
            border: none;
        }

        .close-modal:hover {
            background: #5aa045;
        }

        /* Temperature styling */
        .temperature-value {
            font-size: 4rem;
            font-weight: 300;
            line-height: 1;
        }

        .temperature-unit {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 0.1rem;
        }

        /* Scrollbar styling */
        .scroll-container {
            scrollbar-width: thin;
            scrollbar-color: var(--mlx-green) rgba(255, 255, 255, 0.1);
        }

        .scroll-container::-webkit-scrollbar {
            height: 6px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background: var(--mlx-green);
            border-radius: 3px;
        }

        /* Forecast card spacing */
        .forecast-card {
            margin-bottom: 1.5rem;
        }

        /* Language dropdown styling */
        .language-dropdown {
            background: var(--mlx-light-blue) !important;
            border: 1px solid var(--mlx-green) !important;
        }

        .language-option {
            background: var(--mlx-light-blue);
            color: #fff;
            cursor: pointer;
            transition: background 0.2s;
            border-bottom: 1px solid var(--mlx-teal);
        }

        .language-option:hover {
            background: var(--mlx-green) !important;
        }

        .language-option:last-child {
            border-bottom: none !important;
        }

        /* Geolocation permission styles */
        .permission-request {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--mlx-green);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin: 20px;
        }

        .permission-btn {
            background: var(--mlx-green);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        .permission-btn:hover {
            background: #5aa045;
        }

        .permission-btn.secondary {
            background: var(--mlx-light-blue);
        }

        .permission-btn.secondary:hover {
            background: #0066a1;
        }
    </style>
</head>
<body class="min-vh-100 d-flex align-items-center justify-content-center py-3">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-xxl-10">
                <div class="weather-app rounded-4 shadow-lg overflow-hidden">
                    
                    <!-- Header -->
                    <div class="app-header px-3 px-md-4 py-3">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <div class="logo-section mb-2 mb-md-0 d-flex align-items-center">
                                <img src="mlx-logo-wobg.png" alt="MLX Logo" class="img-fluid me-2" style="height: 35px;">
                                <div class="app-title fs-4 fw-bold text-white">MLX WeatherPro</div>
                            </div>
                            <div class="d-flex flex-column flex-sm-row align-items-center gap-2">
                                
                            
                           <div class="language-selector position-relative">
    <button class="language-btn btn text-white rounded-2 px-3 py-2 d-flex align-items-center">
        <i class="fas fa-globe me-2"></i> <span>English</span>
    </button>
    <div class="language-dropdown position-absolute end-0 mt-1 rounded-2 shadow overflow-hidden" style="display: none; min-width: 180px; z-index: 1000; background: var(--mlx-light-blue); border: 1px solid var(--mlx-green);">
        <div class="language-option px-3 py-2 border-bottom text-white" data-lang="en"><i class="fas fa-globe me-2"></i> English</div>
        <div class="language-option px-3 py-2 border-bottom text-white" data-lang="es"><i class="fas fa-globe me-2"></i> Español</div>
        <div class="language-option px-3 py-2 border-bottom text-white" data-lang="fr"><i class="fas fa-globe me-2"></i> Français</div>
        <div class="language-option px-3 py-2 border-bottom text-white" data-lang="de"><i class="fas fa-globe me-2"></i> Deutsch</div>
        <div class="language-option px-3 py-2 border-bottom text-white" data-lang="it"><i class="fas fa-globe me-2"></i> Italiano</div>
        <div class="language-option px-3 py-2 border-bottom text-white" data-lang="pt"><i class="fas fa-globe me-2"></i> Português</div>
        <div class="language-option px-3 py-2 border-bottom text-white" data-lang="ru"><i class="fas fa-globe me-2"></i> Русский</div>
        <div class="language-option px-3 py-2 border-bottom text-white" data-lang="ja"><i class="fas fa-globe me-2"></i> 日本語</div>
        <div class="language-option px-3 py-2 border-bottom text-white" data-lang="zh"><i class="fas fa-globe me-2"></i> 中文</div>
        <div class="language-option px-3 py-2 text-white" data-lang="ar"><i class="fas fa-globe me-2"></i> العربية</div>
    </div>
</div>
                                <div class="search-box rounded-2 d-flex">
                                    <input type="text" class="form-control border-0 shadow-none" placeholder="Search city..." id="city-input">
                                    <button class="btn text-white px-3" id="search-btn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Permission Request -->
                    <div class="permission-request" id="location-permission" style="display: none;">
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt fs-1 text-success mb-3"></i>
                            <h5 id="permission-title">Enable Location Access</h5>
                            <p id="permission-text" class="mb-3">Allow MLX WeatherPro to access your location to show local weather information.</p>
                        </div>
                        <div>
                            <button class="permission-btn" id="allow-location">
                                <i class="fas fa-check me-2"></i> Allow Location
                            </button>
                            <button class="permission-btn secondary" id="deny-location">
                                <i class="fas fa-times me-2"></i> Use Default City
                            </button>
                        </div>
                    </div>

                    <!-- Loading -->
                    <div class="loading text-center py-5" id="loading" style="display: none;">
                        <div class="spinner-border text-success mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-white" id="loading-text">Loading weather data...</p>
                    </div>

                    <!-- Main Content -->
                    <div class="row g-0 min-vh-50">
                        <!-- Current Weather -->
                        <div class="col-lg-6 current-weather">
                            <div class="h-100 d-flex flex-column p-3 p-md-4">
                                <!-- City and Date -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="city-name fs-3 fw-bold" id="city-name">-</div>
                                    <div class="date text-muted-custom small text-end" id="date">-</div>
                                </div>

                                <!-- Temperature and Icon -->
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="d-flex align-items-baseline">
                                        <span class="temperature-value display-4 fw-light me-1" id="temperature">-</span>
                                        <span class="temperature-unit fs-2 fw-semibold align-self-start">°C</span>
                                    </div>
                                    <div class="weather-icon display-1" id="weather-icon">-</div>
                                </div>

                                <!-- Weather Description -->
                                <div class="weather-description text-center fs-5 text-muted-custom fw-semibold mb-4" id="weather-description">-</div>

                                <!-- Stats Grid -->
                                <div class="row g-2 mt-auto">
                                    <div class="col-6">
                                        <div class="detail-item rounded-3 p-3 text-center h-100">
                                            <i class="fas fa-temperature-low fs-4 text-success mb-2"></i>
                                            <div class="detail-value fs-5 fw-bold" id="feels-like">-</div>
                                            <div class="detail-label small text-muted-custom" id="feels-like-label">Feels Like</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="detail-item rounded-3 p-3 text-center h-100">
                                            <i class="fas fa-tint fs-4 text-success mb-2"></i>
                                            <div class="detail-value fs-5 fw-bold" id="humidity">-</div>
                                            <div class="detail-label small text-muted-custom" id="humidity-label">Humidity</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="detail-item rounded-3 p-3 text-center h-100">
                                            <i class="fas fa-wind fs-4 text-success mb-2"></i>
                                            <div class="detail-value fs-5 fw-bold" id="wind-speed">-</div>
                                            <div class="detail-label small text-muted-custom" id="wind-label">Wind Speed</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="detail-item rounded-3 p-3 text-center h-100">
                                            <i class="fas fa-compress-arrows-alt fs-4 text-success mb-2"></i>
                                            <div class="detail-value fs-5 fw-bold" id="pressure">-</div>
                                            <div class="detail-label small text-muted-custom" id="pressure-label">Pressure</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Forecast Section -->
                        <div class="col-lg-6 forecast-section">
                            <div class="h-100 d-flex flex-column p-3 p-md-4">
                                <!-- 24-Hour Forecast -->
                                <div class="forecast-card">
                                    <h5 class="section-title fs-5 fw-bold mb-3 pb-2 d-flex align-items-center">
                                        <i class="fas fa-clock text-success me-2"></i>
                                        <span id="hourly-title">24-Hour Forecast</span>
                                    </h5>
                                    <div class="hourly-container scroll-container d-flex overflow-auto pb-2 gap-3" id="hourly-container">
                                        <!-- Hourly items will be inserted here -->
                                    </div>
                                </div>

                                <!-- 7-Day Forecast -->
                                <div class="forecast-card flex-grow-1">
                                    <h5 class="section-title fs-5 fw-bold mb-3 pb-2 d-flex align-items-center">
                                        <i class="fas fa-calendar-week text-success me-2"></i>
                                        <span id="weekly-title">7-Day Forecast</span>
                                    </h5>
                                    <div class="days-container scroll-container d-flex overflow-auto pb-2 gap-3" id="days-container">
                                        <!-- Day items will be inserted here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div class="error-message text-center text-danger fw-semibold py-3" id="error-message" style="display: none;">
                        City not found. Please try again.
                    </div>

                    <!-- Footer -->
                    <div class="app-footer text-center py-3">
                        <p class="small text-muted-custom mb-0" id="footer-text">
                            Weather data provided by OpenWeatherMap | MLX WeatherPro © <span id="current-year"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Day Detail Modal -->
    <div class="modal fade" id="dayModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content day-modal-content border-0">
                <div class="modal-header border-bottom-0 position-relative">
                    <div class="w-100">
                        <h4 class="modal-title fw-bold" id="modal-day-name">-</h4>
                        <p class="text-muted-custom mb-0" id="modal-day-date">-</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="detail-item rounded-3 p-4 text-center h-100">
                                <div class="modal-weather-icon display-1 mb-3" id="modal-weather-icon">-</div>
                                <div class="modal-temperature fs-2 fw-bold mb-2" id="modal-temperature">-</div>
                                <div class="modal-weather-desc text-muted-custom" id="modal-weather-desc">-</div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row g-3 h-100">
                                <div class="col-6">
                                    <div class="detail-item rounded-3 p-3 h-100">
                                        <i class="fas fa-temperature-low text-success fs-5 me-3"></i>
                                        <div>
                                            <div class="fw-bold fs-6" id="modal-feels-like">-</div>
                                            <small class="text-muted-custom" id="modal-feels-like-label">Feels Like</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="detail-item rounded-3 p-3 h-100">
                                        <i class="fas fa-tint text-success fs-5 me-3"></i>
                                        <div>
                                            <div class="fw-bold fs-6" id="modal-humidity">-</div>
                                            <small class="text-muted-custom" id="modal-humidity-label">Humidity</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="detail-item rounded-3 p-3 h-100">
                                        <i class="fas fa-wind text-success fs-5 me-3"></i>
                                        <div>
                                            <div class="fw-bold fs-6" id="modal-wind-speed">-</div>
                                            <small class="text-muted-custom" id="modal-wind-label">Wind Speed</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="detail-item rounded-3 p-3 h-100">
                                        <i class="fas fa-compress-arrows-alt text-success fs-5 me-3"></i>
                                        <div>
                                            <div class="fw-bold fs-6" id="modal-pressure">-</div>
                                            <small class="text-muted-custom" id="modal-pressure-label">Pressure</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hourly Forecast in Modal -->
                    <h6 class="fw-bold mb-3" id="modal-hourly-title">Hourly Forecast</h6>
                    <div class="modal-hourly-container scroll-container d-flex overflow-auto pb-2 gap-3" id="modal-hourly-container">
                        <!-- Modal hourly items will be inserted here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // DOM Elements
        const cityInput = document.getElementById('city-input');
        const searchBtn = document.getElementById('search-btn');
        const cityName = document.getElementById('city-name');
        const dateElement = document.getElementById('date');
        const temperature = document.getElementById('temperature');
        const weatherDescription = document.getElementById('weather-description');
        const weatherIcon = document.getElementById('weather-icon');
        const humidity = document.getElementById('humidity');
        const windSpeed = document.getElementById('wind-speed');
        const feelsLike = document.getElementById('feels-like');
        const pressure = document.getElementById('pressure');
        const errorMessage = document.getElementById('error-message');
        const loading = document.getElementById('loading');
        const loadingText = document.getElementById('loading-text');
        const hourlyContainer = document.getElementById('hourly-container');
        const daysContainer = document.getElementById('days-container');
        const languageBtn = document.querySelector('.language-btn');
        const languageDropdown = document.querySelector('.language-dropdown');
        const languageOptions = document.querySelectorAll('.language-option');
        const hourlyTitle = document.getElementById('hourly-title');
        const weeklyTitle = document.getElementById('weekly-title');
        const footerText = document.getElementById('footer-text');
        const feelsLikeLabel = document.getElementById('feels-like-label');
        const humidityLabel = document.getElementById('humidity-label');
        const windLabel = document.getElementById('wind-label');
        const pressureLabel = document.getElementById('pressure-label');
        const currentYear = document.getElementById('current-year');
        const locationPermission = document.getElementById('location-permission');
        const allowLocationBtn = document.getElementById('allow-location');
        const denyLocationBtn = document.getElementById('deny-location');
        const permissionTitle = document.getElementById('permission-title');
        const permissionText = document.getElementById('permission-text');

        // Modal elements
        const dayModal = new bootstrap.Modal(document.getElementById('dayModal'));
        const modalDayName = document.getElementById('modal-day-name');
        const modalDayDate = document.getElementById('modal-day-date');
        const modalWeatherIcon = document.getElementById('modal-weather-icon');
        const modalTemperature = document.getElementById('modal-temperature');
        const modalWeatherDesc = document.getElementById('modal-weather-desc');
        const modalFeelsLike = document.getElementById('modal-feels-like');
        const modalHumidity = document.getElementById('modal-humidity');
        const modalWindSpeed = document.getElementById('modal-wind-speed');
        const modalPressure = document.getElementById('modal-pressure');
        const modalHourlyContainer = document.getElementById('modal-hourly-container');
        const modalHourlyTitle = document.getElementById('modal-hourly-title');
        const modalFeelsLikeLabel = document.getElementById('modal-feels-like-label');
        const modalHumidityLabel = document.getElementById('modal-humidity-label');
        const modalWindLabel = document.getElementById('modal-wind-label');
        const modalPressureLabel = document.getElementById('modal-pressure-label');

        // API Key
        const API_KEY = 'ea6223158d175893346d5a8058f7515e';

        // Set current year in footer
        currentYear.textContent = new Date().getFullYear();

        // Language translations
        const translations = {
            en: {
                searchPlaceholder: "Search city...",
                loading: "Loading weather data...",
                hourlyTitle: "24-Hour Forecast",
                weeklyTitle: "7-Day Forecast",
                feelsLike: "Feels Like",
                humidity: "Humidity",
                windSpeed: "Wind Speed",
                pressure: "Pressure",
                footer: "Weather data provided by OpenWeatherMap | MLX WeatherPro © " + new Date().getFullYear(),
                error: "City not found. Please try again.",
                today: "Today",
                modalHourlyTitle: "Hourly Forecast",
                permissionTitle: "Enable Location Access",
                permissionText: "Allow MLX WeatherPro to access your location to show local weather information.",
                allowLocation: "Allow Location",
                denyLocation: "Use Default City",
                detectingLocation: "Detecting your location...",
                locationError: "Unable to detect your location. Using default city."
            },
            es: {
                searchPlaceholder: "Buscar ciudad...",
                loading: "Cargando datos del clima...",
                hourlyTitle: "Pronóstico 24 Horas",
                weeklyTitle: "Pronóstico 7 Días",
                feelsLike: "Sensación Térmica",
                humidity: "Humedad",
                windSpeed: "Velocidad del Viento",
                pressure: "Presión",
                footer: "Datos climáticos proporcionados por OpenWeatherMap | MLX WeatherPro © " + new Date().getFullYear(),
                error: "Ciudad no encontrada. Por favor, intente de nuevo.",
                today: "Hoy",
                modalHourlyTitle: "Pronóstico Horario",
                permissionTitle: "Habilitar Acceso a Ubicación",
                permissionText: "Permita que MLX WeatherPro acceda a su ubicación para mostrar información meteorológica local.",
                allowLocation: "Permitir Ubicación",
                denyLocation: "Usar Ciudad Predeterminada",
                detectingLocation: "Detectando su ubicación...",
                locationError: "No se pudo detectar su ubicación. Usando ciudad predeterminada."
            },
            // ... other languages with the same structure
        };

        // Current language
        let currentLang = 'en';

        // Custom weather icons mapping
        const weatherIcons = {
            '01d': '☀️', // clear sky (day)
            '01n': '🌙', // clear sky (night)
            '02d': '⛅', // few clouds (day)
            '02n': '☁️', // few clouds (night)
            '03d': '☁️', // scattered clouds
            '03n': '☁️',
            '04d': '🌥️', // broken clouds
            '04n': '🌥️',
            '09d': '🌧️', // shower rain
            '09n': '🌧️',
            '10d': '🌦️', // rain (day)
            '10n': '🌧️', // rain (night)
            '11d': '⛈️', // thunderstorm
            '11n': '⛈️',
            '13d': '❄️', // snow
            '13n': '❄️',
            '50d': '🌫️', // mist
            '50n': '🌫️'
        };

        // Track if we should clear input on focus
        let shouldClearInput = true;

        // Set current date
        function setCurrentDate() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateElement.textContent = now.toLocaleDateString(currentLang, options);
        }

        // Update UI language
        function updateLanguage(lang) {
            currentLang = lang;
            const t = translations[lang];
            
            // Update UI elements
            cityInput.placeholder = t.searchPlaceholder;
            loadingText.textContent = t.loading;
            hourlyTitle.textContent = t.hourlyTitle;
            weeklyTitle.textContent = t.weeklyTitle;
            footerText.textContent = t.footer;
            errorMessage.textContent = t.error;
            feelsLikeLabel.textContent = t.feelsLike;
            humidityLabel.textContent = t.humidity;
            windLabel.textContent = t.windSpeed;
            pressureLabel.textContent = t.pressure;
            modalHourlyTitle.textContent = t.modalHourlyTitle;
            modalFeelsLikeLabel.textContent = t.feelsLike;
            modalHumidityLabel.textContent = t.humidity;
            modalWindLabel.textContent = t.windSpeed;
            modalPressureLabel.textContent = t.pressure;
            permissionTitle.textContent = t.permissionTitle;
            permissionText.textContent = t.permissionText;
            allowLocationBtn.innerHTML = `<i class="fas fa-check me-2"></i> ${t.allowLocation}`;
            denyLocationBtn.innerHTML = `<i class="fas fa-times me-2"></i> ${t.denyLocation}`;
            
            // Update language button
            const languageName = getLanguageName(lang);
            languageBtn.innerHTML = `<i class="fas fa-globe me-2"></i> <span>${languageName}</span>`;
            
            // Update date
            setCurrentDate();
            
            // Refresh weather data to update day names
            if (cityInput.value.trim() && cityInput.value !== 'New York') {
                handleSearch();
            }
        }

        function getLanguageName(code) {
            const names = {
                en: "English",
                es: "Español", 
                fr: "Français",
                de: "Deutsch",
                it: "Italiano",
                pt: "Português",
                ru: "Русский",
                ja: "日本語",
                zh: "中文",
                ar: "العربية"
            };
            return names[code] || "English";
        }

        // Get user's location
        function getUserLocation() {
            return new Promise((resolve, reject) => {
                if (!navigator.geolocation) {
                    reject(new Error('Geolocation is not supported by this browser.'));
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        resolve({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        });
                    },
                    (error) => {
                        reject(error);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 60000
                    }
                );
            });
        }

        // Fetch weather data by city name
        async function fetchWeatherData(city) {
            try {
                loading.style.display = 'block';
                errorMessage.style.display = 'none';

                const currentResponse = await fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${API_KEY}`);
                if (!currentResponse.ok) throw new Error('City not found');
                const currentData = await currentResponse.json();

                const forecastResponse = await fetch(`https://api.openweathermap.org/data/2.5/forecast?q=${city}&units=metric&appid=${API_KEY}`);
                if (!forecastResponse.ok) throw new Error('Forecast data not available');
                const forecastData = await forecastResponse.json();

                return { current: currentData, forecast: forecastData };
            } catch (error) {
                console.error('Error fetching weather data:', error);
                throw error;
            }
        }

        // Fetch weather data by coordinates
        async function fetchWeatherByCoords(lat, lon) {
            try {
                loading.style.display = 'block';
                errorMessage.style.display = 'none';
                loadingText.textContent = translations[currentLang].detectingLocation;

                const currentResponse = await fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&appid=${API_KEY}`);
                if (!currentResponse.ok) throw new Error('Location not found');
                const currentData = await currentResponse.json();

                const forecastResponse = await fetch(`https://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&units=metric&appid=${API_KEY}`);
                if (!forecastResponse.ok) throw new Error('Forecast data not available');
                const forecastData = await forecastResponse.json();

                return { current: currentData, forecast: forecastData };
            } catch (error) {
                console.error('Error fetching weather data by coordinates:', error);
                throw error;
            }
        }

        // Display current weather data
        function displayCurrentWeather(data, forecastData) {
            cityName.textContent = `${data.name}, ${data.sys.country}`;
            temperature.textContent = Math.round(data.main.temp);
            weatherDescription.textContent = data.weather[0].description;
            humidity.textContent = `${data.main.humidity}%`;
            windSpeed.textContent = `${data.wind.speed} m/s`;
            feelsLike.textContent = `${Math.round(data.main.feels_like)}°C`;
            pressure.textContent = `${data.main.pressure} hPa`;
            
            // Update search input with current city and set flag to clear on next focus
            cityInput.value = data.name;
            shouldClearInput = true;
            
            // Set weather icon using emoji
            const iconCode = data.weather[0].icon;
            weatherIcon.textContent = weatherIcons[iconCode] || '🌤️';
        }

        // Display hourly forecast
        function displayHourlyForecast(forecastData) {
            hourlyContainer.innerHTML = '';
            const hourlyData = forecastData.list.slice(0, 8);
            
            hourlyData.forEach(item => {
                const time = new Date(item.dt * 1000);
                const hour = time.getHours();
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const displayHour = hour % 12 || 12;
                const iconCode = item.weather[0].icon;
                
                const hourlyItem = document.createElement('div');
                hourlyItem.className = 'hourly-item rounded-3 p-3 text-center flex-shrink-0';
                hourlyItem.style.minWidth = '80px';
                hourlyItem.innerHTML = `
                    <div class="small text-muted-custom fw-semibold mb-2">${displayHour} ${ampm}</div>
                    <div class="fs-5 mb-2">${weatherIcons[iconCode] || '🌤️'}</div>
                    <div class="fw-bold">${Math.round(item.main.temp)}°</div>
                `;
                hourlyContainer.appendChild(hourlyItem);
            });
        }

        // Display weekly forecast
        function displayWeeklyForecast(forecastData) {
            daysContainer.innerHTML = '';
            const dailyForecasts = {};
            
            forecastData.list.forEach(item => {
                const date = new Date(item.dt * 1000);
                const dayKey = date.toDateString();
                if (!dailyForecasts[dayKey]) dailyForecasts[dayKey] = [];
                dailyForecasts[dayKey].push(item);
            });
            
            const dayKeys = Object.keys(dailyForecasts).slice(0, 7);
            
            dayKeys.forEach((dayKey, index) => {
                const dayData = dailyForecasts[dayKey];
                const date = new Date(dayKey);
                const dayName = index === 0 ? translations[currentLang].today : date.toLocaleDateString(currentLang, { weekday: 'short' });
                const dayDate = date.toLocaleDateString(currentLang, { month: 'short', day: 'numeric' });
                
                const temps = dayData.map(item => item.main.temp);
                const minTemp = Math.round(Math.min(...temps));
                const maxTemp = Math.round(Math.max(...temps));
                
                const weatherCounts = {};
                dayData.forEach(item => {
                    const condition = item.weather[0].main;
                    weatherCounts[condition] = (weatherCounts[condition] || 0) + 1;
                });
                const dominantWeather = Object.keys(weatherCounts).reduce((a, b) => 
                    weatherCounts[a] > weatherCounts[b] ? a : b
                );
                
                const dominantItem = dayData.find(item => item.weather[0].main === dominantWeather);
                const iconCode = dominantItem.weather[0].icon;
                
                const dayItem = document.createElement('div');
                dayItem.className = 'day-item rounded-3 p-3 text-center flex-shrink-0';
                dayItem.style.minWidth = '120px';
                dayItem.innerHTML = `
                    <div class="fw-bold mb-1">${dayName}</div>
                    <div class="small text-muted-custom mb-2">${dayDate}</div>
                    <div class="fs-4 mb-2">${weatherIcons[iconCode] || '🌤️'}</div>
                    <div class="fw-bold mb-2">${maxTemp}° / ${minTemp}°</div>
                    <div class="small text-muted-custom">
                        <div>H: ${Math.round(dayData.reduce((sum, item) => sum + item.main.humidity, 0) / dayData.length)}%</div>
                        <div>W: ${(dayData.reduce((sum, item) => sum + item.wind.speed, 0) / dayData.length).toFixed(1)} m/s</div>
                    </div>
                `;
                
                dayItem.addEventListener('click', () => {
                    openDayModal(dayData, dayName, dayDate, iconCode, maxTemp, minTemp);
                });
                
                daysContainer.appendChild(dayItem);
            });
        }

        function openDayModal(dayData, dayName, dayDate, iconCode, maxTemp, minTemp) {
            modalDayName.textContent = dayName;
            modalDayDate.textContent = dayDate;
            modalWeatherIcon.textContent = weatherIcons[iconCode] || '🌤️';
            modalTemperature.textContent = `${maxTemp}° / ${minTemp}°`;
            modalWeatherDesc.textContent = dayData[0].weather[0].description;
            modalFeelsLike.textContent = `${Math.round(dayData[0].main.feels_like)}°C`;
            modalHumidity.textContent = `${Math.round(dayData.reduce((sum, item) => sum + item.main.humidity, 0) / dayData.length)}%`;
            modalWindSpeed.textContent = `${(dayData.reduce((sum, item) => sum + item.wind.speed, 0) / dayData.length).toFixed(1)} m/s`;
            modalPressure.textContent = `${dayData[0].main.pressure} hPa`;
            
            modalHourlyContainer.innerHTML = '';
            dayData.forEach(item => {
                const time = new Date(item.dt * 1000);
                const hour = time.getHours();
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const displayHour = hour % 12 || 12;
                const itemIconCode = item.weather[0].icon;
                
                const modalHourlyItem = document.createElement('div');
                modalHourlyItem.className = 'hourly-item rounded-3 p-3 text-center flex-shrink-0';
                modalHourlyItem.style.minWidth = '80px';
                modalHourlyItem.innerHTML = `
                    <div class="small text-muted-custom fw-semibold mb-2">${displayHour} ${ampm}</div>
                    <div class="fs-5 mb-2">${weatherIcons[itemIconCode] || '🌤️'}</div>
                    <div class="fw-bold">${Math.round(item.main.temp)}°</div>
                `;
                modalHourlyContainer.appendChild(modalHourlyItem);
            });
            
            dayModal.show();
        }

        async function handleSearch() {
            const city = cityInput.value.trim();
            if (!city) {
                errorMessage.textContent = translations[currentLang].error;
                errorMessage.style.display = 'block';
                loading.style.display = 'none';
                return;
            }

            try {
                const weatherData = await fetchWeatherData(city);
                displayCurrentWeather(weatherData.current, weatherData.forecast);
                displayHourlyForecast(weatherData.forecast);
                displayWeeklyForecast(weatherData.forecast);
                loading.style.display = 'none';
            } catch (error) {
                loading.style.display = 'none';
                errorMessage.textContent = translations[currentLang].error;
                errorMessage.style.display = 'block';
            }
        }

        // Initialize with user's location
        async function initializeApp() {
            setCurrentDate();
            
            // Check if we should ask for location permission
            const locationPermissionAsked = localStorage.getItem('locationPermissionAsked');
            
            if (!locationPermissionAsked) {
                // Show location permission request
                locationPermission.style.display = 'block';
            } else {
                // Try to get user location if previously allowed
                try {
                    const position = await getUserLocation();
                    const weatherData = await fetchWeatherByCoords(position.latitude, position.longitude);
                    displayCurrentWeather(weatherData.current, weatherData.forecast);
                    displayHourlyForecast(weatherData.forecast);
                    displayWeeklyForecast(weatherData.forecast);
                    loading.style.display = 'none';
                } catch (error) {
                    console.log('Location access denied or failed, using default city');
                    // Use default city
                    cityInput.value = 'New York';
                    shouldClearInput = true;
                    handleSearch();
                }
            }
        }

        // Event listeners
        searchBtn.addEventListener('click', handleSearch);
        cityInput.addEventListener('keyup', (event) => {
            if (event.key === 'Enter') handleSearch();
        });

        // FIXED: Clear input on focus when appropriate
        cityInput.addEventListener('focus', function() {
            if (shouldClearInput) {
                this.value = '';
                shouldClearInput = false;
            }
        });

        // Reset clear flag when user starts typing
        cityInput.addEventListener('input', function() {
            shouldClearInput = false;
        });

        // Location permission handlers
        allowLocationBtn.addEventListener('click', async () => {
            locationPermission.style.display = 'none';
            localStorage.setItem('locationPermissionAsked', 'true');
            
            try {
                const position = await getUserLocation();
                const weatherData = await fetchWeatherByCoords(position.latitude, position.longitude);
                displayCurrentWeather(weatherData.current, weatherData.forecast);
                displayHourlyForecast(weatherData.forecast);
                displayWeeklyForecast(weatherData.forecast);
                loading.style.display = 'none';
            } catch (error) {
                console.error('Error getting location:', error);
                errorMessage.textContent = translations[currentLang].locationError;
                errorMessage.style.display = 'block';
                // Fallback to default city
                cityInput.value = 'New York';
                shouldClearInput = true;
                handleSearch();
            }
        });

        denyLocationBtn.addEventListener('click', () => {
            locationPermission.style.display = 'none';
            localStorage.setItem('locationPermissionAsked', 'true');
            // Use default city
            cityInput.value = 'New York';
            shouldClearInput = true;
            handleSearch();
        });

        languageBtn.addEventListener('click', () => {
            languageDropdown.style.display = languageDropdown.style.display === 'none' ? 'block' : 'none';
        });

        languageOptions.forEach(option => {
            option.addEventListener('click', () => {
                const lang = option.getAttribute('data-lang');
                updateLanguage(lang);
                languageDropdown.style.display = 'none';
            });
        });

        document.addEventListener('click', (event) => {
            if (!languageBtn.contains(event.target) && !languageDropdown.contains(event.target)) {
                languageDropdown.style.display = 'none';
            }
        });

        // Initialize the app
        window.addEventListener('load', initializeApp);
    </script>
</body>
</html>