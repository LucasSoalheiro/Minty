<?php

namespace Tests\Infra;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
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

    public function testCreateCategory(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $email = $this->createUser($client);
        $token = $this->loginAndGetToken($client, $email);
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
    }

    public function testListCategories(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $email = $this->createUser($client, "john.doe@example.com");
        $token = $this->loginAndGetToken($client, $email);

        $client->request(
            "GET",
            "/categories",
            server: [
                "HTTP_AUTHORIZATION" => "Bearer $token"
            ]
        );

        $this->assertResponseIsSuccessful();
    }

}