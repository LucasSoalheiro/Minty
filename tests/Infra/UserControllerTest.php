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

        // valida criação
        $this->assertResponseStatusCodeSame(201);

        return $email;
    }
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
        $this->assertEquals($email, $data['email']);
    }
}