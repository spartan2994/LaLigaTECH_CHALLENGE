<?php
// ...

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use PHPUnit\Framework\Assert;

class PlayerRequestProcessorUnitTest extends WebTestCase
{
    private static $client = null;
    private static $METHOD_POST = null;
    private static $METHOD_GET = null;
    private static $METHOD_DELETE = null;

    public function setUp(): void
    {
        self::$client = HttpClient::create();
        self::$METHOD_POST = "POST";
        self::$METHOD_GET = "GET";
        self::$METHOD_DELETE = "DELETE";
    }

    public function testCreatePlayerRequestTest(): void
    {
        $payload = [
            "id_club" => 2,
            "name" => "Robinho",
            "salary" => 10000000000,
            "email" => "robinho@fifa.com",
        ];

        $response = self::$client->request(
            self::$METHOD_POST,
            'http://127.0.0.1:8000/create_player',
            [],
            [],
            [],
            json_encode($payload)
        );

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $responseDta = json_decode($response->getContent(), true);
    }

    public function testGetPlayersRequestTest(): void
    {
        $response = self::$client->request(
            self::$METHOD_GET,
            'http://127.0.0.1:8000/players'
        );

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $responseDta = json_decode($response->getContent(), true);
    }

    public function testFindPlayersRequestTest(): void
    {
        $response = self::$client->request(
            self::$METHOD_GET,
            'http://127.0.0.1:8000/find_players?id_club=1&player=Diego Armando Maradona&pag=1'
        );

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $responseDta = json_decode($response->getContent(), true);
    }

    public function testDeletePlayerRequestTest(): void
    {
        $payload = [
            "id" => 2,
        ];

        $response = self::$client->request(
            self::$METHOD_DELETE,
            'http://127.0.0.1:8000/delete_player',
            [],
            [],
            [],
            json_encode($payload)
        );

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $responseDta = json_decode($response->getContent(), true);
    }
}
