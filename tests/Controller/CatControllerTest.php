<?php

namespace App\Tests\Controller;

use App\Tests\Controller\TestUtilities;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CatControllerTest extends WebTestCase
{
    public function testCatList(): void
    {
        $client = static::createClient();

        TestUtilities::logUser($this, $client);

        // Find the URL of the first cat of the list
        $catURL = TestUtilities::getFirstCatURL($this, $client);

        $client->request('GET', $catURL);
        $this->assertResponseIsSuccessful();

        // Visit each sub-category
        $pages = ['editer-infos', 'editer-adresse-proprietaire', 'editer-adresse-veterinaire'];

        foreach($pages as $page) {
            $client->request('GET', $catURL . '/' . $page);
            $this->assertResponseIsSuccessful();
        }
    }
}