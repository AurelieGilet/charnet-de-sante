<?php

namespace App\DataFixtures;

use App\Entity\FAQ;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class FAQFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $faq = new FAQ();

            $faq->setQuestion('Lorem ipsum dolor sit amet, consectetur adipiscing elit aenean posuere ?')
                ->setAnswer('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras venenatis mattis lorem, at euismod nisl tempus quis. Cras mauris ligula, varius ut dolor et, interdum semper odio. Nunc euismod urna vitae neque posuere, in tristique nulla malesuada. Curabitur laoreet orci velit, sed aliquet orci scelerisque vel. Sed vel risus dapibus, maximus magna nec, faucibus neque. Morbi fermentum lacinia ligula, vitae vestibulum arcu fermentum et. Aenean commodo elit vel lacus lobortis, eget sagittis augue cursus.');

            $manager->persist($faq);
        }

        $manager->flush();
    }
}
