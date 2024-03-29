<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Apod;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\CurlHandler;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Astronomy Picture of the Day service
 */
class ApodService
{
    const APOD_URI = 'https://api.nasa.gov/planetary/apod';
    const APOD = 'apod';

    /** @var string Api Key */
    private $key;

    /** @var ClientInterface */
    private $httpClient;

    /** @var AdapterInterface */
    private $cacheAdapter;

    /**
     * @param AdapterInterface $cacheAdapter
     * @param $key
     */
    public function __construct(AdapterInterface $cacheAdapter, $key)
    {
        $this->httpClient = new GuzzleClient(
            [
                'handler' => new CurlHandler(),
            ]
        );
        $this->cacheAdapter = $cacheAdapter;
        $this->key = $key;
    }

    /**
     * @return bool|mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getData()
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
                $this->resolveParameters()
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

    public function create($response)
    {
        $apod = new Apod();
        $apod->setTitle($response->title);
        $apod->setDate($response->date);
        $apod->setExplanation($response->explanation);
        $apod->setHdUrl($response->hdurl);
        $apod->setUrl($response->url);

        return $apod;
    }

    /**
     * @param bool $hd
     *
     * @return string
     */
    private function resolveParameters($hd = false): string
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
     * @return string
     */
    private function getApiKey()
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
