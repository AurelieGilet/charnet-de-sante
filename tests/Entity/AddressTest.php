<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use App\Entity\Address;
use App\Entity\Cat;

final class AddressTest extends TestCase
{
    public function testAttributes(): void
    {
        $attributes = [
            'id', 'address1', 'address2', 'city', 'postalCode', 'phoneNumber',
            'ownerAddressCat', 'veterinaryAddressCat', 'email'
        ];

        foreach($attributes as $attr) {
            $this->assertClassHasAttribute($attr, Address::class);
        }

        // Make sure that no other attribute exists
        $address = new Address;
        $this->assertCount(count($attributes), (array) $address);
    }

    public function testInitialValues(): void
    {
        $address = new Address;

        $this->assertNull($address->getId());
        $this->assertNull($address->getAddress1());
        $this->assertNull($address->getAddress2());
        $this->assertNull($address->getCity());
        $this->assertNull($address->getPostalCode());
        $this->assertNull($address->getPhoneNumber());
        $this->assertNull($address->getOwnerAddressCat());
        $this->assertNull($address->getVeterinaryAddressCat());
        $this->assertNull($address->getEmail());
    }

    public function testGettersSetters(): void
    {
        $address = new Address;
        $cat = new Cat;        

        $address->setAddress1("setAddress1")
                ->setAddress2("setAddress2")
                ->setCity("setCity")
                ->setPostalCode("setPostalCode")
                ->setPhoneNumber("setPhoneNumber")
                ->setOwnerAddressCat($cat)
                ->setVeterinaryAddressCat($cat)
                ->setEmail("setEmail");
        
        $this->assertEquals("setAddress1", $address->getAddress1());
        $this->assertEquals("setAddress2", $address->getAddress2());
        $this->assertEquals("setCity", $address->getCity());
        $this->assertEquals("setPostalCode", $address->getPostalCode());
        $this->assertEquals("setPhoneNumber", $address->getPhoneNumber());
        $this->assertInstanceOf(Cat::class, $address->getOwnerAddressCat());
        $this->assertNotNull($address->getOwnerAddressCat());
        $this->assertInstanceOf(Cat::class, $address->getVeterinaryAddressCat());
        $this->assertNotNull($address->getVeterinaryAddressCat());
        $this->assertEquals("setEmail", $address->getEmail());
    }
}