<?php

namespace Modules\News\Services;

class NewsService
{
    public function __construct() {}

    public function newsApiWebhook(array $data) {
        $url = env('NEWS_API_EVERYTHING_URL');
        $topUrl = env('NEWS_API_TOP_URL');
        $key = env('NEWS_API_KEY');

        $topUrl .= '?pageSize=5';
        $url .= '?pageSize=5';

        if (data_get($data, 'category')) {
            $topUrl .= '&category=' . data_get($data, 'category');
            $url = $topUrl;
        } else if (data_get($data, 'source')) {
            $topUrl .= '&sources=' . data_get($data, 'source');
            $url = $topUrl;
        } else {

            if (data_get($data, 'sort')) {
                $url .= '&sortBy=' . data_get($data, 'sort');
            }

            if (data_get($data, 'start')) {
                $url .= '&from=' . data_get($data, 'start');
            }

            if (data_get($data, 'end')) {
                $url .= '&to=' . data_get($data, 'end');
            }
        }

        if (data_get($data, 'page')) {
            $url .= '&page=' . data_get($data, 'page');
        }

        if (data_get($data, 'query')) {
            $url .= '&q=' . data_get($data, 'query');
        } else {
            // default value
            $url .= '&q=a';
        }

        $headers = [
            'X-Api-Key: ' . $key,
            'Content-Type: application/json',
            'User-Agent: sail_user'
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        return json_decode($response);
    }

    public function newYorkTimesWebhook(array $data) {
        $url = env('NYT_API_URL');
        $key = env('NYT_API_KEY');

        $url .=  '?api-key=' . $key;

        if (data_get($data, 'page')) {
            $url .= '&page=' . data_get($data, 'page');
        }

        if (data_get($data, 'start')) {
            $url .= '&begin_date=' . data_get($data, 'start');
        }

        if (data_get($data, 'end')) {
            $url .= '&end_date=' . data_get($data, 'end');
        }

        if (data_get($data, 'query')) {
            $url .= '&q=' . data_get($data, 'query');
        }

        if (data_get($data, 'sort')) {
            $url .= '&sort=' . data_get($data, 'sort');
        }

        $headers = [
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        return json_decode($response);
    }

    public function theGuardianWebhook(array $data) {
        $url = env('TG_API_URL');
        $key = env('TG_API_KEY');

        $url .=  '?page-size=5&api-key=' . $key;

        if (data_get($data, 'page')) {
            $url .= '&page=' . data_get($data, 'page');
        }

        if (data_get($data, 'source')) {
            $url .= '&section=' . data_get($data, 'source');
        }

        if (data_get($data, 'start')) {
            $url .= '&from_date=' . data_get($data, 'start');
        }

        if (data_get($data, 'end')) {
            $url .= '&to_date=' . data_get($data, 'end');
        }

        if (data_get($data, 'query')) {
            $url .= '&q=' . data_get($data, 'query');
        }

        if (data_get($data, 'sort')) {
            $url .= '&order_by=' . data_get($data, 'sort');
        }

        $headers = [
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        return json_decode($response);
    }
}
