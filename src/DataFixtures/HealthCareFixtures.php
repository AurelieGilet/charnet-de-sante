<?php

namespace App\DataFixtures;

use DateTime;
use DateInterval;
use App\Entity\HealthCare;
use App\Repository\CatRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class HealthCareFixtures extends Fixture implements DependentFixtureInterface
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
        for ($i = 1; $i < 6; $i++) {
            $number = 3;
            $newDate = $currentDate->sub(new DateInterval('P'.$number.'Y'))->format('Y-m-d');
            array_push($Dates, $newDate);
        }

        $endDates = [];
        for ($i = 0; $i < 6; $i++) {
            $newDate = date('Y-m-d', strtotime($Dates[$i].'+ 7 days'));
            array_push($endDates, $newDate);            
        }

        $parasitesDates = [$currentDate->format('Y-m-d')];
        for ($i = 1; $i < 10; $i++) {
            $number = 1;
            $newDate = $currentDate->sub(new DateInterval('P'.$number.'Y'))->format('Y-m-d');
            array_push($parasitesDates, $newDate);
        }

        $vaccineNames = ['PureVax', 'Feligen', 'Nobivac', 'Versifel'];

        $vaccineTypes = ['typhus,coryza', 'leucose féline', 'rage', 'autre'];

        $vaccineInjectionSites = ['postérieur gauche', 'postérieur droit'];

        $dewormerNames = ['Biocanina', 'Vetocanis', 'Milbemax', 'Drontal'];

        $antiparasiteNames = ['Frontline', 'Advantage', 'Effipro', 'Fibrospot'];

        $parasiteTypes = ['puces', 'tiques', 'poux', 'gale', 'aoutats', 'teigne', 'autre'];

        $treatmentCauses = ['Fracture', 'Fracture', 'gastrite', 'gastrotomie', 'cystite', 'stérilisation'];

        $treatment = ['anti-inflammatoire', 'anti-biotique', 'pansement gastrique', 'anti-biotique', 'anti-biotique', 'anti-biotique'];

        $descaling = ['yes', 'yes', '1 dent arrachée'];

        // Vaccine Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 6; $j++) {
                $rand_vaccineNames_keys = array_rand($vaccineNames, 1);
                $rand_vaccineTypes_keys = array_rand($vaccineTypes, 1);
                $rand_vaccineInjectionSites_keys = array_rand($vaccineInjectionSites, 1);

                $healthCare = new HealthCare();

                $healthCare->setCat($cats[$i])
                           ->setDate(new Datetime($Dates[$j]))
                           ->setVaccine($vaccineTypes[$rand_vaccineTypes_keys])
                           ->setProductName($vaccineNames[$rand_vaccineNames_keys])
                           ->setInjectionSite($vaccineInjectionSites[$rand_vaccineInjectionSites_keys]);

                $manager->persist($healthCare);
            }
        }

        // Dewormer Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 10; $j++) {
                $rand_dewormerNames_keys = array_rand($dewormerNames, 1);

                $healthCare = new HealthCare();

                $healthCare->setCat($cats[$i])
                           ->setDate(new Datetime($parasitesDates[$j]))
                           ->setDewormer(true)
                           ->setProductName($dewormerNames[$rand_dewormerNames_keys])
                           ->setDosage('1 comprimé');

                $manager->persist($healthCare);
            }
        }

        // Antiparasite Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 10; $j++) {
                $rand_dewormerNames_keys = array_rand($antiparasiteNames, 1);
                $rand_parasiteTypes_keys = array_rand($parasiteTypes, 1);

                $healthCare = new HealthCare();

                $healthCare->setCat($cats[$i])
                           ->setDate(new Datetime($parasitesDates[$j]))
                           ->setParasite($parasiteTypes[$rand_parasiteTypes_keys])
                           ->setProductName($dewormerNames[$rand_dewormerNames_keys])
                           ->setDosage('1 pipette');

                $manager->persist($healthCare);
            }
        }

        // Treatment Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 6; $j++) {
                $healthCare = new HealthCare();

                $healthCare->setCat($cats[$i])
                           ->setDate(new Datetime($Dates[$j]))
                           ->setEndDate(new Datetime($endDates[$j]))
                           ->setTreatment($treatmentCauses[$j])
                           ->setProductName($treatment[$j])
                           ->setDosage('1 cachet / jour');

                $manager->persist($healthCare);
            }
        }

        // Descaling Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 6; $j++) {
                $rand_descaling_keys = array_rand($descaling, 1);

                $healthCare = new HealthCare();

                $healthCare->setCat($cats[$i])
                           ->setDate(new Datetime($Dates[$j]))
                           ->setDescaling($descaling[$rand_descaling_keys]);

                $manager->persist($healthCare);
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
