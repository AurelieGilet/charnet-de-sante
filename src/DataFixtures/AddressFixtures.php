<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\DataFixtures\CatFixtures;
use App\Repository\CatRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AddressFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(CatRepository $catRepository)
    {
        $this->catRepository = $catRepository;
    }

    public function load(ObjectManager $manager)
    {     
        $addresses1 = ['10 Boulevard du Nord', '13 rue des Champions', '2 Route de l\'Océan', '45 Route d\'Ancrage', '5 Chemin du Clair de Lune', '12 Rue de Clarté', '25 Rue des Olives', '3 Boulevard de la Terre de Fer'];
        $addresses2 = ['Appartement 5', 'Clinique des Champions', '', '', 'La Ferme aux Chats', '', '3ème étage', ''];
        $cities = ['Strasbourg', 'Strasbourg', 'Sainte-Foy', 'Les Sables-d\'Olonne', 'Lanteuil', 'Brive-la-Gaillarde', 'Tours', 'Tours'];
        $postalCodes = ['67000', '67000', '85150', '85340', '19190', '19100', '37000', '37000'];
        $phoneNumbers = ['0388623266', '0388116649', '0255321198', '0251657346', '0555369813', '0555659815', '0247326895', '0247132569'];

        for ($i = 2; $i < 6; $i++) {
            $userCats = $this->catRepository->findBy(['owner' => $i]);

            for ($j = 1; $j <= count($userCats); $j++ ){
                $ownerAddress = new Address;

                $ownerAddress->setAddress1($addresses1[$i * 2 - 4])
                        ->setAddress2($addresses2[$i * 2 - 4])
                        ->setCity($cities[$i * 2 - 4])
                        ->setPostalCode($postalCodes[$i * 2 - 4])
                        ->setPhoneNumber($phoneNumbers[$i * 2 - 4])
                        ->setEmail('owner'.$i.'@mail.com')
                        ->setOwnerAddressCat($this->getReference('FakeUser'.$i.'-Cat'.$j));

      
                $manager->persist($ownerAddress);

                $vetAddress = new Address;

                $vetAddress->setAddress1($addresses1[$i * 2 - 3])
                        ->setAddress2($addresses2[$i * 2 - 3])
                        ->setCity($cities[$i * 2 - 3])
                        ->setPostalCode($postalCodes[$i * 2 - 3])
                        ->setPhoneNumber($phoneNumbers[$i * 2 - 3])
                        ->setEmail('vet'.$i.'@mail.com')
                        ->setVeterinaryAddressCat($this->getReference('FakeUser'.$i.'-Cat'.$j));
                
                $manager->persist($vetAddress);
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
