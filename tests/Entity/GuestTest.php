<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use App\Entity\Guest;
use App\Entity\GuestCode;
use App\Entity\User;

final class GuestTest extends TestCase
{
    public function testAttributes(): void
    {
        $attributes = [
            'id', 'username', 'roles', 'password', 'user', 'guestCode'
        ];

        foreach($attributes as $attr) {
            $this->assertClassHasAttribute($attr, Guest::class);
        }

        // Make sure that no other attribute exists
        $guest = new Guest;
        $this->assertCount(count($attributes), (array) $guest);
    }

    public function testInitialValues(): void
    {
        $guest = new Guest;

        $this->assertNull($guest->getId());
        $this->assertEquals("", $guest->getUsername());
        $this->assertEquals("", $guest->getUserIdentifier());
        $this->assertCount(1, $guest->getRoles());
        $this->assertEquals("ROLE_GUEST", $guest->getRoles()[0]);
        $this->assertNull($guest->getSalt());
        $this->assertNull($guest->getUser());
        $this->assertNull($guest->getGuestCode());
        // Doing this one last to expect exception for getPassword only (since password should not be null)
        $this->expectException(TypeError::class);
        $this->assertNull($guest->getPassword());
    }

    public function testGettersSetters(): void
    {
        $guest = new Guest;
        $user = new User;
        $guestCode = new GuestCode;

        $guest->setUsername("setUsername")
               ->setPassword("setPassword")
               ->setUser($user)
               ->setGuestCode($guestCode);

        // This should do nothing
        $guest->eraseCredentials();
        
        $this->assertEquals("setUsername", $guest->getUsername());
        $this->assertEquals("setUsername", $guest->getUserIdentifier());
        $this->assertEquals("setPassword", $guest->getPassword());
        $this->assertNotNull($guest->getUser());
        $this->assertInstanceOf(User::class, $guest->getUser());
        $this->assertNotNull($guest->getGuestCode());
        $this->assertInstanceOf(GuestCode::class, $guest->getGuestCode());
    }

    public function testRoles(): void
    {
        $guest = new Guest;

        $this->assertCount(1, $guest->getRoles());
        $guest->setRoles(["ROLE_1", "ROLE_2"]);
        $this->assertCount(3, $guest->getRoles());
        $guest->setRoles(["ROLE_3"]);
        $this->assertCount(2, $guest->getRoles());
        $guest->setRoles([]);
        $this->assertCount(1, $guest->getRoles());
    }
}