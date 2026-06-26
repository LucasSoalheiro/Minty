<?php

namespace Tests\Infra;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class AccountControllerTest extends WebTestCase
{
    private function createUser(KernelBrowser $client, string $email = "john.doe@example.com"): string
    {
        $client->request(
            "POST",
            "/users",
            server: ["CONTENT_TYPE" => "application/json"],
            content: json_encode([
                "name" => "John Doe",
                "email" => $email,
                "password" => "P@ssw0t789"
            ])
        );

        $this->assertResponseStatusCodeSame(201);

        return $email;
    }

    private function loginAndGetToken(KernelBrowser $client, string $email): string
    {
        $client->request(
            "POST",
            "/login",
            server: ["CONTENT_TYPE" => "application/json"],
            content: json_encode([
                "email" => $email,
                "password" => "P@ssw0t789"
            ])
        );

        $this->assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);

        return $response['data']['access_token'];
    }

    public function testCreateAccount()
    {
        $client = static::createClient();
        $client->disableReboot();
        $email = $this->createUser($client);
        $token = $this->loginAndGetToken($client, $email);
        $client->request(
            "POST",
            "/accounts",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ],
            content: json_encode([
                "name" => "My Account",
                "balance" => 1000
            ])
        );
        $this->assertResponseStatusCodeSame(201);
    }


    public function testCreateAccountUnauthorized()
    {
        $client = static::createClient();
        $client->request(
            "POST",
            "/accounts",
            server: ["CONTENT_TYPE" => "application/json"],
            content: json_encode([
                "name" => "My Account",
                "balance" => 1000
            ])
        );
        $this->assertResponseStatusCodeSame(401);
    }

    public function testListAccounts()
    {
        $client = static::createClient();
        $client->disableReboot();
        $email = $this->createUser($client, "john@example.com");
        $token = $this->loginAndGetToken($client, $email);
        $client->request(
            "GET",
            "/accounts",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ]
        );
        $this->assertResponseIsSuccessful();
    }

    public function testListAccountsUnauthorized()
    {
        $client = static::createClient();
        $client->request(
            "GET",
            "/accounts",
            server: ["CONTENT_TYPE" => "application/json"]
        );
        $this->assertResponseStatusCodeSame(401);
    }

    public function testGetAccountById()
    {
        $client = static::createClient();
        $client->disableReboot();
        $email = $this->createUser($client, "john@example.com");
        $token = $this->loginAndGetToken($client, $email);
        $client->request(
            "POST",
            "/accounts",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ],
            content: json_encode([
                "name" => "My Account",
                "balance" => 1000
            ])
        );
        $this->assertResponseStatusCodeSame(201);
        $client->request(
            "GET",
            "/accounts",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ]
        );
        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);
        $accountId = $response['data'][0]['id'];
        $client->request(
            "GET",
            "/accounts/$accountId",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ]
        );
        $this->assertResponseIsSuccessful();
    }

    public function testGetAccountByIdUnauthorized()
    {
        $client = static::createClient();
        $client->request(
            "GET",
            "/accounts/non-existing-id",
            server: ["CONTENT_TYPE" => "application/json"]
        );
        $this->assertResponseStatusCodeSame(401);
    }

    public function testGetAccountByIdNotFound()
    {
        $client = static::createClient();
        $client->disableReboot();
        $email = $this->createUser($client, "john@example.com");
        $token = $this->loginAndGetToken($client, $email);
        $client->request(
            "GET",
            "/accounts/non-existing-id",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ]
        );
        $this->assertResponseStatusCodeSame(404);
    }

    public function testDeposit()
    {
        $client = static::createClient();
        $client->disableReboot();
        $email = $this->createUser($client, "john@example.com");
        $token = $this->loginAndGetToken($client, $email);
        $client->request(
            "POST",
            "/accounts",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ],
            content: json_encode([
                "name" => "My Account",
                "balance" => 1000
            ])
        );
        $this->assertResponseStatusCodeSame(201);
        $client->request(
            "GET",
            "/accounts",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ]
        );
        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);
        $accountId = $response['data'][0]['id'];
        $client->request(
            "POST",
            "/categories",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ],
            content: json_encode([
                "name" => "Test Category",
                "description" => "This is a test category"
            ])
        );

        $this->assertResponseStatusCodeSame(201);

        $client->request(
            "GET",
            "/categories",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ]
        );
        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);
        $categoryId = $response['data'][0]['id'];
        $client->request(
            "POST",
            "/accounts/$accountId/deposit",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ],
            content: json_encode([
                "amount" => 500,
                "categoryId" => $categoryId
            ])
        );
        $this->assertResponseIsSuccessful();

        $client->request(
            "GET",
            "/accounts/$accountId",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ]
        );
        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(1500, $response['data']['balance']);
    }

    public function testWithdraw(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $email = $this->createUser($client, "john@example.com");
        $token = $this->loginAndGetToken($client, $email);
        $client->request(
            "POST",
            "/accounts",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ],
            content: json_encode([
                "name" => "My Account",
                "balance" => 1000
            ])
        );
        $this->assertResponseStatusCodeSame(201);
        $client->request(
            "GET",
            "/accounts",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ]
        );
        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);
        $accountId = $response['data'][0]['id'];
        $client->request(
            "POST",
            "/categories",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ],
            content: json_encode([
                "name" => "Test Category",
                "description" => "This is a test category"
            ])
        );

        $this->assertResponseStatusCodeSame(201);

        $client->request(
            "GET",
            "/categories",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ]
        );
        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);
        $categoryId = $response['data'][0]['id'];
        $client->request(
            "POST",
            "/accounts/$accountId/withdraw",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ],
            content: json_encode([
                "amount" => 500,
                "categoryId" => $categoryId
            ])
        );
        $this->assertResponseIsSuccessful();

        $client->request(
            "GET",
            "/accounts/$accountId",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ]
        );
        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(500, $response['data']['balance']);

    }

    public function testTransfer(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $email = $this->createUser($client, "john@example.com");
        $token = $this->loginAndGetToken($client, $email);
        $client->request(
            "POST",
            "/accounts",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ],
            content: json_encode([
                "name" => "My Account",
                "balance" => 1000
            ])
        );
        $client->request(
            "POST",
            "/accounts",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ],
            content: json_encode([
                "name" => "My second Account",
                "balance" => 200
            ])
        );
        $this->assertResponseStatusCodeSame(201);
        $client->request(
            "GET",
            "/accounts",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ]
        );
        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);
        $accountId = $response['data'][0]['id'];
        $secondAccountId = $response['data'][1]['id'];
        $client->request(
            "POST",
            "/categories",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ],
            content: json_encode([
                "name" => "Test Category",
                "description" => "This is a test category"
            ])
        );

        $this->assertResponseStatusCodeSame(201);

        $client->request(
            "GET",
            "/categories",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ]
        );
        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);
        $categoryId = $response['data'][0]['id'];

        $client->request(
            "POST",
            "/accounts/$accountId/transfer",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ],
            content: json_encode([
                "amount" => 500,
                "categoryId" => $categoryId,
                "toAccountId" => $secondAccountId
            ])
        );
        $this->assertResponseIsSuccessful();

        // Verify balances
        $client->request("GET", "/accounts/$accountId", server: ["HTTP_AUTHORIZATION" => "Bearer $token"]);
        $res1 = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(500, $res1['data']['balance']);

        $client->request("GET", "/accounts/$secondAccountId", server: ["HTTP_AUTHORIZATION" => "Bearer $token"]);
        $res2 = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(700, $res2['data']['balance']);
    }
}