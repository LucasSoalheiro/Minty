<?php

namespace Tests\Infra;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private function createUser($client, string $email = "lucas@email.com"): string
    {

        $client->request(
            method: "POST",
            uri: "/users",
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

    // Create User Route Tests
    public function testCreateUser()
    {
        $client = static::createClient();

        $client->request(
            method: "POST",
            uri: "/users",
            server: [
                "CONTENT_TYPE" => "application/json",
            ],
            content: json_encode([
                "name" => "Lucas",
                "email" => "lucas@email.com",
                "password" => "P@ssw0t789"
            ])
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
    }

    public function testShouldNotCreateUserWIthoutName()
    {
        $client = static::createClient();

        $client->request(
            method: "POST",
            uri: "/users",
            server: [
                "CONTENT_TYPE" => "application/json",
            ],
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
            method: "POST",
            uri: "/users",
            server: [
                "CONTENT_TYPE" => "application/json",
            ],
            content: json_encode([
                "name" => "Lucas",
                "email" => "lucasemail.com",
                "password" => "P@ssw0t789"
            ])
        );

        $this->assertResponseStatusCodeSame(400);

    }

    // Find By Email Route Tests

    public function testFindByEmail(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $email = $this->createUser($client);

        $client->request(
            method: "GET",
            uri: "/users?email=lucas@email.com",
            server: ["CONTENT_TYPE" => "application/json"]
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($email, $data['data']['email']);
    }

    public function testUserNotFoundWithSendedEmail(): void
    {
        $client = static::createClient();
        $client->request(
            method: "GET",
            uri: "/users?email=lucas@email.com",
            server: ["CONTENT_TYPE" => "application/json"]
        );

        $this->assertResponseStatusCodeSame(404);
    }

    // Change Email Route Tests

    public function testChangeEmail(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $email = $this->createUser($client);
        $client->request(
            method: "GET",
            uri: "/users?email=lucas@email.com",
            server: ["CONTENT_TYPE" => "application/json"]
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        $client->request(
            method: "PATCH",
            uri: "/users/email/" . $data["data"]["id"],
            server: ["CONTENT_TYPE" => "application/json"],
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
        $client->request(
            method: "GET",
            uri: "/users?email=lucas@email.com",
            server: ["CONTENT_TYPE" => "application/json"]
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        $client->request(
            method: "PATCH",
            uri: "/users/email/" . $data["data"]["id"],
            server: ["CONTENT_TYPE" => "application/json"],
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
        $this->createUser($client);
        $this->createUser($client, "other.email@email.com");
        $client->request(
            method: "GET",
            uri: "/users?email=lucas@email.com",
            server: ["CONTENT_TYPE" => "application/json"]
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        $client->request(
            method: "PATCH",
            uri: "/users/email/" . $data["data"]["id"],
            server: ["CONTENT_TYPE" => "application/json"],
            content: json_encode([
                "email" => "other.email@email.com",
                "password" => "P@ssw0t789"
            ])
        );
        $this->assertResponseStatusCodeSame(409);
    }

    // Change Password Tests
    public function testUpdatePassword(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $email = $this->createUser($client);

        $client->request(
            method: "PATCH",
            uri: "/users/password?email=$email",
            server: ["CONTENT_TYPE" => "application/json"],
            content: json_encode([
                "email" => "other.email@email.com",
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

        $client->request(
            method: "PATCH",
            uri: "/users/password?email=$email",
            server: ["CONTENT_TYPE" => "application/json"],
            content: json_encode([
                "email" => "other.email@email.com",
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

        $client->request(
            method: "PATCH",
            uri: "/users/password?email=$email",
            server: ["CONTENT_TYPE" => "application/json"],
            content: json_encode([
                "email" => "other.email@email.com",
                "oldPassword" => "P@ssw0t789",
                "newPassword" => "P@ssw0t789"
            ])
        );
        $this->assertResponseStatusCodeSame(409);
    }
}