<?php

namespace Tests\Infra;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
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

    public function testLogin()
    {
        $client = static::createClient();
        $client->disableReboot();
        $email = $this->createUser($client);

        $client->request(
            method: "POST",
            uri: "/login",
            server: ["CONTENT_TYPE" => "application/json"],
            content: json_encode([
                "email" => $email,
                "password" => "P@ssw0t789"
            ])
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testLoginWithoutRegister()
    {
        $client = static::createClient();

        $client->request(
            method: "POST",
            uri: "/login",
            server: ["CONTENT_TYPE" => "application/json"],
            content: json_encode([
                "email" => "john.doe@email.com",
                "password" => "P@ssw0t789"
            ])
        );

        $this->assertResponseStatusCodeSame(404);
    }

    public function testLoginWithWrongPassword()
    {
        $client = static::createClient();
        $client->disableReboot();
        $email = $this->createUser($client);

        $client->request(
            method: "POST",
            uri: "/login",
            server: ["CONTENT_TYPE" => "application/json"],
            content: json_encode([
                "email" => $email,
                "password" => "WrongPassword"
            ])
        );

        $this->assertResponseStatusCodeSame(401);
    }

}