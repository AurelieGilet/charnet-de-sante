<?php

namespace App\DataFixtures;

use DateTime;
use DateInterval;
use App\Entity\Measure;
use App\Repository\CatRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MeasureFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(CatRepository $catRepository)
    {
        $this->catRepository = $catRepository;
    }
    
    public function load(ObjectManager $manager)
    {
        $cats = $this->catRepository->findAll();
        $femaleCats = $this->catRepository->findBy(['sexe' => 'F']);

        $currentDate = new DateTime(date('Y-m-d'));

        $weightDates = [$currentDate->format('Y-m-d')];
        for ($i = 1; $i < 10; $i++) {
            $number = 7 * $i;
            $newDate = $currentDate->sub(new DateInterval('P'.$number.'D'))->format('Y-m-d');
            array_push($weightDates, $newDate);
        }

        $temperatureDates = [$currentDate->format('Y-m-d')];
        for ($i = 1; $i < 10; $i++) {
            $number = 1 * $i;
            $newDate = $currentDate->sub(new DateInterval('P'.$number.'D'))->format('Y-m-d');
            array_push($temperatureDates, $newDate);
        }

        $heatDates = [$currentDate->sub(new DateInterval('P12D'))->format('Y-m-d')];
        for ($i = 1; $i < 5; $i++) {
            $number = 12 * $i + 15;
            $newDate = $currentDate->sub(new DateInterval('P'.$number.'D'))->format('Y-m-d');
            array_push($heatDates, $newDate);
        }

        $heatEndDates = [];
        for ($i = 0; $i < 5; $i++) {
            $newDate = date('Y-m-d', strtotime($heatDates[$i].'+ 12 days'));
            array_push($heatEndDates, $newDate);
        }

        // Weight fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 10; $j++) {
                $measure = new Measure;

                $measure->setCat($cats[$i])
                        ->setDate(new DateTime($weightDates[$j]))
                        ->setWeight(mt_rand(35, 50) / 10);

                $manager->persist($measure);
            }
        }

        // Temperature Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 10; $j++) {
                $measure = new Measure;

                $measure->setCat($cats[$i])
                        ->setDate(new DateTime($temperatureDates[$j]))
                        ->setTemperature(mt_rand(380, 400) / 10);

                $manager->persist($measure);
            }
        }

        // Heat Fixtures
        for ($i = 0; $i < count($cats); $i++) {
            for ($j = 0; $j < 5; $j++) {
                if (in_array($cats[$i], $femaleCats)) {
                    $measure = new Measure;

                    $measure->setCat($cats[$i])
                            ->setDate(new DateTime($heatDates[$j]))
                            ->setHeatEndDate(new DateTime($heatEndDates[$j]))
                            ->setIsInHeat(true)
                            ->setIsMated(mt_rand(0, 1))
                            ->setIsPregnant(false);
                    
                    $manager->persist($measure);
                }
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
