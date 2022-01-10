<?php

namespace App\Tests\Controller;

use App\Tests\Controller\TestUtilities;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testAdminMenu(): void
    {
        $client = static::createClient();

        TestUtilities::logUser($this, $client, 'admin@mail.com', 'Password0', 'admin-interface');

        // Visit each sub-category
        $pages = ['gestion-utilisateurs', 'gestion-faq'];

        foreach($pages as $page) {
            $client->request('GET',  '/espace-administrateur/' . $page);
            $this->assertResponseIsSuccessful();
        }
    }
}