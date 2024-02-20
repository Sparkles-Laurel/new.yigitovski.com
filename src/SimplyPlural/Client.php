<?php

declare(strict_types=1);

namespace App\SimplyPlural;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpOptions;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

const SP_BASE = "https://v2.apparyllis.com";

readonly class Client
{
    private HttpClientInterface $client;

    public function __construct(private string $token)
    {
        $this->client = HttpClient::create(
            (new HttpOptions())
                ->setBaseUri(SP_BASE)
                ->setHeaders([
                    'authorization' => $this->token
                ])
                ->toArray()
        );
    }

    /**
     * @param bool $raw
     * @return Front[]
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getFronters(bool $raw = false): array
    {
        $response = $this->client->request('GET', '/v1/fronters');

        $data = $response->toArray();

        if ($raw) return $data;

        return array_map(
            fn(array $data): Front => (new Response($data))->asFront(),
            $data
        );
    }

    /**
     * @param string $id
     * @param bool $raw
     * @return Member[]
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getMembers(string $id, bool $raw = false): array
    {
        $response = $this->client->request('GET', '/v1/members/' . $id);

        $data = $response->toArray();

        if ($raw) return $data;

        return array_map(
            fn(array $data): Member => (new Response($data))->asMember(),
            $data
        );
    }
}
