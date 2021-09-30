<?php

namespace App\DataFixtures;

use DateTime;
use DateInterval;
use App\Entity\Health;
use App\Repository\CatRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class HealthFixtures extends Fixture implements DependentFixtureInterface
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
            $number = 1;
            $newDate = $currentDate->sub(new DateInterval('P'.$number.'Y'))->format('Y-m-d');
            array_push($Dates, $newDate);
        }

        $endDates = [null];
        for ($i = 1; $i < 10; $i++) {
            $newDate = date('Y-m-d', strtotime($Dates[$i].'+ 7 days'));
            array_push($endDates, $newDate);            
        }

        $visitMotifs = ['Visite annuelle', 'Vaccination', 'Visite annuelle', 'Refuse sa nourriture et vomis sans arrêt', 'Visite annuelle', 'Vaccination', 'Visite annuelle', 'Visite annuelle', 'Piqué par un insecte', "Vaccination"];

        $allergyTypes = ['allergie aux puces', 'allergie alimentaire', 'allergie atopique', 'allergie de contact', 'autre'];

        $diseaseNames = ['acné', 'coryza', 'gastrite', 'asthme', 'conjonctivite', 'gingivite'];

        $woundTypes = ['attaque de chat', 'intoxication', 'piqûre d\'insecte', 'patte cassée', 'griffure', 'dent cassée'];

        $surgeryTypes = ['ostéotomie', 'exérèse de kyste', 'gastrotomie', 'réparation fracture', 'points de suture', 'stérilisation'];

        $analysisTypes = ['contrôle des reins', 'hématologie suite à vomissements répétés', 'analyse hormonale (thyroïde)', 'analyses biochimiques', 'test FeLV', 'contrôle des reins'];

        $documentNames = ['analyse de sang', 'echographie abdominale', 'résultats test FeLV', 'ordonnance anti-biotiques', 'radio de la patte', 'compte-rendu chirurgie'];

        // Veterinary visits Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 10; $j++) {
                $health = new Health(); 

                $health->setCat($cats[$i])
                       ->setDate(new DateTime($Dates[$j]))
                       ->setVetVisitMotif($visitMotifs[$j])
                    ;

                $manager->persist($health);
            }
        }

        // Allergies Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 6; $j++) {
                $rand_allergyTypes_keys = array_rand($allergyTypes, 1);

                $health = new Health(); 

                $health->setCat($cats[$i])
                       ->setDate(new DateTime($Dates[$j]))
                       ->setAllergy($allergyTypes[$rand_allergyTypes_keys])
                       ->setDetails('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus pulvinar pellentesque pharetra. Donec aliquet feugiat dictum. Aenean laoreet, sem a dictum aliquet, justo lacus eleifend tortor, eget auctor nunc leo fermentum enim.');

                       if ($j == 0) {
                            $health->setEndDate(null);
                       } else {
                            $health->setEndDate(new DateTime($endDates[$j]));
                       }

                $manager->persist($health);
            }
        }

        // Disease Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 6; $j++) {
                $health = new Health(); 

                $health->setCat($cats[$i])
                       ->setDate(new DateTime($Dates[$j]))
                       ->setDisease($diseaseNames[$j])
                       ->setDetails('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus pulvinar pellentesque pharetra. Donec aliquet feugiat dictum. Aenean laoreet, sem a dictum aliquet, justo lacus eleifend tortor, eget auctor nunc leo fermentum enim.');

                       if ($j == 0) {
                            $health->setEndDate(null);
                       } else {
                            $health->setEndDate(new DateTime($endDates[$j]));
                       }
                    
                $manager->persist($health);
            }
        }

        // Wounds Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 6; $j++) {
                $health = new Health(); 

                $health->setCat($cats[$i])
                       ->setDate(new DateTime($Dates[$j]))
                       ->setEndDate(new DateTime($endDates[$j]))
                       ->setWound($woundTypes[$j])
                       ->setDetails('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus pulvinar pellentesque pharetra. Donec aliquet feugiat dictum. Aenean laoreet, sem a dictum aliquet, justo lacus eleifend tortor, eget auctor nunc leo fermentum enim.');

                       if ($j == 0) {
                            $health->setEndDate(null);
                       } else {
                            $health->setEndDate(new DateTime($endDates[$j]));
                       }

                $manager->persist($health);
            }
        }

        // Surgery Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 6; $j++) {
                $health = new Health(); 

                $health->setCat($cats[$i])
                       ->setDate(new DateTime($Dates[$j]))
                       ->setSurgery($surgeryTypes[$j])
                       ->setDetails('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus pulvinar pellentesque pharetra. Donec aliquet feugiat dictum. Aenean laoreet, sem a dictum aliquet, justo lacus eleifend tortor, eget auctor nunc leo fermentum enim.')
                    ;

                $manager->persist($health);
            }
        }

        // Analysis Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 6; $j++) {
                $health = new Health(); 

                $health->setCat($cats[$i])
                       ->setDate(new DateTime($Dates[$j]))
                       ->setAnalysis($analysisTypes[$j])
                       ->setDetails('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus pulvinar pellentesque pharetra. Donec aliquet feugiat dictum. Aenean laoreet, sem a dictum aliquet, justo lacus eleifend tortor, eget auctor nunc leo fermentum enim.')
                    ;

                $manager->persist($health);
            }
        }

        // Documents Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 6; $j++) {
                $health = new Health(); 

                $health->setCat($cats[$i])
                       ->setDate(new DateTime($Dates[$j]))
                       ->setDocumentName($documentNames[$j])
                    ;

                $manager->persist($health);
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
