<?php

/*
 * (c) Jérémy Marodon         <marodon.jeremy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\YahooWeatherAPI\Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Th3Mouk\YahooWeatherAPI\YahooWeatherAPI;

class YahooWeatherClientTests extends TestCase
{
    public function testCallApiWoeidException()
    {
        $service = new YahooWeatherAPI();

        $this->expectException(\Exception::class);

        $service->callApiWoeid(null);
    }

    public function testCallApiCityNameException()
    {
        $service = new YahooWeatherAPI();

        $this->expectException(\Exception::class);

        $service->callApiCityName(null);
    }

    public function testCallApiTestException()
    {
        $service = new YahooWeatherAPI();

        $this->expectException(\Exception::class);

        $service->callApi(null);
    }

    public function testCallApiLastRes()
    {
        $errYql = 'https://query.yahooapis.com/v1/public/yql?q=from%20weather.forecast%20where%20woeid%20in%20(select%20woeid%20from%20geo.places(1)%20where%20text=%22Taipei%22)&format=json&env=store://datatables.org/alltableswithkeys';

        $service = new YahooWeatherAPI();

        $this->expectException(\Exception::class);

        $service->callApi($errYql);
    }

    public function testSetClient()
    {
        $service = new YahooWeatherAPI();

        $response = $service->setClient(new Client());

        $this->assertNull($response);
    }

    public function testSetLastRes()
    {
        $service = new YahooWeatherAPI();

        $response = $service->setLastResponse(null);

        $this->assertNull($response);
    }

    public function testCallApiWoeid()
    {
        $service = new YahooWeatherAPI();
        $response = $service->callApiWoeid(1232345678);

        $this->assertFalse(false);

        $response = $service->callApiWoeid(2306179);
        $city = str_replace(' ', '', $response['location']['city']);
        $country = str_replace(' ', '', $response['location']['country']);
        $region = str_replace(' ', '', $response['location']['region']);

        $this->assertSame('TaipeiCity', $city);
        $this->assertSame('Taiwan', $country);
        $this->assertSame('TaipeiCity', $region);

        $woeid = 2306179;
        $response = $this->getWoeidTest($service);

        $this->assertSame($woeid, $response);
    }

    public function testCallApiCityName()
    {
        $service = new YahooWeatherAPI();
        $response = $service->callApiCityName('Taipei');
        $city = str_replace(' ', '', $response['location']['city']);
        $country = str_replace(' ', '', $response['location']['country']);
        $region = str_replace(' ', '', $response['location']['region']);

        $this->assertSame('TaipeiCity', $city);
        $this->assertSame('Taiwan', $country);
        $this->assertSame('TaipeiCity', $region);

        $city = 'Taipei';
        $response = $this->getCityTest($service);

        $this->assertSame($city, $response);
    }

    public function testCallApi()
    {
        $service = new YahooWeatherAPI();
        $yql = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="123456789")';
        $url = 'https://query.yahooapis.com/v1/public/yql?q='.$yql.'&format=json&env=store://datatables.org/alltableswithkeys';
        $url = $this->encodeURI($url);
        $response = $service->callApi($url);

        $this->assertFalse($response);

        $yql = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="Taipei")';
        $url = 'https://query.yahooapis.com/v1/public/yql?q='.$yql.'&format=json&env=store://datatables.org/alltableswithkeys';
        $url = $this->encodeURI($url);
        $response = $service->callApi($url);
        $city = str_replace(' ', '', $response['location']['city']);
        $country = str_replace(' ', '', $response['location']['country']);
        $region = str_replace(' ', '', $response['location']['region']);

        $this->assertSame('TaipeiCity', $city);
        $this->assertSame('Taiwan', $country);
        $this->assertSame('TaipeiCity', $region);

        $response = $this->getYqlTest($service);

        $this->assertSame($url, $response);
    }

    public function testGetLastRes()
    {
        $service = new YahooWeatherAPI();
        $service->setLastResponse($this->getResponseData());
        $response = $service->getLastResponse(false);

        $this->assertInternalType('array', $response);

        $response = $service->getLastResponse(true);

        $this->assertInternalType('string', $response);
    }

    public function testGetTemperature()
    {
        $service = new YahooWeatherAPI();
        $response = $this->getResponseData();
        $storeRes = $response;

        $service->setLastResponse(null);
        $service->getLastResponse(true);
        $response = $service->getTemperature(true);

        $this->assertSame('', $response);

        $noTemp = $storeRes;
        $noTemp['item']['condition']['temp'] = null;
        $service->setLastResponse($noTemp);
        $response = $service->getTemperature(false);

        $this->assertSame('', $response);

        $service->setLastResponse($storeRes);
        $response = $service->getTemperature(true);

        $this->assertInternalType('string', $response);

        $response = (int) $service->getTemperature(false);

        $this->assertInternalType('integer', $response);
    }

    public function testGetLocation()
    {
        $service = new YahooWeatherAPI();
        $response = $this->getResponseData();
        $storeRes = $response;

        $service->setLastResponse(null);
        $response = $service->getLocation();

        $this->assertSame('', $response);

        $noCity = $storeRes;
        $noCity['location']['city'] = null;
        $service->setLastResponse($noCity);
        $response = $service->getLocation();

        $this->assertSame('', $response);

        $service->setLastResponse($storeRes);
        $response = $service->getLocation();
        $city = str_replace(' ', '', $response);

        $this->assertSame('TaipeiCity', $city);
    }

    public function testGetForecast()
    {
        $service = new YahooWeatherAPI();
        $response = $this->getResponseData();
        $storeRes = $response;

        $service->setLastResponse(null);
        $response = $service->getForecast();

        $this->assertCount(0, $response);

        $noForecast = $storeRes;
        $noForecast['item']['forecast'] = null;
        $service->setLastResponse($noForecast);
        $response = $service->getForecast();

        $this->assertCount(0, $response);

        $service->setLastResponse($storeRes);
        $response = $service->getForecast();

        $this->assertCount(10, $response);
    }

    public function testGetWind()
    {
        $service = new YahooWeatherAPI();
        $response = $this->getResponseData();
        $storeRes = $response;

        $service->setLastResponse(null);
        $response = $service->getWind(true);

        $this->assertCount(0, $response);

        $noSpeed = $storeRes;
        $noSpeed['wind']['speed'] = null;
        $service->setLastResponse($noSpeed);
        $response = $service->getWind(true);

        $this->assertCount(0, $response);

        $service->setLastResponse($storeRes);
        $expectRes = array(
            'chill' => $storeRes['wind']['chill'],
            'direction' => $storeRes['wind']['direction'],
            'speed' => $storeRes['wind']['speed'],
        );

        $response = $service->getWind(true);
        $expectRes['speed'] .= ' '.$storeRes['units']['speed'];

        $this->assertSame($expectRes['speed'], $response['speed']);

        $expectRes = array(
            'chill' => $storeRes['wind']['chill'],
            'direction' => $storeRes['wind']['direction'],
            'speed' => $storeRes['wind']['speed'],
        );

        $response = $service->getWind(false);

        $this->assertSame($expectRes['speed'], $response['speed']);
    }

    public function getWoeidTest(YahooWeatherAPI $service)
    {
        return $service->getWoeid();
    }

    public function getCityTest(YahooWeatherAPI $service)
    {
        return $service->getCity();
    }

    public function getYqlTest(YahooWeatherAPI $service)
    {
        return $service->getYql();
    }

    public function encodeURI($url)
    {
        $unescaped = array(
            '%2D' => '-', '%5F' => '_', '%2E' => '.', '%21' => '!', '%7E' => '~',
            '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')',
        );
        $reserved = array(
            '%3B' => ';', '%2C' => ',', '%2F' => '/', '%3F' => '?', '%3A' => ':',
            '%40' => '@', '%26' => '&', '%3D' => '=', '%2B' => '+', '%24' => '$',
        );
        $score = array(
            '%23' => '#',
        );

        return strtr(rawurlencode($url), array_merge($reserved, $unescaped, $score));
    }

    public function getResponseData()
    {
        $response = file_get_contents(__DIR__ . '/response.json');
        $response = json_decode($response, true);

        return $response;
    }
}
