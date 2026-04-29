<?php

namespace Tests\Infra;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private function createUser($client, string $email = "lucas@email.com"): string
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

    private function loginAndGetToken($client, string $email): string
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

    private function authHeader(string $token): array
    {
        return [
            "CONTENT_TYPE" => "application/json",
            "HTTP_AUTHORIZATION" => "Bearer $token"
        ];
    }

    // ========================
    // CREATE USER
    // ========================

    public function testCreateUser()
    {
        $client = static::createClient();

        $this->createUser($client);

        $this->assertResponseIsSuccessful();
    }

    public function testShouldNotCreateUserWIthoutName()
    {
        $client = static::createClient();

        $client->request(
            "POST",
            "/users",
            server: ["CONTENT_TYPE" => "application/json"],
            content: json_encode([
                "email" => "lucas@email.com",
                "password" => "P@ssw0t789"
            ])
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testShouldNotCreateUserWithEmailMalformatted()
    {
        $client = static::createClient();

        $client->request(
            "POST",
            "/users",
            server: ["CONTENT_TYPE" => "application/json"],
            content: json_encode([
                "name" => "Lucas",
                "email" => "lucasemail.com",
                "password" => "P@ssw0t789"
            ])
        );

        $this->assertResponseStatusCodeSame(400);
    }

    // ========================
    // FIND
    // ========================

    public function testUserNotFoundWithSendedEmail(): void
    {
        $client = static::createClient();

        $client->request("GET", "/users/email/lucas@email.com");

        $this->assertResponseStatusCodeSame(404);
    }


    // ========================
    // SEARCH
    // ========================

    public function testSearchByEmail(): void
    {
        $client = static::createClient();

        $this->createUser($client);

        $client->request("GET", "/users/search?email=lucas@email.com");

        $this->assertResponseIsSuccessful();
    }

    // ========================
    // CHANGE EMAIL
    // ========================

    public function testChangeEmail(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $email = $this->createUser($client);
        $token = $this->loginAndGetToken($client, $email);

        $client->request(
            "PATCH",
            "/users/email",
            server: $this->authHeader($token),
            content: json_encode([
                "email" => "test@email.com",
                "password" => "P@ssw0t789"
            ])
        );

        $this->assertResponseIsSuccessful();
    }

    public function testChangeEmailWithIncorrectPassword(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $email = $this->createUser($client);
        $token = $this->loginAndGetToken($client, $email);

        $client->request(
            "PATCH",
            "/users/email",
            server: $this->authHeader($token),
            content: json_encode([
                "email" => "test@email.com",
                "password" => "Wrong_password"
            ])
        );

        $this->assertResponseStatusCodeSame(401);
    }

    public function testChangeEmailWithAEmailThatIsAlreadyInUse(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $email = $this->createUser($client);
        $this->createUser($client, "other@email.com");

        $token = $this->loginAndGetToken($client, $email);

        $client->request(
            "PATCH",
            "/users/email",
            server: $this->authHeader($token),
            content: json_encode([
                "email" => "other@email.com",
                "password" => "P@ssw0t789"
            ])
        );

        $this->assertResponseStatusCodeSame(409);
    }

    // ========================
    // PASSWORD
    // ========================

    public function testUpdatePassword(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $email = $this->createUser($client);
        $token = $this->loginAndGetToken($client, $email);

        $client->request(
            "PATCH",
            "/users/password?email=$email",
            server: $this->authHeader($token),
            content: json_encode([
                "oldPassword" => "P@ssw0t789",
                "newPassword" => "NewP@ssw0t789"
            ])
        );

        $this->assertResponseIsSuccessful();
    }

    public function testUpdatePasswordWithWrongPassword(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $email = $this->createUser($client);
        $token = $this->loginAndGetToken($client, $email);

        $client->request(
            "PATCH",
            "/users/password?email=$email",
            server: $this->authHeader($token),
            content: json_encode([
                "oldPassword" => "WrongPassword",
                "newPassword" => "NewP@ssw0t789"
            ])
        );

        $this->assertResponseStatusCodeSame(403);
    }

    public function testUpdatePasswordWitSamePassword(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $email = $this->createUser($client);
        $token = $this->loginAndGetToken($client, $email);

        $client->request(
            "PATCH",
            "/users/password?email=$email",
            server: $this->authHeader($token),
            content: json_encode([
                "oldPassword" => "P@ssw0t789",
                "newPassword" => "P@ssw0t789"
            ])
        );

        $this->assertResponseStatusCodeSame(409);
    }

    // ========================
    // NAME
    // ========================

    public function testUpdateName(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $email = $this->createUser($client);
        $token = $this->loginAndGetToken($client, $email);

        $client->request(
            "PATCH",
            "/users/name?email=$email",
            server: $this->authHeader($token),
            content: json_encode([
                "name" => "Joao"
            ])
        );

        $this->assertResponseIsSuccessful();
    }
}