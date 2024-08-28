<?php

namespace App\Services\AmoChat\Client;

use App\Exceptions\AmoChat\AmoChatRequestException;
use DateTimeInterface;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ApiClient implements ApiClientInterface
{
    protected string $baseUrl;

    protected string $secretKey;

    private PendingRequest $request;

    private Response|PromiseInterface $response;

    private string $endpoint;

    private array $body = [];

    public function __construct()
    {
        $this->baseUrl = "https://amojo.amocrm.ru";
        $this->secretKey = config('amochat.channel.secret_key');
    }

    /**
     * Calculate the checksum of the request body
     *
     * @param string $body
     * @return string
     */
    protected function createBodyChecksum(string $body): string
    {
        return md5($body);
    }

    /**
     * Calculate the signature of the request
     *
     * @param string $secret
     * @param string $checkSum
     * @param string $url
     * @param string $httpMethod
     * @param string $contentType
     * @return string
     */
    protected function createSignature(
        string $secret,
        string $checkSum,
        string $url,
        string $httpMethod = 'POST',
        string $contentType = 'application/json'
    ): string {
        $str = implode("\n", [
            strtoupper($httpMethod),
            $checkSum,
            $contentType,
            date(DateTimeInterface::RFC2822),
            $url,
        ]);

        return hash_hmac('sha1', $str, $secret);
    }

    /**
     * Prepare headers for the HTTP request
     *
     * @param string $checkSum
     * @param string $signature
     * @param string $contentType
     * @return array
     */
    protected function prepareHeaders(
        string $checkSum,
        string $signature,
        string $contentType = 'application/json'
    ): array {
        return [
            'Date' => date(DateTimeInterface::RFC2822),
            'Content-Type' => $contentType,
            'Content-MD5' => strtolower($checkSum),
            'X-Signature' => strtolower($signature),
            'User-Agent' => 'amoCRM-Chats-Doc-Example/1.0',
        ];
    }

    /**
     * Execute HTTP request to AmoCRM API
     *
     * @param string $url
     * @param array $body
     * @param string $httpMethod
     * @return array
     * @throws Exception
     */
    public function request(
        string $url,
        array $body,
        string $httpMethod = 'POST'
    ): array {
        $requestBody = json_encode($body);

        $request = $this->buildRequest($requestBody, $url, $httpMethod);

        $this->response = $response = $request->send($httpMethod, $url, [
            'body' => $requestBody,
        ]);

        if ($response->failed()) {
            $message = " ".PHP_EOL;
            $message .= "error code: ".json_decode($response->body(), true)['error_code'].PHP_EOL;
            $message .= "error type: ".json_decode($response->body(), true)['error_type'].PHP_EOL;
            $message .= "error description: ".json_decode($response->body(), true)['error_description'].PHP_EOL;
            $message .= "request body: ".$requestBody;

            throw new AmoChatRequestException($message, $response->status());
        }

        return $response->json();
    }

    public function getLastRequestInfo(): array
    {
        $request = $this->request->getOptions();
        $request['url'] = $this->baseUrl.$this->endpoint;
        $request['body'] = $this->body;
        $request['response'] = $this->response->json();

        return $request;
    }

    private function buildRequest(string $body, string $url, string $httpMethod): PendingRequest
    {
        $checkSum = $this->createBodyChecksum($body);
        $signature = $this->createSignature($this->secretKey, $checkSum, $url, $httpMethod);
        $headers = $this->prepareHeaders($checkSum, $signature);

        $this->endpoint = $url;
        $this->body = json_decode($body, true);

        return $this->request = Http::withHeaders($headers)->baseUrl($this->baseUrl);
    }
}