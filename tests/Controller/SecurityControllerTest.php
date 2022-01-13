<?php

namespace App\Tests\Controller;

use App\Tests\Controller\TestUtilities;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{

    public function testUserAccount(): void
    {
        $client = static::createClient();

        TestUtilities::logUser($this, $client);

        // Open "user-account"
        $client->request('GET', '/espace-utilisateur/compte');
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame("user-account");
    }

    public function testLogout(): void
    {
        $client = static::createClient();

        TestUtilities::logUser($this, $client);

        $client->request('GET', '/deconnexion');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame("homepage");
    }
}