<?php

namespace App\DataFixtures;

use App\Entity\Guest;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class GuestFixtures extends Fixture
{
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function load(ObjectManager $manager)
    {
        $users = $this->userRepository->findAll();

        for ($i = 1; $i < count($users); $i++) {
            $guest = new Guest();

            $guest->setUser($users[$i])
                  ->setUsername('User-'.$users[$i]->getId().'-Guest')
                  ->setPassword('000000000')
                  ->setRoles(['ROLE_GUEST']);

            $manager->persist($guest);
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
