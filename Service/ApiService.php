<?php declare(strict_types=1);

namespace App\Service;

use App\SearchBundle\SearchBundle;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\CurlHandler;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class ApiService
{
    const APOD_URI = 'https://api.nasa.gov/planetary/apod';
    const APOD = 'apod';
    const NEOW_FEED = 'https://api.nasa.gov/neo/rest/v1/feed?start_date=START_DATE&end_date=END_DATE&api_key=API_KEY';
    const NEOW = 'neow';

    /** @var string Api Key */
    private $key;

    /** @var ClientInterface */
    private $httpClient;

    /** @var AdapterInterface */
    private $cacheAdapter;

    /** @var SearchBundle */
    private $bundle;

    public function __construct(AdapterInterface $cacheAdapter, $key, SearchBundle $bundle)
    {
        $this->httpClient = new GuzzleClient(
            [
                'handler' => new CurlHandler(),
            ]
        );
        $this->cacheAdapter = $cacheAdapter;
        $this->key = $key;
        $this->bundle = $bundle;
    }

    /**
     * @return bool|mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getApodData()
    {
        $cacheKey = self::constructCacheKey('apod');

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
                $this->resolveApodParameters()
            );
        } catch (GuzzleException $e) {
            return false;
        }

        $result = json_decode($request->getBody()->getContents());

        $item->set($result);
        $item->expiresAt(new \DateTime('tomorrow'));
        $this->cacheAdapter->save($item);

        return $result;
    }

    /**
     * @return bool|mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getNearEarthObjectsData()
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
                $this->resolveNearEarthObjectsParameters()
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
     * @return string
     */
    private function getApiKey(): string
    {
        return $this->key;
    }

    /**
     * @param bool $hd
     *
     * @return string
     */
    private function resolveApodParameters($hd = false): string
    {
        $date = date("Y-m-d");

        return $url = sprintf(
            '%s?date=%s&hd=%s&api_key=%s',
            self::APOD_URI,
            $date,
            $hd,
            $this->getApiKey()
        );
    }

    /**
     * @param array $date
     * @param bool $hd
     *
     * @return string
     */
    private function resolveNearEarthObjectsParameters($date = [null]): string
    {
        if ($date === [null]) {
            $date['start_date'] = '1900-01-01';
            $date['end_date'] = date("Y/m/d");
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
     * @param $api
     *
     * @return string
     */
    public static function constructCacheKey($api): string
    {
        return sprintf('cacheKey_%s', md5($api));
    }
}
