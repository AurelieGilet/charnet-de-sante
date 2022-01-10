<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use App\Entity\GuestCode;
use App\Entity\User;
use App\Entity\Guest;
use App\Entity\Cat;

final class GuestCodeTest extends TestCase
{
    public function testAttributes(): void
    {
        $attributes = [
            'id', 'user', 'guest', 'cat', 'code',
            'validity', 'isUsed'
        ];

        foreach($attributes as $attr) {
            $this->assertClassHasAttribute($attr, GuestCode::class);
        }

        // Make sure that no other attribute exists
        $guestCode = new GuestCode;
        $this->assertCount(count($attributes), (array) $guestCode);
    }

    public function testInitialValues(): void
    {
        $guestCode = new GuestCode;

        $this->assertNull($guestCode->getId());
        $this->assertNull($guestCode->getUser());
        $this->assertNull($guestCode->getGuest());
        $this->assertNull($guestCode->getCat());
        $this->assertNull($guestCode->getCode());
        $this->assertNull($guestCode->getValidity());
        $this->assertNull($guestCode->getIsUsed());
    }

    public function testGettersSetters(): void
    {
        $guestCode = new GuestCode;
        $user = new User;
        $guest = new Guest;
        $cat = new Cat;        

        $guestCode->setUser($user)
                  ->setGuest($guest)
                  ->setCat($cat)
                  ->setCode("setCode")
                  ->setValidity(new DateTime(date('Y-m-d', 1151712000)))
                  ->setIsUsed(true);
        
        $this->assertNotNull($guestCode->getUser());
        $this->assertInstanceOf(User::class, $guestCode->getUser());
        $this->assertNotNull($guestCode->getGuest());
        $this->assertInstanceOf(Guest::class, $guestCode->getGuest());
        $this->assertNotNull($guestCode->getCat());
        $this->assertInstanceOf(Cat::class, $guestCode->getCat());
        $this->assertEquals("setCode", $guestCode->getCode());
        $this->assertInstanceOf(DateTime::class, $guestCode->getValidity());
        $this->assertEquals(new DateTime(date('Y-m-d', 1151712000)), $guestCode->getValidity());
        $this->assertTrue($guestCode->getIsUsed());
    }
}