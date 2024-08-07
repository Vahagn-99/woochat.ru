<?php

namespace App\Services\Whatsapp\Instance;

use App\Exceptions\InstanceCreationException;
use GuzzleHttp\Client;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class CreateInstanceApi implements CreateInstanceApiInterface
{
    protected static string $host = 'https://api.green-api.com';
    protected static string $endpoint = '{{partnerApiUrl}}/partner/createInstance/{{partnerToken}}';

    public function __construct(
        private readonly string $partnerApiUrl,
        private readonly string $partnerToken,
    )
    {
    }

    /**
     * @throws InstanceCreationException
     */
    public function newInstance(array $params): CreatedInstanceDTO
    {
        $client = new Client([
            'base_uri' => self::$host
        ]);
        $endpoint = str_replace('{{partnerApiUrl}}', $this->partnerApiUrl, self::$endpoint);
        $endpoint = str_replace('{{partnerToken}}', $this->partnerToken, $endpoint);

        $request = Http::acceptJson()->setClient($client);
        try {

            $response = $request->post($endpoint, $params)->json();
            return new CreatedInstanceDTO(
                $response['idInstance'],
                $response['apiTokenInstance'],
                $response['typeInstance'],
            );
        } catch (ConnectionException $e) {
            throw new  InstanceCreationException($e->getMessage());
        }
    }
}