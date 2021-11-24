<?php


namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class PocketClient
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * HarmonyClient constructor.
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $host
     * @return array|null
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function nodeStatus(string $host = 'https://api.s0.t.hmny.io/'): ?array
    {
        $requestData = [
            "payload" => [
                "data" => "",
                "method" => "POST",
                "path" => "",
                "headers" => ['x' => 'y'],
            ]
        ];

        try {

            $response = $this->httpClient->request('POST', $host . 'status', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'max_duration' => 5,
                'body' => json_encode($requestData)
            ]);

            $data = $response->toArray(false);

            return isset($data['result']) ? $data['result'] : null;
        }
        catch (\Exception $e)
        {
            return null;
        }
    }
}