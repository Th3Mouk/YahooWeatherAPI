Yahoo Weather API
=================

This PHP library providing a simple way to communicate with Yahoo Weather API.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4ad02cc8-b470-46e0-a40d-4f23e5a4b1b4/mini.png)](https://insight.sensiolabs.com/projects/4ad02cc8-b470-46e0-a40d-4f23e5a4b1b4) [![Latest Stable Version](https://poser.pugx.org/th3mouk/yahoo-weather-api/v/stable)](https://packagist.org/packages/th3mouk/yahoo-weather-api) [![Total Downloads](https://poser.pugx.org/th3mouk/yahoo-weather-api/downloads)](https://packagist.org/packages/th3mouk/yahoo-weather-api) [![Latest Unstable Version](https://poser.pugx.org/th3mouk/yahoo-weather-api/v/unstable)](https://packagist.org/packages/th3mouk/yahoo-weather-api) [![License](https://poser.pugx.org/th3mouk/yahoo-weather-api/license)](https://packagist.org/packages/th3mouk/yahoo-weather-api)

## Installation

`composer require th3mouk/yahoo-weather-api ^1.0@dev`

## Usage

Simply implement the class

```php
$service = new YahooWeatherAPI();
```

### Methods

Get forecasts with a WOEID code :

```php
$service->callApiWoeid($woeid = null, $unit = 'c');
```

Get forecasts with a city name :

```php
$service->callApiCityName($name = null, $unit = 'c');
```

Get forecasts with a [yql request](https://developer.yahoo.com/yql/console/) :

```php
$service->callApi($yql = null);
```

## Thanks
- To [Jean-Baptiste Audebert](https://github.com/jb18) for the first layer of code

## Please

Feel free to improve this library.
