<?php

namespace App\Controller;

use DateTime;
use App\Entity\Cat;
use App\Entity\Health;
use App\Form\CatHealthFormType;
use App\Repository\CatRepository;
use App\Repository\HealthRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatHealthController extends AbstractController
{
    // This method is used in the different routes with ID in the URL to secure them and prevent users to access routes they don't have permission to.
    // There is 2 kind of users : the normal users and the guests. Users are limited to the pages concerning their own cats. Guests are limited to the pages concerning THE cat the users gave them access to. 
    private function isRouteSecure($className, $user, $cat) {
        if ($className == "App\Entity\User") {
            if ($cat->getOwner() != $user) {
                $this->addFlash('danger', "Vous n'avez pas accès à cette fiche");
                return $this->redirectToRoute('cat-list');
            }
        } elseif ($className == "App\Entity\Guest") {
            if ($cat->getId() != $user->getGuestCode()->getCat()->getId()) {
                $this->addFlash('danger', "Vous n'avez pas accès à cette fiche");
                return $this->redirectToRoute('homepage');
            }
        } 

        return null;
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante", name="cat-health")
     * @Route("/espace-veterinaire/chat/{id}/sante", name="veterinary-cat-health")
     */
    public function catHealth(Cat $cat): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        return $this->render('cat-interface/cat-health/cat_health.html.twig', [
            'controller_name' => 'CatHealthController',
            'cat' => $cat
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/visite-veterinaire", name="cat-vetVisit")
     * @Route("/espace-veterinaire/chat/{id}/sante/visite-veterinaire", name="veterinary-cat-vetVisit")
     */
    public function catVetVisit(Request $request, Cat $cat, HealthRepository $healthRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $healths = $healthRepository->findCatVetVisits($cat);

        $paginatedHealths = $paginator->paginate(
            $healths,
            $request->query->getInt('page', 1),
            5
        );
        $paginatedHealths->setParam('_fragment', 'last-entries'); // Intelephense indicate the method is undefined, but it works perfectly

        return $this->render('cat-interface/cat-health/cat_health_vet_visit.html.twig', [
            'controller_name' => 'CatHealthController',
            'cat' => $cat,
            'paginatedHealths' => $paginatedHealths,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/allergies", name="cat-allergy")
     * @Route("/espace-veterinaire/chat/{id}/sante/allergies", name="veterinary-cat-allergy")
     */
    public function catAllergy(Request $request, Cat $cat, HealthRepository $healthRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        // We create 2 sets of data : those that are finished (with end date < current date) and those that are ongoing.
        $currentDate = new DateTime();
        
        $healths = $healthRepository->findCatAllergies($cat, $currentDate);

        $paginatedHealths = $paginator->paginate(
            $healths,
            $request->query->getInt('page', 1),
            5
        );
        $paginatedHealths->setParam('_fragment', 'last-entries'); // Intelephense indicate the method is undefined, but it works perfectly

        $currentHealths = $healthRepository->findCatCurrentAllergies($cat, $currentDate);

        $paginatedCurrentHealths = $paginator->paginate(
            $currentHealths,
            $request->query->getInt('page2', 1),
            5,
            [
                'pageParameterName' => 'page2',
                'sortFieldParameterName' => 'sort2',
                'sortDirectionParameterName' => 'direction2',
            ]
        );

        return $this->render('cat-interface/cat-health/cat_health_allergy.html.twig', [
            'controller_name' => 'CatHealthController',
            'cat' => $cat,
            'paginatedHealths' => $paginatedHealths,
            'paginatedCurrentHealths' => $paginatedCurrentHealths,
            'currentHealths' => $currentHealths,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/maladies", name="cat-disease")
     * @Route("/espace-veterinaire/chat/{id}/sante/maladies", name="veterinary-cat-disease")
     */
    public function catDisease(Request $request, Cat $cat, HealthRepository $healthRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        // We create 2 sets of data : those that are finished (with end date < current date) and those that are ongoing.
        $currentDate = new DateTime();
        
        $healths = $healthRepository->findCatDiseases($cat, $currentDate);

        $paginatedHealths = $paginator->paginate(
            $healths,
            $request->query->getInt('page', 1),
            5
        );
        $paginatedHealths->setParam('_fragment', 'last-entries'); // Intelephense indicate the method is undefined, but it works perfectly

        $currentHealths = $healthRepository->findCatCurrentDiseases($cat, $currentDate);

        $paginatedCurrentHealths = $paginator->paginate(
            $currentHealths,
            $request->query->getInt('page2', 1),
            5,
            [
                'pageParameterName' => 'page2',
                'sortFieldParameterName' => 'sort2',
                'sortDirectionParameterName' => 'direction2',
            ]
        );

        return $this->render('cat-interface/cat-health/cat_health_disease.html.twig', [
            'controller_name' => 'CatHealthController',
            'cat' => $cat,
            'paginatedHealths' => $paginatedHealths,
            'paginatedCurrentHealths' => $paginatedCurrentHealths,
            'currentHealths' => $currentHealths,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/blessures", name="cat-wound")
     * @Route("/espace-veterinaire/chat/{id}/sante/blessures", name="veterinary-cat-wound")
     */
    public function catWound(Request $request, Cat $cat, HealthRepository $healthRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $healths = $healthRepository->findCatWounds($cat);

        $paginatedHealths = $paginator->paginate(
            $healths,
            $request->query->getInt('page', 1),
            5
        );
        $paginatedHealths->setParam('_fragment', 'last-entries'); // Intelephense indicate the method is undefined, but it works perfectly

        return $this->render('cat-interface/cat-health/cat_health_wound.html.twig', [
            'controller_name' => 'CatHealthController',
            'cat' => $cat,
            'paginatedHealths' => $paginatedHealths,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/chirurgie", name="cat-surgery")
     * @Route("/espace-veterinaire/chat/{id}/sante/chirurgie", name="veterinary-cat-surgery")
     */
    public function catSurgery(Request $request, Cat $cat, HealthRepository $healthRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $healths = $healthRepository->findCatSurgeries($cat);

        $paginatedHealths = $paginator->paginate(
            $healths,
            $request->query->getInt('page', 1),
            5
        );
        $paginatedHealths->setParam('_fragment', 'last-entries'); // Intelephense indicate the method is undefined, but it works perfectly

        return $this->render('cat-interface/cat-health/cat_health_surgery.html.twig', [
            'controller_name' => 'CatHealthController',
            'cat' => $cat,
            'paginatedHealths' => $paginatedHealths,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/analyses", name="cat-analysis")
     * @Route("/espace-veterinaire/chat/{id}/sante/analyses", name="veterinary-cat-analysis")
     */
    public function catAnalysis(Request $request, Cat $cat, HealthRepository $healthRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $healths = $healthRepository->findCatAnalysis($cat);

        $paginatedHealths = $paginator->paginate(
            $healths,
            $request->query->getInt('page', 1),
            5
        );
        $paginatedHealths->setParam('_fragment', 'last-entries'); // Intelephense indicate the method is undefined, but it works perfectly

        return $this->render('cat-interface/cat-health/cat_health_analysis.html.twig', [
            'controller_name' => 'CatHealthController',
            'cat' => $cat,
            'paginatedHealths' => $paginatedHealths,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/documents", name="cat-document")
     * @Route("/espace-veterinaire/chat/{id}/sante/documents", name="veterinary-cat-document")
     */
    public function catDocument(Request $request, Cat $cat, HealthRepository $healthRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $healths = $healthRepository->findCatDocuments($cat);

        $paginatedHealths = $paginator->paginate(
            $healths,
            $request->query->getInt('page', 1),
            5
        );
        $paginatedHealths->setParam('_fragment', 'last-entries'); // Intelephense indicate the method is undefined, but it works perfectly

        return $this->render('cat-interface/cat-health/cat_health_document.html.twig', [
            'controller_name' => 'CatHealthController',
            'cat' => $cat,
            'paginatedHealths' => $paginatedHealths,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/visite-veterinaire/ajouter", name="add-vetVisit")
     */
    public function addVetVisit(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $health = new Health;

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('add-vetVisit', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getVetVisitMotif() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date et le motif de la visite.");

                return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);
            }

            $health->setCat($cat);

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date et le motif de la visite.");

            return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_vet_visit.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/allergies/ajouter", name="add-allergy")
     */
    public function addAllergy(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $health = new Health;

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('add-allergy', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getAllergy() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date de début et un type d'allergie.");

                return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);
            }

            $health->setCat($cat);

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date de début et un type d'allergie.");

            return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_allergy.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/maladies/ajouter", name="add-disease")
     */
    public function addDisease(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $health = new Health;

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('add-disease', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getDisease() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date de début et un nom de maladie.");

                return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);
            }

            $health->setCat($cat);

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date de début et un nom de maladie.");

            return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_disease.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/blessures/ajouter", name="add-wound")
     */
    public function addWound(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $health = new Health;

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('add-wound', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getWound() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date de début et un type de blessure.");

                return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);
            }

            $health->setCat($cat);

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date de début et un type de blessure.");

            return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_wound.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/chirurgie/ajouter", name="add-surgery")
     */
    public function addSurgery(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $health = new Health;

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('add-surgery', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getSurgery() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date et un type de chirurgie.");

                return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);
            }

            $health->setCat($cat);

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date et un type de chirurgie.");

            return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_surgery.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/analyses/ajouter", name="add-analysis")
     */
    public function addAnalysis(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $health = new Health;

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('add-analysis', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getAnalysis() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date et le motif de l'analyse.");

                return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);
            }

            $health->setCat($cat);

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date et le motif de l'analyse.");

            return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_analysis.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/documents/ajouter", name="add-document")
     */
    public function addDocument(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat, SluggerInterface $slugger): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $documents = $healthRepository->findCatFilenames($cat);

        // Those are the authorized document extensions.
        $mimeTypes = ['application/pdf', 'image/png', 'image/jpg', 'image/jpeg'];

        $health = new Health;

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('add-document', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('document')->getData();

            $documentName = $form->getData()->getDocumentName();

            if ($file) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $fileExtension = finfo_file($finfo, $file);
            }

            if ($file == null || $documentName == null) {
                $this->addFlash('danger', "Le document n'a pas été ajouté. Tous les champs sont requis.");

                return $this->redirectToRoute('cat-document', ['id' => $cat->getId() ]);
            }

            // We check the size and extension of the document.
            if (filesize($file) <= 1000000 && in_array($fileExtension, $mimeTypes)) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);

                $filename = $safeFilename . '.' . $file->guessExtension();

                // We have to check if this document has already been dowloaded to prevent duplicates.
                $fileExists = array_search($filename, array_column($documents, 'document'));

                if ($fileExists !== false) {
                    $this->addFlash('danger', "Le document n'a pas été ajouté. Il existe déjà.");

                    return $this->redirectToRoute('cat-document', ['id' => $cat->getId() ]);
                    
                } else {
                    $file->move(
                        $this->getParameter('files_directory'),
                        $filename
                    );
    
                    $health->setCat($cat);
    
                    $health->setDocument($filename);
    
                    $manager->persist($health);
                    $manager->flush();
    
                    $this->addFlash('success', "Le document a été ajouté.");
    
                    return $this->redirectToRoute('cat-document', ['id' => $cat->getId() ]);
                }
            } else {
                $this->addFlash('danger', "Le document n'a pas été ajouté. Il doit faire moins d'1Mo et avoir une des extensions autorisées : pdf, png, jpg ou jpeg.");

                return $this->redirectToRoute('cat-document', ['id' => $cat->getId() ]);
            }    
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "Le document n'a pas été ajouté. Tous les champs sont requis.");

            return $this->redirectToRoute('cat-document', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_document.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/visite-veterinaire/{healthId}/editer", name="edit-vetVisit")
     */
    public function editVetVisit(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('edit-vetVisit', ['catId' => $cat->getId(), 'healthId' => $health->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getVetVisitMotif() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir une date et le motif de la visite.");

                return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);
            }

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir une date et le motif de la visite.");

            return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_vet_visit.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/allergies/{healthId}/editer", name="edit-allergy")
     */
    public function editAllergy(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('edit-allergy', ['catId' => $cat->getId(), 'healthId' => $health->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getAllergy() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date de début et un type d'allergie.");

                return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);
            }

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date de début et un type d'allergie.");

            return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_allergy.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/maladies/{healthId}/editer", name="edit-disease")
     */
    public function editDisease(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('edit-disease', ['catId' => $cat->getId(), 'healthId' => $health->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getDisease() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date de début et un nom de maladie.");

                return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);
            }

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date de début et un nom de maladie.");

            return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_disease.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/blessures/{healthId}/editer", name="edit-wound")
     */
    public function editWound(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('edit-wound', ['catId' => $cat->getId(), 'healthId' => $health->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getWound() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date de début et un type de blessure.");

                return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);
            }

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date de début et un type de blessure.");

            return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_wound.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/chirurgie/{healthId}/editer", name="edit-surgery")
     */
    public function editSurgery(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('edit-surgery', ['catId' => $cat->getId(), 'healthId' => $health->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getSurgery() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date et un type de chirurgie.");

                return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);
            }

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date et un type de chirurgie.");

            return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_surgery.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/analyses/{healthId}/editer", name="edit-analysis")
     */
    public function editAnalysis(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('edit-analysis', ['catId' => $cat->getId(), 'healthId' => $health->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getAnalysis() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date et le motif de l'analyse.");

                return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);
            }

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date et le motif de l'analyse.");

            return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_analysis.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/documents/{healthId}/editer", name="edit-document")
     */
    public function editDocument(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null, SluggerInterface $slugger): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        $oldDocument = $health->getDocument();

        $documents = $healthRepository->findCatFilenames($cat);

        $mimeTypes = ['application/pdf', 'image/png', 'image/jpg', 'image/jpeg'];

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('edit-document', ['catId' => $cat->getId(), 'healthId' => $health->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('document')->getData();

            $documentName = $form->getData()->getDocumentName();

            if ($file) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $fileExtension = finfo_file($finfo, $file);
            }
            
            if ($documentName == null) {
                $this->addFlash('danger', "Le document n'a pas été modifié. Vous devez saisir au moins une date et un nom de document.");

                return $this->redirectToRoute('cat-document', ['id' => $cat->getId() ]);
            }

            if ($file) {
                if (filesize($file) <= 1000000 && in_array($fileExtension, $mimeTypes)) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $filename = $safeFilename . '.' . $file->guessExtension();
    
                    $fileExists = array_search($filename, array_column($documents, 'document'));
    
                    if ($fileExists !== false) {
                        $this->addFlash('danger', "Le document n'a pas été modifié. Il existe déjà.");
    
                        return $this->redirectToRoute('cat-document', ['id' => $cat->getId() ]);
                    }

                    $filesystem = new Filesystem();
                    $filesystem->remove($this->getParameter('files_directory') . '/' . $oldDocument);

                    $file->move(
                        $this->getParameter('files_directory'),
                        $filename
                    );

                    $health->setDocument($filename);

                } else {
                    $this->addFlash('danger', "Le document n'a pas été modifié. Il doit faire moins d'1Mo et avoir une des extensions autorisées : pdf, png, jpg ou jpeg.");

                    return $this->redirectToRoute('cat-document', ['id' => $cat->getId() ]);
                } 
            }

            $health->setCat($cat);

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "Le document a été modifié.");

            return $this->redirectToRoute('cat-document', ['id' => $cat->getId() ]);
              
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "Le document n'a pas été modifié. Vous devez saisir au moins une date et un nom de document.");

            return $this->redirectToRoute('cat-document', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_document.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/visite-veterinaire/{healthId}/supprimer", name="delete-health")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/sante/allergies/{healthId}/supprimer", name="delete-health")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/sante/maladies/{healthId}/supprimer", name="delete-health")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/sante/blessures/{healthId}/supprimer", name="delete-health")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/sante/chirurgie/{healthId}/supprimer", name="delete-health")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/sante/analyses/{healthId}/supprimer", name="delete-health")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/sante/documents/{healthId}/supprimer", name="delete-health")
     */
    public function deleteHealth(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        // To avoir having to create a delete method for each kind of health data, we create variables that will store the corresponding data. 
        // The one we are deleting is the only variable that is not empty. We then use this variable to determine the redirection route.
        $vetVisit = $health->getVetVisitMotif();
        $allergy = $health->getAllergy();
        $disease = $health->getDisease();
        $wound = $health->getWound();
        $surgery = $health->getSurgery();
        $analysis = $health->getAnalysis();
        $document = $health->getDocument();

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('delete-health', ['catId' => $cat->getId(), 'healthId' => $health->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->remove($health);
            $manager->flush();

            $filesystem = new Filesystem();
            $filesystem->remove($this->getParameter('files_directory') . '/' . $document);

            $this->addFlash('success', "L'entrée a été supprimée");

            // Here we check each variable to find the one that is not empty and will determine the redirection route in case of success or failure.
            if ($vetVisit != null) {
                return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);
            } else if ($allergy != null) {
                return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);
            } else if ($disease != null) {
                return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);
            } else if ($wound != null) {
                return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);
            } else if ($surgery != null) {
                return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);
            } else if ($analysis != null) {
                return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);
            } else if ($document != null) {
                return $this->redirectToRoute('cat-document', ['id' => $cat->getId() ]);
            } else {
                return $this->redirectToRoute('cat-health', ['id' => $cat->getId() ]);
            } 
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée' n'a pas été supprimée");

            if ($vetVisit  != null) {
                return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);
            } else if ($allergy != null) {
                return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);
            } else if ($disease != null) {
                return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);
            } else if ($wound != null) {
                return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);
            } else if ($surgery != null) {
                return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);
            } else if ($analysis != null) {
                return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);
            } else if ($document != null) {
                return $this->redirectToRoute('cat-document', ['id' => $cat->getId() ]);
            } else {
                return $this->redirectToRoute('cat-health', ['id' => $cat->getId() ]);
            }
        }

        return $this->render('cat-interface/cat-health/_delete_cat_health.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView(),
            'catId' => $cat->getId(),
            'healthId' => $health->getId(),
        ]);
    }
}
