<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Entity\Address;
use App\Form\CatFormType;
use App\Form\AddressFormType;
use App\Form\DeleteCatFormType;
use App\Repository\CatRepository;
use App\Form\EditCatPictureFormType;
use App\Repository\HealthRepository;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatController extends AbstractController
{
    private function isRouteSecure($className, $user, $cat) {
        if ($className == "App\Entity\User") {
            if ($cat->getOwner() == $user) {
                return true;
            }
        } elseif ($className == "App\Entity\Guest") {
            if ($cat->getId() == $user->getGuestCode()->getCat()->getId()) {
                return true;
            }
        }
    }

    /**
     * @Route("/espace-utilisateur/liste-chats", name="cat-list")
     */
    public function catList(CatRepository $catRepository): Response
    {
        $user = $this->getUser();

        $cats = $catRepository->findBy(['owner' => $user]);

        return $this->render('cat-interface/cat_list.html.twig', [
            'controller_name' => 'CatController',
            'cats' => $cats,
        ]);
    }

     /**
     * @Route("espace-utilisateur/ajouter-un-chat", name="add-cat")
     */
    public function addCat(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $cat = new Cat;

        $form = $this->createForm(CatFormType::class, $cat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cat->setOwner($user);

            $manager->persist($cat);
            $manager->flush($cat);

            $this->addFlash('success', "Le chat ". $cat->getName() . " a bien été ajouté");

            return $this->redirectToRoute('cat-list');
        }

        return $this->render('cat-interface/add_edit_cat_info.html.twig', [
            'controller_name' => 'CatController',
            'catForm' => $form->createView()
        ]);
    }

    /**
     * @Route("espace-utilisateur/chat/{catId}/supprimer-un-chat", name="delete-cat")
     */
    public function deleteCat(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null): Response
    {
        $user = $this->getUser();
        $userPassword = $user->getPassword();

        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);
        $catName = $cat->getName();
        $picture = $cat->getPicture();
        $documents = $healthRepository->findCatFilenames($cat);

        $form = $this->createForm(DeleteCatFormType::class, $user, [
            'action' => $this->generateUrl('delete-cat', ['catId' => $cat->getId() ]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enteredPassword = $form['password']->getData();

            if (password_verify($enteredPassword, $userPassword)) {

                $manager->remove($cat);
                $manager->flush($cat);

                $filesystem = new Filesystem();
                $filesystem->remove($this->getParameter('images_directory') . '/' . $picture);
                for ($i=0; $i < count($documents); $i++) { 
                    $filesystem->remove($this->getParameter('files_directory') . '/' . $documents[$i]['document']);
                }

                $this->addFlash('success', "Le chat ". $catName . " a bien été supprimé");

                return $this->redirectToRoute('cat-list');

            } else {
                $this->addFlash('danger', "Le chat ". $catName . " n'a pas été supprimé. Le mot de passe ne correspond pas.");

                return $this->redirectToRoute('cat-list');
            }
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "Le chat ". $catName . " n'a pas été supprimé. Vous devez confirmez par mot de passe.");

            return $this->redirectToRoute('cat-list', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/_delete_cat.html.twig', [
            'controller_name' => 'CatController',
            'deleteCatForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}", name="cat-detail")
     * @Route("/espace-veterinaire/chat/{id}", name="veterinary-cat-detail")
     */
    public function catDetail(Cat $cat, AddressRepository $addressRepository): Response
    {
        if ($this->getUser()) {
            $user = $this->getUser();

            $className = get_class($user);

            if (!$this->isRouteSecure($className, $user, $cat) ) {
                if ($className == "App\Entity\User") {
                    $this->addFlash('danger', "Vous n'avez pas accès à cette fiche");
                    return $this->redirectToRoute('cat-list', ['id' => $user->getId() ]);
                } elseif ($className == "App\Entity\Guest") {
                    $this->addFlash('danger', "Vous n'avez pas accès à cette fiche");
                    return $this->redirectToRoute('homepage');
                } 
            }
        }
        
        $microchip = $cat->getMicrochip();
        $regex = '/([0-9]{3})([0-9]{2})([0-9]{2})([0-9]{8})/';
        $replacement = "$1-$2-$3-$4";  
        $microchip = preg_replace($regex, $replacement, $microchip);

        $replacement2 = "$1.$2.$3.$4.$5";
        $regex2 = '/([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/';
        $ownerPhone = '';
        $veterinaryPhone = '';
        if ($addressRepository->findOneBy(['ownerAddressCat' => $cat]) != null) {
            $ownerPhone =  $addressRepository->findOneBy(['ownerAddressCat' => $cat])->getPhoneNumber();
            $ownerPhone = preg_replace($regex2, $replacement2, $ownerPhone);
        }
        if ($addressRepository->findOneBy(['veterinaryAddressCat' => $cat]) != null) {
            $veterinaryPhone =  $addressRepository->findOneBy(['veterinaryAddressCat' => $cat])->getPhoneNumber();
            $veterinaryPhone = preg_replace($regex2, $replacement2, $veterinaryPhone);
        }
        
        $this->container->get('session')->set('cat', $cat);

        return $this->render('cat-interface/cat_detail.html.twig', [
            'controller_name' => 'CatController',
            'cat' => $cat,
            'microchip' => $microchip,
            'ownerPhone' => $ownerPhone,
            'veterinaryPhone' => $veterinaryPhone,
        ]);
    }

    /**
     * @Route("espace-utilisateur/chat/{id}/editer-infos", name="edit-cat-info")
     */
    public function editCatInfos(Request $request, EntityManagerInterface $manager, Cat $cat): Response
    {
        $form = $this->createForm(CatFormType::class, $cat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($cat);
            $manager->flush($cat);

            $this->addFlash('success', "Le chat ". $cat->getName() . " a bien été modifié");

            return $this->redirectToRoute('cat-detail', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/add_edit_cat_info.html.twig', [
            'controller_name' => 'CatController',
            'catForm' => $form->createView(),
            'cat' => $cat,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/editer-photo", name="edit-cat-picture", methods={"POST"}, options={"expose"=true})
     */
    public function editCatPicture(Request $request, CatRepository $catRepository, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $oldCat = $this->container->get('session')->get('cat');
        $oldPicture = $oldCat->getPicture();

        $catId = $request->attributes->get('_route_params');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $form = $this->createForm(EditCatPictureFormType::class, $cat);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            $file = $_FILES['file'];
            $file = new UploadedFile($file['tmp_name'], $file['name'], $file['type']);

            if (filesize($file) <= 2000000) {
                if ($oldPicture) {
                    $filesystem = new Filesystem();
                    $filesystem->remove($this->getParameter('images_directory') . '/' . $oldPicture);
                }

                $filename = $slugger->slug($cat->getId()) . '-' . uniqid() . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('images_directory'),
                    $filename
                );

                $cat->setPicture($filename);

                $manager->persist($cat);
                $manager->flush();

                $this->addFlash('success', "La photo de votre chat a été ajoutée");
                
                return new JsonResponse();
                
            } else {
                $this->addFlash('danger', "La photo de votre chat n'a pas été modifiée. L'image doit faire moins de 2 Mo.");

                return new JsonResponse(415);
            }           
        } 

        return $this->render('cat-interface/_edit_cat_picture_form.html.twig', [
            'controller_name' => 'CatController',
            'pictureForm' => $form->createView()
        ]);
    }

    /**
     * @Route("espace-utilisateur/chat/{id}/editer-adresse-proprietaire", name="edit-cat-owner-address")
     */
    public function editCatOwnerAddress(Request $request, EntityManagerInterface $manager, AddressRepository $addressRepository, Cat $cat): Response
    {
        $address = new Address;

        if ($addressRepository->findOneBy(['ownerAddressCat' => $cat]) != null) {
            $address = $addressRepository->findOneBy(['ownerAddressCat' => $cat]);
        }

        $form = $this->createForm(AddressFormType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $address->setOwnerAddressCat($cat);

            $manager->persist($address);
            $manager->flush($address);

            $this->addFlash('success', "L'adresse du propriétaire du chat " . $cat->getName() . " a bien été enregistrée");

            return $this->redirectToRoute('cat-detail', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/add_edit_cat_address.html.twig', [
            'controller_name' => 'CatController',
            'addressForm' => $form->createView(),
            'cat' => $cat,
        ]);
    }

    /**
     * @Route("espace-utilisateur/chat/{id}/editer-adresse-veterinaire", name="edit-cat-veterinary-address")
     */
    public function editCatVeterinaryAddress(Request $request, EntityManagerInterface $manager, AddressRepository $addressRepository, Cat $cat): Response
    {
        $address = new Address;

        if ($addressRepository->findOneBy(['veterinaryAddressCat' => $cat]) != null) {
            $address = $addressRepository->findOneBy(['veterinaryAddressCat' => $cat]);
        }

        $form = $this->createForm(AddressFormType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $address->setVeterinaryAddressCat($cat);

            $manager->persist($address);
            $manager->flush($address);

            $this->addFlash('success', "L'adresse du vétérinaire du chat " . $cat->getName() . " a bien été enregistrée");

            return $this->redirectToRoute('cat-detail', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/add_edit_cat_address.html.twig', [
            'controller_name' => 'CatController',
            'addressForm' => $form->createView(),
            'cat' => $cat,
        ]);
    }
}
