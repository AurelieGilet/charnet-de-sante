<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use App\Entity\HealthCare;
use App\Entity\Cat;

final class HealthCareCareTest extends TestCase
{
    public function testAttributes(): void
    {
        $attributes = [
            'id', 'cat', 'date', 'endDate', 'vaccine',
            'injectionSite', 'dewormer', 'parasite', 'treatment',
            'productName', 'dosage', 'descaling'
        ];

        foreach($attributes as $attr) {
            $this->assertClassHasAttribute($attr, HealthCare::class);
        }

        // Make sure that no other attribute exists
        $healthCare = new HealthCare;
        $this->assertCount(count($attributes), (array) $healthCare);
    }

    public function testInitialValues(): void
    {
        $healthCare = new HealthCare;

        $this->assertNull($healthCare->getId());
        $this->assertNull($healthCare->getCat());
        $this->assertNull($healthCare->getDate());
        $this->assertNull($healthCare->getEndDate());
        $this->assertNull($healthCare->getVaccine());
        $this->assertNull($healthCare->getInjectionSite());
        $this->assertNull($healthCare->getDewormer());
        $this->assertNull($healthCare->getParasite());
        $this->assertNull($healthCare->getTreatment());
        $this->assertNull($healthCare->getProductName());
        $this->assertNull($healthCare->getDosage());
        $this->assertNull($healthCare->getDescaling());
    }

    public function testGettersSetters(): void
    {
        $healthCare = new HealthCare;
        $cat = new Cat;        

        $healthCare->setCat($cat)
                   ->setDate(new DateTime(date('Y-m-d', 1151712000)))
                   ->setEndDate(new DateTime(date('Y-m-d', 1625097600)))
                   ->setVaccine("setVaccine")
                   ->setInjectionSite("setInjectionSite")
                   ->setDewormer(true)
                   ->setParasite("setParasite")
                   ->setTreatment("setTreatment")
                   ->setProductName("setProductName")
                   ->setDosage("setDosage")
                   ->setDescaling("setDescaling");
        
        $this->assertNotNull($healthCare->getCat());
        $this->assertInstanceOf(Cat::class, $healthCare->getCat());
        $this->assertInstanceOf(DateTime::class, $healthCare->getDate());
        $this->assertEquals(new DateTime(date('Y-m-d', 1151712000)), $healthCare->getDate());
        $this->assertInstanceOf(DateTime::class, $healthCare->getEndDate());
        $this->assertEquals(new DateTime(date('Y-m-d', 1625097600)), $healthCare->getEndDate());
        $this->assertEquals("setVaccine", $healthCare->getVaccine());
        $this->assertEquals("setInjectionSite", $healthCare->getInjectionSite());
        $this->assertTrue($healthCare->getDewormer());
        $this->assertEquals("setParasite", $healthCare->getParasite());
        $this->assertEquals("setTreatment", $healthCare->getTreatment());
        $this->assertEquals("setProductName", $healthCare->getProductName());
        $this->assertEquals("setDosage", $healthCare->getDosage());
        $this->assertEquals("setDescaling", $healthCare->getDescaling());
    }
}