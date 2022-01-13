<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    PRIVATE $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            if ($i === 0 ) {
                $user = new User();

                $user->setEmail('admin@mail.com')
                     ->setPassword($this->passwordHasher->hashPassword(
                    $user,'Password0'))
                     ->setRoles(['ROLE_ADMIN'])
                     ->setUsername('Chadmin'); 

                $manager->persist($user);   
                
            } else {
                $user = new User();

                $user->setEmail('fakeuser'.$i.'@mail.com')
                     ->setPassword($this->passwordHasher->hashPassword(
                    $user,'Password'.$i))
                     ->setRoles(['ROLE_USER'])
                     ->setUsername('FakeUser'.$i);
                     
                $manager->persist($user);
            }
        }

        $manager->flush();
    }
}
