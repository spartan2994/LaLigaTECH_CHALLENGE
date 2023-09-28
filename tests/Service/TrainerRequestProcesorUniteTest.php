<?php
// ...

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use PHPUnit\Framework\Assert;

class TrainerRequestProcessorUnitTest extends WebTestCase
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
            "name" => "Javier Aguirre",
            "salary" => 12000,
            "email" => "javier_aguirre@llt.com",
        ];

        $response = self::$client->request(
            self::$METHOD_POST,
            'http://127.0.0.1:8000/create_trainer',
            [],
            [],
            [],
            json_encode($payload)
        );

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $responseDta = json_decode($response->getContent(), true);
    }

    public function testGetTrainersRequestTest(): void
    {
        $response = self::$client->request(
            self::$METHOD_GET,
            'http://127.0.0.1:8000/players'
        );

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $responseDta = json_decode($response->getContent(), true);
    }

    public function testDeleteTrainerRequestTest(): void
    {
        $payload = [
            "id" => 4,
        ];

        $response = self::$client->request(
            self::$METHOD_DELETE,
            'http://127.0.0.1:8000/delete_trainer',
            [],
            [],
            [],
            json_encode($payload)
        );

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $responseDta = json_decode($response->getContent(), true);
    }
}
