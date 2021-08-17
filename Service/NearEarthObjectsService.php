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
    const NEOW_FEED = 'https://api.nasa.gov/neo/rest/v1/feed';
    const NEOW = 'neow';

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
     * @return string
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
     * @return array
     */
    public function normalize($response)
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
    private function getApiKey()
    {
        return $this->key;
    }

    /**
     * @param $api
     * @return string
     */
    public static function constructCacheKey($api): string
    {
        return sprintf('cacheKey_%s', md5($api));
    }
}
