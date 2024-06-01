<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$apiKey = $_ENV['OPENWEATHER_API_KEY'];
$cityId = "2755251"; # Groningen
$googleApiUrl = "https://api.openweathermap.org/data/2.5/weather?id=" . $cityId . "&lang=en&units=metric&APPID=" . $apiKey;

$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);

curl_close($ch);
$data = json_decode($response);
$currentTime = time();

$agent = $_SERVER["HTTP_USER_AGENT"];

if (strpos($agent, 'curl') !== false) {
    echo $data->name . "\n";
    echo date("l jS F, Y g:i a", $currentTime) . "\n";
    echo ucwords($data->weather[0]->description) . "\n";
    echo 'High: ' . $data->main->temp_max . '°C, Low: ' . $data->main->temp_min . '°C' . "\n";
    echo 'Humidity: ' . $data->main->humidity . '%' . "\n";
    echo 'Wind: ' . $data->wind->speed . ' km/h' . "\n";
    return;
}
?>
<!doctype html>
<html>
<head>
<title>Forecast Weather using OpenWeatherMap with PHP</title>
</head>
<body>
    <div class="report-container">
        <h2><?php echo $data->name; ?> Weather Status</h2>
        <div class="time">
            <div><?php echo date("jS F, Y l g:i a", $currentTime); ?></div>
            <div><?php echo ucwords($data->weather[0]->description); ?></div>
        </div>
        <div class="weather-forecast">
            <img
                src="https://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png"
                class="weather-icon" />
        <div class="temperature">
            <span class="max-temperature"><?php echo 'High: ' . $data->main->temp_max; ?>°C</span><br />
            <span class="min-temperature"><?php echo 'Low: ' . $data->main->temp_min; ?>°C</span>
        </div>
        <div class="time">
            <div>Humidity: <?php echo $data->main->humidity; ?> %</div>
            <div>Wind: <?php echo $data->wind->speed; ?> km/h</div>
        </div>
    </div>
</body>
</html>
