<?php

namespace App\Tests\Controller;

use App\Tests\Controller\TestUtilities;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GuestSecurityControllerTest extends WebTestCase
{
    public function testGuestCode(): void
    {
        $client = static::createClient();

        TestUtilities::logUser($this, $client);

        // Find the URL of the first cat of the list
        $catURL = TestUtilities::getFirstCatURL($this, $client);

        // Fetch a new guest code
        $client->xmlHttpRequest('POST', $catURL . '/nouveau-code');
        $this->assertResponseIsSuccessful();

        $guestCode = $client->getResponse()->getContent();
        $this->assertNotEmpty($guestCode);

        // Log the guest
        TestUtilities::logGuest($this, $client, $guestCode);
    }
}