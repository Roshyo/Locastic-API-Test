<?php

namespace App\tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class UserResourceTest extends ApiTestCase
{
    public function testCreateUser()
    {
        $client = self::createClient();

        $em = self::$container->get('doctrine')->getManager();

        $client->request('POST', '/users', ['headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json',], 'json' => ['email' => 'test@example.com', 'plainPassword' => 'aSecurePassword', 'lastName' => 'Last name', 'firstName' => 'First name',]]);
        $this->assertResponseStatusCodeSame(201);
    }
}
