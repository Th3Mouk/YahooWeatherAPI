<?php

/*
 * (c) Jérémy Marodon         <marodon.jeremy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\YahooWeatherAPI;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Th3Mouk\YahooWeatherAPI\Query\Query;

class YahooWeatherAPI implements YahooWeatherAPIInterface
{
    /**
     * @var Client Goutte client
     */
    protected $client;

    /**
     * @var array last response from API
     */
    protected $lastResponse;

    /**
     * @var string last woeid explicitly called
     */
    protected $woeid;

    /**
     * @var string last city name explicitly called
     */
    protected $city;

    /**
     * @var string the last url called
     */
    protected $yql;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * {@inheritdoc}
     */
    public function callApiWoeid($woeid = null, $unit = 'c')
    {
        if ($woeid === null && $this->woeid === null) {
            throw new \Exception('Please provide a woeid code', 400);
        }

        if ($woeid !== null) {
            $this->woeid = $woeid;
        }

        $this->yql = Query::URL_BASE.urlencode(sprintf(Query::WOEID_QUERY, $this->woeid, $unit));

        return $this->callApi();
    }

    /**
     * {@inheritdoc}
     */
    public function callApiCityName($city = null, $unit = 'c')
    {
        if ($city === null && $this->city === null) {
            throw new \Exception('Please provide a city\'s name', 400);
        }

        if ($city !== null) {
            $this->city = $city;
        }

        $this->yql = Query::URL_BASE.urlencode(sprintf(Query::CITY_NAME_QUERY, $this->city, $unit));

        return $this->callApi();
    }

    /**
     * {@inheritdoc}
     */
    public function callApi($yql = null)
    {
        if ($yql === null && empty($this->yql)) {
            throw new \Exception('Please provide a YQL request', 400);
        }

        if ($yql !== null) {
            $this->yql = $yql;
        }

        try {
            $headers = array(
                'verify' => false,
            );
            $response = $this->client->request('GET', $this->yql, $headers);
            $response = json_decode($response->getBody(), true);
            if (!isset($response['query']['results']['channel']['item']['condition'])) {
                $this->lastResponse = false;
            } else {
                $this->lastResponse = $response['query']['results']['channel'];
            }
        } catch (\Exception $e) {
            throw new \Exception("Something error happen, please check your request url whether it's correct.", 400);
        }

        return $this->lastResponse;
    }

    /**
     * Get lastResponse.
     *
     * @param bool $toJson choose format for the return value (array or json)
     *
     * @return array|string
     */
    public function getLastResponse($toJson = false)
    {
        return ($toJson) ? json_encode($this->lastResponse) : $this->lastResponse;
    }

    /**
     * Set lastResponse.
     *
     * @param array $data data from json_encode
     */
    public function setLastResponse($data)
    {
        $this->lastResponse = $data;
    }

    /**
     * Get current temperature.
     *
     * @param bool $withUnit return or not the unit
     *
     * @return string
     */
    public function getTemperature($withUnit = false)
    {
        if (!$this->lastResponse || !isset($this->lastResponse['item']['condition']['temp'])) {
            return '';
        }
        $return = $this->lastResponse['item']['condition']['temp'];
        if ($withUnit) {
            $return .= ' '.$this->lastResponse['units']['temperature'];
        }

        return $return;
    }

    /**
     * Get Location.
     *
     * @return string
     */
    public function getLocation()
    {
        if (!$this->lastResponse || !isset($this->lastResponse['location']['city'])) {
            return '';
        }

        return $this->lastResponse['location']['city'];
    }

    /**
     * get Forecast.
     *
     * @return array
     */
    public function getForecast()
    {
        if (!$this->lastResponse || !isset($this->lastResponse['item']['forecast'])) {
            return array();
        }

        return $this->lastResponse['item']['forecast'];
    }

    /**
     * get Wind.
     *
     * @param bool $withUnit return or not the unit
     *
     * @return array
     */
    public function getWind($withUnit = false)
    {
        if (!$this->lastResponse || !isset($this->lastResponse['wind']['speed'])) {
            return array();
        }

        $response = array(
            'chill' => $this->lastResponse['wind']['chill'],
            'direction' => $this->lastResponse['wind']['direction'],
            'speed' => $this->lastResponse['wind']['speed'],
        );

        if ($withUnit) {
            $response['speed'] .= ' '.$this->lastResponse['units']['speed'];
        }

        return $response;
    }

    /**
     * @param GoutteClient $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getWoeid()
    {
        return $this->woeid;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getYql()
    {
        return $this->yql;
    }
}
