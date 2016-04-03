<?php
use GuzzleHttp\Client;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$app['api_key'] = 'AHpwAVcBAAAAf2MmHQIAoZffhyzsHuoj7MMaAzDHOHSEEgAAAAAAAAAAAAA1R-ZTuqoSi07C3Xjg7vQ4gNbZog==';

$app->get('/', function() use($app) {
    $ip = $_SERVER['REMOTE_ADDR'];

    $client = new Client(['base_uri' => 'http://api.lbs.yandex.net/']);

    try {
        $response = $client->request('POST', 'geolocation', [
            'form_params' => [
                'json' => json_encode(
                    [
                        'common' => [
                            'version' => '1.0',
                            'api_key' => $app['api_key']
                        ],
                        'ip' => [
                            'address_v4' => $ip
                        ]
                    ]
                )
            ]
        ]);
    } catch (\Exception $e) {
        return $e->getMessage();
    }

    $body = json_decode($response->getBody());

    $response = json_encode([
        'lat' => $body['position']['latitude'],
        'lon' => $body['position']['longitude']
    ]);

    return $response;
});

$app->run();