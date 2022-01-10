<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use App\Entity\User;
use App\Entity\Cat;
use App\Entity\Guest;
use App\Entity\GuestCode;

final class UserTest extends TestCase
{
    public function testAttributes(): void
    {
        $attributes = [
            'id', 'email', 'roles', 'password', 'username', 'picture',
            'cats', 'guest', 'guestCode'
        ];

        foreach($attributes as $attr) {
            $this->assertClassHasAttribute($attr, User::class);
        }

        // Make sure that no other attribute exists
        $user = new User;
        $this->assertCount(count($attributes), (array) $user);
    }

    public function testInitialValues(): void
    {
        $user = new User;

        $this->assertNull($user->getId());
        $this->assertNull($user->getEmail());        
        $this->assertEquals("", $user->getUserIdentifier());
        $this->assertEquals("", $user->getUsername());
        $this->assertCount(1, $user->getRoles());
        $this->assertEquals("ROLE_USER", $user->getRoles()[0]);
        $this->assertNull($user->getSalt());
        $this->assertNull($user->getPicture());
        $this->assertEmpty($user->getCats());
        $this->assertNull($user->getGuest());
        $this->assertNull($user->getGuestCode());        
        // Doing this one last to expect exception for getPassword only (since password should not be null)
        $this->expectException(TypeError::class);
        $this->assertNull($user->getPassword());
    }

    public function testGettersSetters(): void
    {
        $user = new User;
        $guest = new Guest;
        $guestCode = new GuestCode;

        $user->setEmail("setEmail")
              ->setPassword("setPassword")
              ->setUsername("setUsername")
              ->setPicture("setPicture")
              ->setGuest($guest)
              ->setGuestCode($guestCode);

        // This should do nothing
        $user->eraseCredentials();
        
        $this->assertEquals("setEmail", $user->getEmail());
        $this->assertEquals("setUsername", $user->getUsername());
        $this->assertEquals("setEmail", $user->getUserIdentifier());
        $this->assertEquals("setPassword", $user->getPassword());
        $this->assertEquals("setPicture", $user->getPicture());
        $this->assertNotNull($user->getGuest());
        $this->assertInstanceOf(Guest::class, $user->getGuest());
        $this->assertNotNull($user->getGuestCode());
        $this->assertInstanceOf(GuestCode::class, $user->getGuestCode());
    }

    public function testRoles(): void
    {
        $user = new User;

        $this->assertCount(1, $user->getRoles());
        $user->setRoles(["ROLE_1", "ROLE_2"]);
        $this->assertCount(3, $user->getRoles());
        $user->setRoles(["ROLE_3"]);
        $this->assertCount(2, $user->getRoles());
        $user->setRoles([]);
        $this->assertCount(1, $user->getRoles());
    }
    
    public function testCats(): void {
        $user = new User;

        $cat1 = new Cat;
        $cat2 = new Cat;
        $cat3 = new Cat;

        $user->addCat($cat1);
        $this->assertCount(1, $user->getCats());
        $user->addCat($cat2);
        $this->assertCount(2, $user->getCats());
        $user->removeCat($cat3);
        $this->assertCount(2, $user->getCats());
        $user->removeCat($cat1);
        $this->assertCount(1, $user->getCats());
        $user->removeCat($cat2);
        $this->assertEmpty($user->getCats());
    }
}