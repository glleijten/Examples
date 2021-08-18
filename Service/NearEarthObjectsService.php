<?php declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\CurlHandler;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Near Earth Object Web Service
 * NOTE: The Feed date limit is only 7 Days
 */
class NearEarthObjectsService
{
    //?start_date=START_DATE&end_date=END_DATE&api_key=API_KEY
    const NEOW_FEED = 'https://api.nasa.gov/neo/rest/v1/feed';

    /** @var string Api Key */
    private $key;

    /** @var ClientInterface */
    private $httpClient;

    /** @var AdapterInterface */
    private $cacheAdapter;

    /** @var SerializerInterface */
    private $serializer;

    /**
     * @param AdapterInterface $cacheAdapter
     * @param SerializerInterface $serializer
     * @param $key
     */
    public function __construct(AdapterInterface $cacheAdapter, SerializerInterface $serializer, $key)
    {
        $this->httpClient = new GuzzleClient(
            [
                'handler' => new CurlHandler(),
            ]
        );
        $this->cacheAdapter = $cacheAdapter;
        $this->key = $key;
        $this->serializer = $serializer;
    }

    /**
     * @param $response
     */
    public function create($response)
    {
        $nearEarthObjects = new NearEarthObjects();
        $nearEarthObjects->setTitle($response->title);
        $nearEarthObjects->setDate($response->date);
        $nearEarthObjects->setExplanation($response->explanation);
        $nearEarthObjects->setHdUrl($response->hdurl);
        $nearEarthObjects->setUrl($response->url);
    }

    /**
     * @return bool|mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function request()
    {
        /**
         *  EXAMPLE of request->near_earth_objects
         *
         * "2018-12-03": array:6 [▼
         * 0 => {#443 ▼
         * +"links": {#444 ▶}
         * +"id": "3735654"
         * +"neo_reference_id": "3735654"
         * +"name": "(2015 XJ1)"
         * +"nasa_jpl_url": "http://ssd.jpl.nasa.gov/sbdb.cgi?sstr=3735654"
         * +"absolute_magnitude_h": 23.4
         * +"estimated_diameter": {#445 ▼
         * +"kilometers": {#446 ▼
         * +"estimated_diameter_min": 0.0555334912
         * +"estimated_diameter_max": 0.1241766613
         * }
         * +"meters": {#447 ▼
         * +"estimated_diameter_min": 55.5334911581
         * +"estimated_diameter_max": 124.1766612574
         * }
         * +"miles": {#448 ▼
         * +"estimated_diameter_min": 0.0345069009
         * +"estimated_diameter_max": 0.0771597762
         * }
         * +"feet": {#449 ▼
         * +"estimated_diameter_min": 182.1964991311
         * +"estimated_diameter_max": 407.4037573197
         * }
         * }
         * +"is_potentially_hazardous_asteroid": false
         * +"close_approach_data": array:1 [▼
         * 0 => {#450 ▼
         * +"close_approach_date": "2018-12-03"
         * +"epoch_date_close_approach": 1543824000000
         * +"relative_velocity": {#451 ▼
         * +"kilometers_per_second": "14.5594553907"
         * +"kilometers_per_hour": "52414.0394063508"
         * +"miles_per_hour": "32568.0457633517"
         * }
         * +"miss_distance": {#452 ▼
         * +"astronomical": "0.1642395852"
         * +"lunar": "63.8891983032"
         * +"kilometers": "24569892"
         * +"miles": "15267023"
         * }
         * +"orbiting_body": "Earth"
         * }
         * ]
         * +"is_sentry_object": false
         * }
         */

        $cacheKey = self::constructCacheKey('neow');

        try {
            $item = $this->cacheAdapter->getItem($cacheKey);
        } catch (\InvalidArgumentException $e) {
            echo 'InvalidArgumentException';
        }

        if ($item->isHit()) {
            return $item->get();
        }

        try {
            $request = $this->httpClient->request(
                'GET',
                $this->resolveParameters()
            );
        } catch (GuzzleException $e) {
            return false;
        }

        $result = json_decode($request->getBody()->getContents());

        $item->set($result);
        $this->cacheAdapter->save($item);
        return $result;
    }

    /**
     * @param array $date
     *
     * @return string
     *
     * TODO: create FormType
     */
    private function resolveParameters($date = [null]): string
    {
        if ($date === [null]) {
            $date['start_date'] = '2018-12-01';
            $date['end_date'] = '2018-12-07';
        }

        return $url = sprintf(
            '%s?start_date=%s&end_date=%s&api_key=%s',
            self::NEOW_FEED,
            $date['start_date'],
            $date['end_date'],
            $this->getApiKey()
        );
    }

    /**
     * @param $response
     *
     * @return array
     */
    public function normalize($response): array
    {
        $array = [];
        foreach ($response as $object) {
            $array = [
                'id' => $object->id,
                'name' => $object->name,
                'nasa_jpl_url' => $object->nasa_jpl_url,
                'absolute_magnitude_h' => $object->absolute_magnitude_h,
                'estimated_diameter' => [
                    'estimated_diameter_min_km' => $object->estimated_diameter->kilometers->estimated_diameter_min,
                    'estimated_diameter_max_km' => $object->estimated_diameter->kilometers->estimated_diameter_max,
                    'estimated_diameter_min_mi' => $object->estimated_diameter->miles->estimated_diameter_min,
                    'estimated_diameter_max_mi' => $object->estimated_diameter->miles->estimated_diameter_max,
                ],
                'potentially_hazardous' => $object->is_potentially_hazardous_asteroid,
                'close_approach_data' => $object->close_approach_data
            ];
        }

        return $array;
    }

    /**
     * @return string
     */
    private function getApiKey() : string
    {
        return $this->key;
    }

    /**
     * @param $api
     *
     * @return string
     */
    public static function constructCacheKey($api): string
    {
        return sprintf('cacheKey_%s', md5($api));
    }
}
