<?php

/*
 * (c) Jérémy Marodon         <marodon.jeremy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\YahooWeatherAPI\Query;

class Query
{
    /**
     * BASE URL to call for API.
     */
    const URL_BASE = 'https://query.yahooapis.com/v1/public/yql?format=json&q=';

    /**
     * YQL Query to retrieve datas from WOEID.
     */
    const WOEID_QUERY = 'select * from weather.forecast where woeid="%s" and u="%s"';

    /**
     * YQL Query to retrieve datas from city's name.
     */
    const CITY_NAME_QUERY = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="%s") and u="%s"';
}
