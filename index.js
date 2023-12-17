const express = require('express');
const axios = require('axios');

const app = express();
const port = 3000;

// Масив з містами та їх URL API
const cities = {
  'Bucha': "https://api.openweathermap.org/data/2.5/weather?q=Bucha&appid=cf6aa194fcbc2f456eabad63e73d4c10",
  'Kyiv': "https://api.openweathermap.org/data/2.5/weather?q=Kyiv&appid=cf6aa194fcbc2f456eabad63e73d4c10",
  'Chernihiv': "https://api.openweathermap.org/data/2.5/weather?q=Chernihiv&appid=cf6aa194fcbc2f456eabad63e73d4c10",
  'NYC': "https://api.openweathermap.org/data/2.5/weather?q=New York&appid=cf6aa194fcbc2f456eabad63e73d4c10",
  'Canberra': "https://api.openweathermap.org/data/2.5/weather?q=Canberra&appid=cf6aa194fcbc2f456eabad63e73d4c10",
  'Tokyo': "https://api.openweathermap.org/data/2.5/weather?q=Tokyo&appid=cf6aa194fcbc2f456eabad63e73d4c10",
};

// Функція для отримання даних з API
async function fetchData(city) {
  try {
    const response = await axios.get(cities[city]);
    return response.data;
  } catch (error) {
    console.error(`Error fetching data for ${city}:`, error.message);
    throw error;
  }
}

// Ручка для відправки HTML-сторінки
app.get('/', async (req, res) => {
  const citiesToFetch = ['Kyiv', 'NYC', 'Tokyo'];
  const weatherData = await Promise.all(citiesToFetch.map(city => fetchData(city)));
  
  // Створення HTML-коду для відображення даних
  const htmlContent = `
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Weather Data</title>
    </head>
    <body>
      <h1>Weather Data for Cities</h1>
      <ul>
        ${weatherData.map(data => `<li>${JSON.stringify(data)}</li>`).join('')}
      </ul>
    </body>
    </html>
  `;
  
  res.send(htmlContent);
});

// Запуск сервера
app.listen(port, () => {
  console.log(`Server is running at http://localhost:${port}`);
});
