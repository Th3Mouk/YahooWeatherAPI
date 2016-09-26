Yahoo Weather API
=================

This PHP library providing a simple way to communicate with Yahoo Weather API.

[![Latest Stable Version](https://poser.pugx.org/th3mouk/yahoo-weather-api/v/stable)](https://packagist.org/packages/th3mouk/yahoo-weather-api) [![Latest Unstable Version](https://poser.pugx.org/th3mouk/yahoo-weather-api/v/unstable)](https://packagist.org/packages/th3mouk/yahoo-weather-api) [![Total Downloads](https://poser.pugx.org/th3mouk/yahoo-weather-api/downloads)](https://packagist.org/packages/th3mouk/yahoo-weather-api) [![License](https://poser.pugx.org/th3mouk/yahoo-weather-api/license)](https://packagist.org/packages/th3mouk/yahoo-weather-api)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4ad02cc8-b470-46e0-a40d-4f23e5a4b1b4/mini.png)](https://insight.sensiolabs.com/projects/4ad02cc8-b470-46e0-a40d-4f23e5a4b1b4) [![Build Status](https://travis-ci.org/Th3Mouk/YahooWeatherAPI.svg?branch=master)](https://travis-ci.org/Th3Mouk/YahooWeatherAPI) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Th3Mouk/YahooWeatherAPI/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Th3Mouk/YahooWeatherAPI/?branch=master) [![Coverage Status](https://coveralls.io/repos/github/Th3Mouk/YahooWeatherAPI/badge.svg?branch=master)](https://coveralls.io/github/Th3Mouk/YahooWeatherAPI?branch=master)

## Installation

`composer require th3mouk/yahoo-weather-api ^1.0`

## Usage

Simply implement the class

```php
$yahooWeather = new YahooWeatherAPI();
```

### Methods

Get forecasts with a WOEID code :

```php
$yahooWeather->callApiWoeid($woeid = null, $unit = 'c');
```

Get forecasts with a city name :

```php
$yahooWeather->callApiCityName($name = null, $unit = 'c');
```

Get forecasts with a [yql request](https://developer.yahoo.com/yql/console/) :

```php
$yahooWeather->callApi($yql = null);
```

## Thanks
- To [Jean-Baptiste Audebert](https://github.com/jb18) for the first layer of code
- To [peter279k](https://github.com/peter279k) for testing  suite

## Contributing

Before commiting, please run `php-cs-fixer fix .` command, and update the test suite. 

## Please

Feel free to improve this library.
