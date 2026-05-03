<?php

namespace Tests\Infra;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class AccountControllerTest extends WebTestCase
{
    private function createUser(KernelBrowser $client, string $email = "lucas@email.com"): string
    {
        $client->request(
            "POST",
            "/users",
            server: ["CONTENT_TYPE" => "application/json"],
            content: json_encode([
                "name" => "Lucas",
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
}