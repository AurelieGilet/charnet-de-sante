<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Cat;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CatFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $sexes = ['F', 'M'];
        $races = ['European Shorthair', 'Siamois', 'Chartreux', 'Persan', 'American Shorthair', 'Maine Coon', 'Norvegien', 'Sphynx' , 'Ragdoll', 'Sacré de Birmanie'];
        $coats = ['noir', 'blanc', 'bleu', 'chocolat', 'lilas', 'cannelle', 'fauve', 'roux', 'crème', 'ambre'];        
        $owner_names = ['', 'Mr Nicolas Martin', 'Mme Isabelle Bernard', 'Mr Olivier Girard', 'Mme Christelle Poirier'];
        $veterinary_names = ['', 'Dr Jeoffroi Louineaux', 'Dr Hortense Saurel', 'Dr Franck Ducharme', 'Dr Jeanne Vernadeau'];

        for ($i = 2; $i <= 5; $i++) {
            for ($j = 0; $j <= mt_rand(1,4); $j++) {
                $catNumber = $j + 1;
                $rand_sexes_keys = array_rand($sexes, 1);
                $rand_races_keys = array_rand($races, 1);
                $rand_coats_keys = array_rand($coats, 1);
                $random_dateOfBirth = mt_rand(1151712000, 1625097600);

                $random_microchip = '';
                for ($k = 0; $k < 15; $k++) {
                    $random_microchip .= mt_rand(1,9);
                }

                $random_tattoo = '';
                for ($k = 0; $k < 3; $k++) {
                    $random_tattoo .= chr(rand(65,90));
                }
                for ($k = 0; $k < 3; $k++) {
                    $random_tattoo .= mt_rand(1,9);
                }

                $cat = new Cat;

                $cat->setOwner($this->getReference('User_'.$i))
                    ->setName('FakeUser'.$i.'-Cat'.$catNumber)
                    ->setSexe($sexes[$rand_sexes_keys])
                    ->setRace($races[$rand_races_keys])
                    ->setCoat($coats[$rand_coats_keys])
                    ->setDateOfBirth(new DateTime(date('Y-m-d', $random_dateOfBirth)))
                    ->setMicrochip($random_microchip)
                    ->setTattoo($random_tattoo)
                    ->setOwnerName($owner_names[$i - 1])
                    ->setVeterinaryName($veterinary_names[$i - 1]);
                    
                    $manager->persist($cat);

                    $this->addReference('FakeUser'.$i.'-Cat'.$catNumber, $cat);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
