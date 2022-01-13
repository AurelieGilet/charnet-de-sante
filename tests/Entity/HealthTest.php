<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use App\Entity\Health;
use App\Entity\Cat;

final class HealthTest extends TestCase
{
    public function testAttributes(): void
    {
        $attributes = [
            'id', 'cat', 'date', 'endDate', 'vetVisitMotif',
            'allergy', 'disease', 'wound', 'surgery', 'analysis',
            'details', 'documentName', 'document'
        ];

        foreach($attributes as $attr) {
            $this->assertClassHasAttribute($attr, Health::class);
        }

        // Make sure that no other attribute exists
        $health = new Health;
        $this->assertCount(count($attributes), (array) $health);
    }

    public function testInitialValues(): void
    {
        $health = new Health;

        $this->assertNull($health->getId());
        $this->assertNull($health->getCat());
        $this->assertNull($health->getDate());
        $this->assertNull($health->getEndDate());
        $this->assertNull($health->getVetVisitMotif());
        $this->assertNull($health->getAllergy());
        $this->assertNull($health->getDisease());
        $this->assertNull($health->getWound());
        $this->assertNull($health->getSurgery());
        $this->assertNull($health->getAnalysis());
        $this->assertNull($health->getDetails());
        $this->assertNull($health->getDocumentName());
        $this->assertNull($health->getDocument());
    }

    public function testGettersSetters(): void
    {
        $health = new Health;
        $cat = new Cat;        

        $health->setCat($cat)
               ->setDate(new DateTime(date('Y-m-d', 1151712000)))
               ->setEndDate(new DateTime(date('Y-m-d', 1625097600)))
               ->setVetVisitMotif("setVetVisitMotif")
               ->setAllergy("setAllergy")
               ->setDisease("setDisease")
               ->setWound("setWound")
               ->setSurgery("setSurgery")
               ->setAnalysis("setAnalysis")
               ->setDetails("setDetails")
               ->setDocumentName("setDocumentName")
               ->setDocument("setDocument");
        
        $this->assertNotNull($health->getCat());
        $this->assertInstanceOf(Cat::class, $health->getCat());
        $this->assertInstanceOf(DateTime::class, $health->getDate());
        $this->assertEquals(new DateTime(date('Y-m-d', 1151712000)), $health->getDate());
        $this->assertInstanceOf(DateTime::class, $health->getEndDate());
        $this->assertEquals(new DateTime(date('Y-m-d', 1625097600)), $health->getEndDate());
        $this->assertEquals("setVetVisitMotif", $health->getVetVisitMotif());
        $this->assertEquals("setAllergy", $health->getAllergy());
        $this->assertEquals("setDisease", $health->getDisease());
        $this->assertEquals("setWound", $health->getWound());
        $this->assertEquals("setSurgery", $health->getSurgery());
        $this->assertEquals("setAnalysis", $health->getAnalysis());
        $this->assertEquals("setDetails", $health->getDetails());
        $this->assertEquals("setDocumentName", $health->getDocumentName());
        $this->assertEquals("setDocument", $health->getDocument());
    }
}