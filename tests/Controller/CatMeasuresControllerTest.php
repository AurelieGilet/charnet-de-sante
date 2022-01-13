<?php

namespace App\Tests\Controller;

use App\Tests\Controller\TestUtilities;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CatMeasuresControllerTest extends WebTestCase
{
    public function testCatMeasuresMenu(): void
    {
        $client = static::createClient();

        TestUtilities::logUser($this, $client);

        // Find the URL of the first cat of the list
        $catURL = TestUtilities::getFirstCatURL($this, $client) . '/mesures';

        $client->request('GET', $catURL);
        $this->assertResponseIsSuccessful();
        
        // Visit each sub-category
        $pages = ['poids', 'temperature', 'chaleurs'];

        foreach($pages as $page) {
            $client->request('GET', $catURL . '/' . $page);
            $this->assertResponseIsSuccessful();
        }
    }
}