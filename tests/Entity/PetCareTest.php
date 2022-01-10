<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use App\Entity\PetCare;
use App\Entity\Cat;

final class PetCareTest extends TestCase
{
    public function testAttributes(): void
    {
        $attributes = [
            'id', 'cat', 'date', 'endDate', 'foodType',
            'foodQuantity', 'foodBrand', 'grooming', 'claws', 'eyesEars',
            'teeth', 'litterbox', 'notes'
        ];

        foreach($attributes as $attr) {
            $this->assertClassHasAttribute($attr, PetCare::class);
        }

        // Make sure that no other attribute exists
        $petCare = new PetCare;
        $this->assertCount(count($attributes), (array) $petCare);
    }

    public function testInitialValues(): void
    {
        $petCare = new PetCare;

        $this->assertNull($petCare->getId());
        $this->assertNull($petCare->getCat());
        $this->assertNull($petCare->getDate());
        $this->assertNull($petCare->getEndDate());
        $this->assertNull($petCare->getFoodType());
        $this->assertNull($petCare->getFoodQuantity());
        $this->assertNull($petCare->getFoodBrand());
        $this->assertNull($petCare->getGrooming());
        $this->assertNull($petCare->getClaws());
        $this->assertNull($petCare->getEyesEars());
        $this->assertNull($petCare->getTeeth());
        $this->assertNull($petCare->getLitterbox());
        $this->assertNull($petCare->getNotes());
    }

    public function testGettersSetters(): void
    {
        $petCare = new PetCare;
        $cat = new Cat;        

        $petCare->setCat($cat)
               ->setDate(new DateTime(date('Y-m-d', 1151712000)))
               ->setEndDate(new DateTime(date('Y-m-d', 1625097600)))
               ->setFoodType("setFoodType")
               ->setFoodQuantity(42)
               ->setFoodBrand("setFoodBrand")
               ->setGrooming("setGrooming")
               ->setClaws(true)
               ->setEyesEars("setEyesEars")
               ->setTeeth("setTeeth")
               ->setLitterbox(true)
               ->setNotes("setNotes");
        
        $this->assertNotNull($petCare->getCat());
        $this->assertInstanceOf(Cat::class, $petCare->getCat());
        $this->assertInstanceOf(DateTime::class, $petCare->getDate());
        $this->assertEquals(new DateTime(date('Y-m-d', 1151712000)), $petCare->getDate());
        $this->assertInstanceOf(DateTime::class, $petCare->getEndDate());
        $this->assertEquals(new DateTime(date('Y-m-d', 1625097600)), $petCare->getEndDate());
        $this->assertEquals("setFoodType", $petCare->getFoodType());
        $this->assertEquals(42, $petCare->getFoodQuantity());
        $this->assertEquals("setFoodBrand", $petCare->getFoodBrand());
        $this->assertEquals("setGrooming", $petCare->getGrooming());
        $this->assertTrue($petCare->getClaws());
        $this->assertEquals("setEyesEars", $petCare->getEyesEars());
        $this->assertEquals("setTeeth", $petCare->getTeeth());
        $this->assertTrue($petCare->getLitterbox());
        $this->assertEquals("setNotes", $petCare->getNotes());
    }
}