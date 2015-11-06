<?php

/*
 * (c) Jean-Baptiste Audebert <audebert.jb@gmail.com>
 * (c) Jérémy Marodon         <marodon.jeremy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\YahooWeatherAPI;

use Goutte\Client;
use Th3Mouk\YahooWeatherAPI\Query\Query;

class YahooWeatherAPI implements YahooWeatherAPIInterface
{
    /**
     * @var Client Goutte client
     */
    protected $client;

    /**
     * @var array Last response from API
     */
    protected $lastResponse;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * {@inheritdoc}
     */
    public function callApiWoeid($woeid = null, $unit = 'c')
    {
        $woeidUse = ($woeid !== null) ? $woeid : $this->woeid;

        if ($woeidUse === null) {
            throw new \Exception('Please provide a woeid code', 1);
        }

        $yql = Query::URL_BASE.urlencode(sprintf(Query::WOEID_QUERY, $woeidUse, $unit));

        return $this->callApi($yql);
    }

    /**
     * {@inheritdoc}
     */
    public function callApiCityName($name = null, $unit = 'c')
    {
        if ($name === null) {
            throw new \Exception('Please provide a city\'s name', 1);
        }

        $yql = Query::URL_BASE.urlencode(sprintf(Query::CITY_NAME_QUERY, $name, $unit));

        return $this->callApi($yql);
    }

    /**
     * {@inheritdoc}
     */
    public function callApi($yql = null)
    {
        if ($yql === null) {
            throw new \Exception('Please provide a YQL request', 1);
        }

        try {
            $response = $this->client->getClient()->get($yql)->json();
            if (!isset($response['query']['results']['channel']['item']['condition'])) {
                $this->lastResponse = false;
            } else {
                $this->lastResponse = $response['query']['results']['channel'];
            }
        } catch (\Exception $e) {
            $this->lastResponse = false;
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
     * @param bool $with_unit return or not unit
     *
     * @return string
     */
    public function getTemperature($with_unit = false)
    {
        if (!$this->lastResponse || !isset($this->lastResponse['item']['condition']['temp'])) {
            return '';
        }
        $return = $this->lastResponse['item']['condition']['temp'];
        if ($with_unit) {
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
     * @return array
     */
    public function getWind($with_unit = false)
    {
        if (!$this->lastResponse || !isset($this->lastResponse['wind']['speed'])) {
            return array();
        }

        $response = array(
            'chill' => $this->lastResponse['item']['wind']['chill'],
            'direction' => $this->lastResponse['item']['wind']['direction'],
            'speed' => $this->lastResponse['item']['wind']['speed'],
        );

        if ($with_unit) {
            $response['speed'] .= ' '.$this->lastResponse['units']['speed'];
        }

        return $response;
    }
}
