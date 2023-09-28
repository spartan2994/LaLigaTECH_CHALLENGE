<?php
// ...

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use PHPUnit\Framework\Assert;

class ClubRequestProcessorUnitTest extends WebTestCase
{
    private static $client = null;
    private static $METHOD_POST = null;

    public function setUp(): void
    {
        self::$client = HttpClient::create();
        self::$METHOD_POST = "POST";
    }

    public function testCreateClubRequestTest(): void
    {
        $payload = [
            "name" => "Real Madrid",
            "budget" => 70000,
        ];

        $response = self::$client->request(
            self::$METHOD_POST,
            "http://127.0.0.1:8000/create_club",
            [],
            [],
            [],
            json_encode($payload)
        );

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $responseDta = json_decode($response->getContent(), true);
    }

    public function testCreateClubDuplicatedRequest(): void
    {
        $payload = [
            "name" => "Barca 5",
            "budget" => 24000,
        ];

        $response = self::$client->request(
            self::$METHOD_POST,
            "http://127.0.0.1:8000/create_club",
            [],
            [],
            [],
            json_encode($payload)
        );

        self::assertContains(
            200,
            $response->getContent(),
            "testArray doesn't contains duplicated as value"
        );
    }

    public function testUpdateClubBudgetRequestTest(): void
    {
        $payload = [
            "id" => 3,
            "budget" => 70000,
        ];

        $response = self::$client->request(
            self::$METHOD_POST,
            "http://127.0.0.1:8000/set_budget_club",
            [],
            [],
            [],
            json_encode($payload)
        );

        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $responseDta = json_decode($response->getContent(), true);
    }
}
