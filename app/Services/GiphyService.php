<?php

namespace App\Services;

use App\Transformers\GiphyTransformer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class GiphyService
{
    private $url = "http://api.giphy.com/v1/";
    private $client;
    private $apiKey;
    private $ratings = ['y', 'g', 'pg', 'pg-13'];

    /**
     * GiphyService constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.giphy.key');
    }

    /**
     * @param string $tag
     * @param string $rating
     * @return null
     */
    public function random($tag = '', $rating = 'g')
    {
        //Only allow specified ratings
        if(!in_array($rating, $this->ratings)) {
            $rating = 'g';
        }

        $url = $this->url . 'gifs/random';
        $res = $this->getRequest($url, [
            'tag'    => $tag,
            'rating' => $rating,
        ]);

        if($res['success']) {
            $json = fractal()->item($res['body']['data'], new GiphyTransformer())->toArray();

            return $json;
        }

        return null;
    }

    /**
     * @param string $query
     * @param string $rating
     * @param int $limit
     * @param int $offset
     * @return array|null
     */
    public function search($query = '', $rating = 'g', $limit = 25, $offset = 0)
    {
        //Only allow specified ratings
        if(!in_array($rating, $this->ratings)) {
            $rating = 'g';
        }

        $params = [
            'q'      => $query,
            'rating' => $rating,
        ];

        if($limit) {
            $params['limit'] = $limit;
        }

        if($offset) {
            $params['offset'] = $offset;
        }

        $url = $this->url . 'gifs/search';
        $res = $this->getRequest($url, $params);

        if($res['success']) {
            $json = fractal()->collection($res['body']['data'], new GiphyTransformer())->toArray();

            return $json;
        }

        return null;
    }

    /**
     * @param string $url
     * @param array $params
     * @return array
     */
    private function getRequest($url = '', $params = [])
    {
        $data = ['body' => null, 'success' => true];
        $query = $params;
        $query['api_key'] = $this->apiKey;

        try {
            $res = $this->client->get($url, [
                'query' => $query,
            ]);

            $res = json_decode($res->getBody(), true);
            $data['body'] = $res;
        } catch (BadResponseException $e) {
            $res = json_decode($e->getResponse()->getBody()->getContents(), true);

            $data['body'] = $res;
            $data['success'] = false;
        }

        return $data;
    }
}