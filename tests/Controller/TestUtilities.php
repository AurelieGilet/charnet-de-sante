<?php

namespace App\Tests\Controller;

class TestUtilities {

    static public function logUser($context, $client, $email = 'fakeuser1@mail.com', $password = 'Password1', $route = 'user-interface'): void {
        $client->request('GET', '/connexion');
        $context->assertResponseIsSuccessful();
        $context->assertRouteSame("login");

        // Fill the login form
        $client->submitForm('Connexion', [
            'email' => $email,
            'password' => $password,
        ]);

        // We should be redirected to the "user-interface" route
        $client->followRedirect();
        $context->assertResponseIsSuccessful();
        $context->assertRouteSame($route);
    }

    static public function getFirstCatURL($context, $client): ?String {
        $crawler = $client->request('GET', '/espace-utilisateur/liste-chats');
        $context->assertResponseIsSuccessful();
        $context->assertRouteSame("cat-list");

        // Find the URL of the first cat of the list
        return $crawler->filter(".cat-list-wrapper > a")->first()->attr('href');
    }

    static public function logGuest($context, $client, $code): void {
        // Make sure the code doesn't have quotes
        $code = str_replace('"', "", $code);
        $code = str_replace("'", "", $code);

        $client->request('GET', '/');
        $context->assertResponseIsSuccessful();
        $context->assertRouteSame("homepage");

        $client->submitForm('Voir la fiche', [
            'password' => $code
        ]);

        // We should be redirected to the cat's page
        $client->followRedirect();
        $context->assertResponseIsSuccessful();

        $context->assertNotEquals("homepage", $client->getRequest()->get('_route'));
    }
}