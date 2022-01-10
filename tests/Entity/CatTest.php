<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use App\Entity\Address;
use App\Entity\Cat;
use App\Entity\Measure;
use App\Entity\PetCare;
use App\Entity\User;
use App\Entity\HealthCare;
use App\Entity\Health;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

final class CatTest extends TestCase
{
    public function testAttributes(): void
    {
        $attributes = [
            'id', 'owner', 'name', 'picture', 'sexe', 'race',
            'coat', 'isSterilized', 'dateOfBirth', 'dateOfDeath',
            'microchip', 'tattoo', 'ownerName', 'veterinaryName',
            'ownerAddress', 'veterinaryAddress', 'measures', 'petCares',
            'healthCares', 'healths'
        ];

        foreach($attributes as $attr) {
            $this->assertClassHasAttribute($attr, Cat::class);
        }

        // Make sure that no other attribute exists
        $cat = new Cat;
        $this->assertCount(count($attributes), (array) $cat);
    }

    public function testInitialValues(): void
    {
        $cat = new Cat;

        $this->assertNull($cat->getId());
        $this->assertNull($cat->getOwner());
        $this->assertNull($cat->getName());
        $this->assertNull($cat->getPicture());
        $this->assertNull($cat->getSexe());
        $this->assertNull($cat->getRace());
        $this->assertNull($cat->getCoat());
        $this->assertNull($cat->getIsSterilized());
        $this->assertNull($cat->getDateOfBirth());
        $this->assertNull($cat->getDateOfDeath());
        $this->assertNull($cat->getMicrochip());
        $this->assertNull($cat->getTattoo());
        $this->assertNull($cat->getOwnerName());
        $this->assertNull($cat->getVeterinaryName());
        $this->assertEmpty($cat->getOwnerAddress());
        $this->assertEmpty($cat->getVeterinaryAddress());
        $this->assertEmpty($cat->getMeasures());
        $this->assertEmpty($cat->getPetCares());
        $this->assertEmpty($cat->getHealthCares());
        $this->assertEmpty($cat->getHealths());
    }

    public function testGettersSetters(): void
    {
        $cat = new Cat;
        $user = new User;

        $cat->setOwner($user)
            ->setName("setName")
            ->setPicture("setPicture")
            ->setSexe("setSexe")
            ->setRace("setRace")
            ->setCoat("setCoat")
            ->setIsSterilized(true)
            ->setDateOfBirth(new DateTime(date('Y-m-d', 1151712000)))
            ->setDateOfDeath(new DateTime(date('Y-m-d', 1625097600)))
            ->setMicrochip("setMicrochip")
            ->setTattoo("setTattoo")
            ->setOwnerName("setOwnerName")
            ->setVeterinaryName("setVeterinaryName");
        
        $this->assertInstanceOf(User::class, $cat->getOwner());
        $this->assertEquals("setName", $cat->getName());
        $this->assertEquals("setPicture", $cat->getPicture());
        $this->assertEquals("setSexe", $cat->getSexe());
        $this->assertEquals("setRace", $cat->getRace());
        $this->assertEquals("setCoat", $cat->getCoat());
        $this->assertTrue($cat->getIsSterilized());
        $this->assertInstanceOf(DateTime::class, $cat->getDateOfBirth());
        $this->assertEquals(new DateTime(date('Y-m-d', 1151712000)), $cat->getDateOfBirth());
        $this->assertInstanceOf(DateTime::class, $cat->getDateOfDeath());
        $this->assertEquals(new DateTime(date('Y-m-d', 1625097600)), $cat->getDateOfDeath());
        $this->assertEquals("setMicrochip", $cat->getMicrochip());
        $this->assertEquals("setTattoo", $cat->getTattoo());
        $this->assertEquals("setOwnerName", $cat->getOwnerName());
        $this->assertEquals("setVeterinaryName", $cat->getVeterinaryName());
    }

