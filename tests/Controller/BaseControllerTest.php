<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseControllerTest extends WebTestCase
{
    public function testHome(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient();

        // Request a specific page
        $client->request('GET', '/');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Le Charnet de Santé');
    }

    public function testHelp(): void
    {
        $client = static::createClient();

        $client->request('GET', '/aide');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'MODE D\'EMPLOI');
    }

    public function testLegalNotice(): void
    {
        $client = static::createClient();

        $client->request('GET', '/conditions-d-utilisation');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'CONDITIONS D\'UTILISATION ET MENTIONS LÉGALES');
    }

    public function testUserInterfaceNotLogged(): void
    {
        $client = static::createClient();

        $client->request('GET', '/espace-utilisateur');

        // We should be redirected to "login" route
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame("login");
    }

    public function testUserInterfaceLogged(): void
    {
        $client = static::createClient();

        $client->request('GET', '/espace-utilisateur');

        // We should be redirected to "login" route
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame("login");

        // Fill the login form
        $client->submitForm('Connexion', [
            'email' => 'fakeuser1@mail.com',
            'password' => 'Password1',
        ]);

        // We should be redirected to "user-interface" route
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame("user-interface");

        // Trying to go back to the "login" route should redirect to "homepage"
        $client->request('GET', '/connexion');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame("homepage");
    }
}