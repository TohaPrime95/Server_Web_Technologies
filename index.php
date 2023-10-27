<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

// Ваш API ключ OpenWeatherMap
$apiKey = 'cf6aa194fcbc2f456eabad63e73d4c10';

// Масив з містами та їх URL API
$cities = [
    'Bucha' => "https://api.openweathermap.org/data/2.5/weather?q=Bucha&appid=$apiKey",
    'Kyiv' => "https://api.openweathermap.org/data/2.5/weather?q=Kyiv&appid=$apiKey",
    'Chernihiv' => "https://api.openweathermap.org/data/2.5/weather?q=Chernihiv&appid=$apiKey",
    'NYC' => "https://api.openweathermap.org/data/2.5/weather?q=New York&appid=$apiKey",
    'Canberra' => "https://api.openweathermap.org/data/2.5/weather?q=Canberra&appid=$apiKey",
    'Tokyo' => "https://api.openweathermap.org/data/2.5/weather?q=Tokyo&appid=$apiKey",
];

// Ініціалізуємо клієнта Guzzle
$client = new Client();

try {
    echo '<html><head><title>Погода в різних містах</title>';
    echo '<style>';
    echo 'body {font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; background-color: #ccc; color: #000;}';
    echo '.container {margin: 20px; padding: 20px; background-color: #fff; border: 1px solid #ccc; border-radius: 5px; margin: 20px; float: left; width: 25%;}';
    echo 'h1 {font-size: 24px; text-align: center;}';
    echo 'p {font-size: 18px;}';
    echo 'img {width: 128px; height: 200px;}';
    echo '</style>';
    echo '</head><body>';

    foreach ($cities as $cityName => $apiUrl) {
        // Виконуємо GET-запит до API та отримуємо відповідь
        $response = $client->get($apiUrl);

        // Отримуємо тіло відповіді у вигляді рядка JSON
        $data = $response->getBody()->getContents();

        // Парсимо JSON у масив
        $jsonData = json_decode($data, true);

        // Перетворюємо температуру з Кельвіна в Цельсія
        $tempCelsius = $jsonData['main']['temp'] - 273.15;

        // Визначаємо, яку картинку виводити залежно від опису погоди
        $weatherDescription = $jsonData['weather'][0]['description'];
        $imageSrc = 'https://cdn.leonardo.ai/users/b9cd7e90-6214-43ba-89ad-7e7d72e797bf/generations/6060f22a-4f58-420d-aa74-1060f21a7884/DreamShaper_v7_just_a_normal_weather_picture_0.jpg'; // Зображення за замовчуванням

        // Встановлюємо картинку в залежності від опису погоди
        if (stripos($weatherDescription, 'sun') !== false) {
            $imageSrc = 'https://cdn.leonardo.ai/users/b9cd7e90-6214-43ba-89ad-7e7d72e797bf/generations/abab7ac8-ceff-4480-b33c-742231e80431/DreamShaper_v7_sun_0.jpg';
        } elseif (stripos($weatherDescription, 'clouds') !== false) {
            $imageSrc = 'https://cdn.leonardo.ai/users/b9cd7e90-6214-43ba-89ad-7e7d72e797bf/generations/1c67bb46-0974-4978-ac25-e48feeb37754/DreamShaper_v7_overcast_clouds_0.jpg';
        } elseif (stripos($weatherDescription, 'rain') !== false) {
            $imageSrc = 'https://media.istockphoto.com/id/1049365996/ru/фото/дождь-падает-на-землю.jpg?s=2048x2048&w=is&k=20&c=zdH4OSrwIetJ3rotMHNpYKzhB04IVIlHYXv8Lo4eD0U=';
        } elseif (stripos($weatherDescription, 'snow') !== false) {
            $imageSrc = 'https://media.istockphoto.com/id/1181599019/ru/фото/пустой-панорамный-зимний-фон.jpg?s=2048x2048&w=is&k=20&c=lpXepSmfBIODAhbKXICLfrdqblyWdSmZeV8QIb83_Uw=';
        } elseif (stripos($weatherDescription, 'clear sky') !== false) {
            $imageSrc = 'https://cdn.leonardo.ai/users/b9cd7e90-6214-43ba-89ad-7e7d72e797bf/generations/9d2df0ec-7092-4e93-a482-ca87186888d7/DreamShaper_v7_clear_sky_2.jpg';
        }

        echo '<div class="container">';
        echo '<h1>Погода в ' . $cityName . '</h1>';
        echo '<p>Місто: ' . $jsonData['name'] . '</p>';
        echo '<p>Температура: ' . number_format($tempCelsius, 1) . ' &#8451;</p>';
        echo '<p>Вологість: ' . $jsonData['main']['humidity'] . '%</p>';
        echo '<p>Опис: ' . $jsonData['weather'][0]['description'] . '</p>';
        echo '<img src="' . $imageSrc . '" alt="Погода в ' . $cityName . '">';
        echo '</div>';
    }

    echo '</body></html>';
} catch (Exception $e) {
    echo 'Помилка: ' . $e->getMessage();
}
