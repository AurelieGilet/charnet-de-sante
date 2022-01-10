<?php

namespace App\Tests\Controller;

use App\Tests\Controller\TestUtilities;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CatCareControllerTest extends WebTestCase
{
    public function testCatCareMenu(): void
    {
        $client = static::createClient();

        TestUtilities::logUser($this, $client);

        // Find the URL of the first cat of the list
        $catURL = TestUtilities::getFirstCatURL($this, $client) . '/entretien';

        $client->request('GET', $catURL);
        $this->assertResponseIsSuccessful();

        // Visit each sub-category
        $pages = ['alimentation', 'toilettage', 'griffes', 'yeux-et-oreilles', 'dents',
                'litière', 'notes'];

        foreach($pages as $page) {
            $client->request('GET', $catURL . '/' . $page);
            $this->assertResponseIsSuccessful();
        }
    }
}