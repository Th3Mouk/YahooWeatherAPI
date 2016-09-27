<?php

/*
 * (c) Jérémy Marodon         <marodon.jeremy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\YahooWeatherAPI;

interface YahooWeatherAPIInterface
{
    /**
     * Method to call Yahoo Api with WOEID.
     *
     * @param string $woeid woeid which correspond to the city you want
     * @param string $unit  c or f for celsius or fahreneit
     *
     * @return string representation of api response
     *
     * @throws \Exception
     */
    public function callApiWoeid($woeid = null, $unit = 'c');

    /**
     * Method to call Yahoo Api with city's name.
     *
     * @param string $name which correspond to the city's name
     * @param string $unit c or f for celsius or fahreneit
     *
     * @return string representation of api response
     *
     * @throws \Exception
     */
    public function callApiCityName($name = null, $unit = 'c');

    /**
     * Method to call Yahoo Api.
     *
     * @param string $yql the request to execute
     *
     * @return string representation of api response
     *
     * @throws \Exception
     */
    public function callApi($yql = null);
}
