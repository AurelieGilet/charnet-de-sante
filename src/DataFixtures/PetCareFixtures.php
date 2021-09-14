<?php

namespace App\DataFixtures;


use DateTime;
use DateInterval;
use App\Entity\PetCare;
use App\Repository\CatRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PetCareFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(CatRepository $catRepository)
    {
        $this->catRepository = $catRepository;
    }
    
    public function load(ObjectManager $manager)
    {
        $cats = $this->catRepository->findAll();

        $currentDate = new DateTime(date('Y-m-d'));

        $Dates = [$currentDate->format('Y-m-d')];
        for ($i = 1; $i < 10; $i++) {
            $number = 15 * $i;
            $newDate = $currentDate->sub(new DateInterval('P'.$number.'D'))->format('Y-m-d');
            array_push($Dates, $newDate);
        }

        $EndDates = [null , $currentDate->format('Y-m-d')];
        for ($i = 1; $i < 9; $i++) {
            $number = 30 * $i;
            $newDate = $currentDate->sub(new DateInterval('P'.$number.'D'))->format('Y-m-d');
            array_push($EndDates, $newDate);            
        }

        $foodTypes = ['croquettes', 'pâtée'];

        $foodBrands = ['Miam', 'Croquettons', 'Frishty', 'Croquy', 'NomNom', 'Lickitty', 'Miaoulicious', 'Purrfection'];

        $grooming = ['brossage', 'bain'];

        $eyesEars = ['eyes', 'ears', 'both'];

        $teeth = ['brossage', 'dentifrice', 'algues'];

        $notes = ['Vomissements', 'A mangé un bout de plastique', 'Refuse sa nourriture', 'S\'est battu avec le chat du voisin', 'Diarrhée'];

        // Food Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 10; $j++) {
                $rand_foodTypes_keys = array_rand($foodTypes, 1);
                $rand_foodBrands_keys = array_rand($foodBrands, 1);

                $petCare = new PetCare;

                $petCare->setCat($cats[$i])
                        ->setDate(new DateTime($Dates[$j]))
                        ->setFoodType($foodTypes[$rand_foodTypes_keys])
                        ->setFoodBrand($foodBrands[$rand_foodBrands_keys])
                        ->setFoodQuantity(mt_rand(50,200))
                        ->setEndDate(new DateTime($EndDates[$j]));
                
                $manager->persist($petCare);
            }
        }

        // Grooming Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 10; $j++) {
                $rand_grooming_keys = array_rand($grooming, 1);

                $petCare = new PetCare;

                $petCare->setCat($cats[$i])
                        ->setDate(new DateTime($Dates[$j]))
                        ->setGrooming($grooming[$rand_grooming_keys]);
                
                $manager->persist($petCare);
            }
        }

        // Claws Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 10; $j++) {
                $petCare = new PetCare;

                $petCare->setCat($cats[$i])
                        ->setDate(new DateTime($Dates[$j]))
                        ->setClaws(true);
                
                $manager->persist($petCare);
            }
        }

        // Eyes and Ears Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 10; $j++) {
                $rand_eyesEars_keys = array_rand($eyesEars, 1);

                $petCare = new PetCare;

                $petCare->setCat($cats[$i])
                        ->setDate(new DateTime($Dates[$j]))
                        ->setEyesEars($eyesEars[$rand_eyesEars_keys]);
                
                $manager->persist($petCare);
            }
        }

        // Teeth Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 10; $j++) {
                $rand_teeth_keys = array_rand($teeth, 1);

                $petCare = new PetCare;

                $petCare->setCat($cats[$i])
                        ->setDate(new DateTime($Dates[$j]))
                        ->setTeeth($teeth[$rand_teeth_keys]);
                
                $manager->persist($petCare);
            }
        }

        // Litterbox Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 10; $j++) {
                $petCare = new PetCare;

                $petCare->setCat($cats[$i])
                        ->setDate(new DateTime($Dates[$j]))
                        ->setLitterbox(true);
                
                $manager->persist($petCare);
            }
        }

        // Notes Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 10; $j++) {
                $rand_notes_keys = array_rand($notes, 1);

                $petCare = new PetCare;

                $petCare->setCat($cats[$i])
                        ->setDate(new DateTime($Dates[$j]))
                        ->setNotes($notes[$rand_notes_keys]);
                
                $manager->persist($petCare);
            }
        }



        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CatFixtures::class,
        );
    }
}
