<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use App\Entity\Measure;
use App\Entity\Cat;

final class MeasureTest extends TestCase
{
    public function testAttributes(): void
    {
        $attributes = [
            'id', 'cat', 'date', 'weight', 'temperature',
            'isInHeat', 'isMated', 'isPregnant', 'heatEndDate'
        ];

        foreach($attributes as $attr) {
            $this->assertClassHasAttribute($attr, Measure::class);
        }

        // Make sure that no other attribute exists
        $measure = new Measure;
        $this->assertCount(count($attributes), (array) $measure);
    }
    public function testInitialValues(): void
    {
        $measure = new Measure;

        $this->assertNull($measure->getId());
        $this->assertNull($measure->getCat());
        $this->assertNull($measure->getDate());
        $this->assertNull($measure->getWeight());
        $this->assertNull($measure->getTemperature());
        $this->assertNull($measure->getIsInHeat());
        $this->assertNull($measure->getIsMated());
        $this->assertNull($measure->getIsPregnant());
        $this->assertNull($measure->getHeatEndDate());
    }

    public function testGettersSetters(): void
    {
        $measure = new Measure;
        $cat = new Cat;        

        $measure->setCat($cat)
                ->setDate(new DateTime(date('Y-m-d', 1151712000)))
                ->setWeight("setWeight")
                ->setTemperature("setTemperature")
                ->setIsInHeat(true)
                ->setIsMated(true)
                ->setIsPregnant(true)
                ->setHeatEndDate(new DateTime(date('Y-m-d', 1625097600)));
        
        $this->assertNotNull($measure->getCat());
        $this->assertInstanceOf(Cat::class, $measure->getCat());
        $this->assertInstanceOf(DateTime::class, $measure->getDate());
        $this->assertEquals(new DateTime(date('Y-m-d', 1151712000)), $measure->getDate());
        $this->assertEquals("setWeight", $measure->getWeight());
        $this->assertEquals("setTemperature", $measure->getTemperature());
        $this->assertTrue($measure->getIsInHeat());
        $this->assertTrue($measure->getIsMated());
        $this->assertTrue($measure->getIsPregnant());
        $this->assertEquals(new DateTime(date('Y-m-d', 1625097600)), $measure->getHeatEndDate());
        $this->assertInstanceOf(DateTime::class, $measure->getHeatEndDate());
    }
}