<?php

namespace App\Tests\Controller;

use App\Tests\Controller\TestUtilities;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CatHealthControllerTest extends WebTestCase
{
    public function testCatHealthMenu(): void
    {
        $client = static::createClient();

        TestUtilities::logUser($this, $client);

        // Find the URL of the first cat of the list
        $catURL = TestUtilities::getFirstCatURL($this, $client) . '/sante';

        $client->request('GET', $catURL);
        $this->assertResponseIsSuccessful();

        // Visit each sub-category
        $pages = ['visite-veterinaire', 'allergies', 'maladies', 'blessures', 'chirurgie',
                'analyses', 'documents'];

        foreach($pages as $page) {
            $client->request('GET', $catURL . '/' . $page);
            $this->assertResponseIsSuccessful();
        }
    }
}