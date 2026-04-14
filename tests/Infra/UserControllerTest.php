<?php

namespace Tests\Infra;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
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
                "password" => "P@ssw0rd"
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
                "password" => "P@ssw0rd"
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
                "password" => "P@ssw0rd"
            ])
        );

        $this->assertResponseStatusCodeSame(400);

    }
}