    public function testAddresses(): void
    {
        $cat = new Cat;
        $address1 = new Address;
        $address2 = new Address;
        $address3 = new Address;

        $cat->addOwnerAddress($address1);
        $this->assertCount(1, $cat->getOwnerAddress());
        $cat->addOwnerAddress($address2);
        $this->assertCount(2, $cat->getOwnerAddress());
        $cat->removeOwnerAddress($address3);
        $this->assertCount(2, $cat->getOwnerAddress());
        $cat->removeOwnerAddress($address1);
        $this->assertCount(1, $cat->getOwnerAddress());
        $cat->removeOwnerAddress($address2);
        $this->assertEmpty($cat->getOwnerAddress());

        $cat->addVeterinaryAddress($address1);
        $this->assertCount(1, $cat->getVeterinaryAddress());
        $cat->addVeterinaryAddress($address2);
        $this->assertCount(2, $cat->getVeterinaryAddress());
        $cat->removeVeterinaryAddress($address3);
        $this->assertCount(2, $cat->getVeterinaryAddress());
        $cat->removeVeterinaryAddress($address1);
        $this->assertCount(1, $cat->getVeterinaryAddress());
        $cat->removeVeterinaryAddress($address2);
        $this->assertEmpty($cat->getVeterinaryAddress());
    }

    public function testMeasure(): void
    {
        $cat = new Cat;
        $mesure1 = new Measure;
        $mesure2 = new Measure;
        $mesure3 = new Measure;

        $cat->addMeasure($mesure1);
        $this->assertCount(1, $cat->getMeasures());
        $cat->addMeasure($mesure2);
        $this->assertCount(2, $cat->getMeasures());
        $cat->removeMeasure($mesure3);
        $this->assertCount(2, $cat->getMeasures());
        $cat->removeMeasure($mesure1);
        $this->assertCount(1, $cat->getMeasures());
        $cat->removeMeasure($mesure2);
        $this->assertEmpty($cat->getMeasures());
    }
    
    public function testPetCare(): void
    {
        $cat = new Cat;
        $petcare1 = new PetCare;
        $petcare2 = new PetCare;
        $petcare3 = new PetCare;

        $cat->addPetCare($petcare1);
        $this->assertCount(1, $cat->getPetCares());
        $cat->addPetCare($petcare2);
        $this->assertCount(2, $cat->getPetCares());
        $cat->removePetCare($petcare3);
        $this->assertCount(2, $cat->getPetCares());
        $cat->removePetCare($petcare1);
        $this->assertCount(1, $cat->getPetCares());
        $cat->removePetCare($petcare2);
        $this->assertEmpty($cat->getPetCares());
    }
        
    public function testHealthCare(): void
    {
        $cat = new Cat;
        $healthcare1 = new HealthCare;
        $healthcare2 = new HealthCare;
        $healthcare3 = new HealthCare;

        $cat->addHealthCare($healthcare1);
        $this->assertCount(1, $cat->getHealthCares());
        $cat->addHealthCare($healthcare2);
        $this->assertCount(2, $cat->getHealthCares());
        $cat->removeHealthCare($healthcare3);
        $this->assertCount(2, $cat->getHealthCares());
        $cat->removeHealthCare($healthcare1);
        $this->assertCount(1, $cat->getHealthCares());
        $cat->removeHealthCare($healthcare2);
        $this->assertEmpty($cat->getHealthCares());
    }
            
    public function testHealth(): void
    {
        $cat = new Cat;
        $health1 = new Health;
        $health2 = new Health;
        $health3 = new Health;

        $cat->addHealth($health1);
        $this->assertCount(1, $cat->getHealths());
        $cat->addHealth($health2);
        $this->assertCount(2, $cat->getHealths());
        $cat->removeHealth($health3);
        $this->assertCount(2, $cat->getHealths());
        $cat->removeHealth($health1);
        $this->assertCount(1, $cat->getHealths());
        $cat->removeHealth($health2);
        $this->assertEmpty($cat->getHealths());
    }
}