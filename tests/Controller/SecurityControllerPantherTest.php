<?php

namespace App\Tests\Controller;

use App\Tests\Controller\TestUtilities;
use Symfony\Component\Panther\PantherTestCase;

// This test uses Panther to really navigate the page
// Some links use JavaScript and can't be "clicked" otherwise
// Sadly, Panther doesn't support code coverage
class SecurityControllerPantherTest extends PantherTestCase
{
    public function testCreateAndDeleteUser(): void
    {
        // Intelephense may indicate that this function is undefined but it isn't true and works perfectly
        $client = static::createPantherClient();

        // Open homepage
        $client->request('GET', '/');

        // Go to registration
        $client->clickLink('Inscription');

        // Fill the user form
        $client->submitForm('Valider', [
            'registration_form[email]' => 'testuser@mail.com',
            'registration_form[username]' => 'TestUser',
            'registration_form[password][first]' => 'TestPassword1',
            'registration_form[password][second]' => 'TestPassword1'
        ]);

        // Validate the popup
        $this->assertSelectorTextContains('.success-content', 'Votre compte a bien été créé');
        $client->executeScript("document.querySelector('.modal-button').click();");

        // Log the new user
        // Fill the login form
        $client->submitForm('Connexion', [
            'email' => 'testuser@mail.com',
            'password' => 'TestPassword1',
        ]);
        $this->assertSelectorTextContains('h2', 'Bienvenue TestUser');
        $this->assertSelectorExists('.user-avatar');

        // Delete the account
        $client->request('GET', '/espace-utilisateur/compte');

        $client->executeScript("document.querySelector('#delete-user').click();");
        $crawler = $client->waitForVisibility('.delete-user-form');
        // Multiple forms in the page, need to use selectButton to find the right one
        $client->submit($crawler->selectButton('delete_user_form[submit]')->form(), [
            'delete_user_form[password]' => 'TestPassword1'
        ]);

        $this->assertSelectorNotExists('.user-avatar');
    }
}