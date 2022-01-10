<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use App\Entity\FAQ;

final class FAQTest extends TestCase
{
    public function testAttributes(): void
    {
        $attributes = [
            'id', 'question', 'answer'
        ];

        foreach($attributes as $attr) {
            $this->assertClassHasAttribute($attr, FAQ::class);
        }

        // Make sure that no other attribute exists
        $faq = new FAQ;
        $this->assertCount(count($attributes), (array) $faq);
    }

    public function testInitialValues(): void
    {
        $faq = new FAQ;

        $this->assertNull($faq->getId());
        $this->assertNull($faq->getQuestion());
        $this->assertNull($faq->getAnswer());
    }

    public function testGettersSetters(): void
    {
        $faq = new FAQ;

        $faq->setQuestion("setQuestion")
            ->setAnswer("setAnswer");
        
        $this->assertEquals("setQuestion", $faq->getQuestion());
        $this->assertEquals("setAnswer", $faq->getAnswer());
    }
}