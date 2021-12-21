<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Entity\Measure;
use App\Form\CatMeasureFormType;
use App\Repository\CatRepository;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\MeasureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatMeasuresController extends AbstractController
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
     * @Route("/espace-utilisateur/chat/{id}/mesures", name="cat-measures")
     * @Route("/espace-veterinaire/chat/{id}/mesures", name="veterinary-cat-measures")
     */
    public function catMeasures(Cat $cat): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        return $this->render('cat-interface/cat-measures/cat_measures.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'cat' => $cat
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/mesures/poids", name="cat-weight")
     * @Route("/espace-veterinaire/chat/{id}/mesures/poids", name="veterinary-cat-weight")
     */
    public function catWeight(Request $request, Cat $cat, MeasureRepository $measureRepository, PaginatorInterface $paginator, ChartBuilderInterface $chartBuilder): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $measures = $measureRepository->findCatWeights($cat);

        $paginatedMeasures = $paginator->paginate(
            $measures,
            $request->query->getInt('page', 1),
            5
        );

        // We use symfony/ux-chartjs (https://github.com/symfony/ux-chartjs) to represent the data.
        $weights = [];
        
        foreach ($measures as $measure) {
            $weights[$measure->getDate()->format('d/m/y')] = $measure->getWeight();
        }

        $weights = array_reverse($weights);

        if ($weights != null) {
            $minDataValue = min(array_values($weights));
            $maxDataValue = max(array_values($weights));
        } else {
            $minDataValue = 0;
            $maxDataValue = 1;
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => array_keys($weights),
            'datasets' => [
                [
                    'label' => 'Courbe de poids',
                    'borderColor' => '#907AD6',
                    'pointBorderColor' => '#240046',
                    'pointBackgroundColor' => '#240046',
                    'data' => array_values($weights),
                    'fill' => false,
                ],
            ],
        ]);

        $chart->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'yAxes' => [
                    ['stacked' => true],
                    'suggestedMin' => $minDataValue,
                    'suggestedMax' => $maxDataValue
                ],
            ],
        ]);

        return $this->render('cat-interface/cat-measures/cat_measures_weight.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'cat' => $cat,
            'paginatedMeasures' => $paginatedMeasures,
            'chart' => $chart,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/mesures/temperature", name="cat-temperature")
     * @Route("/espace-veterinaire/chat/{id}/mesures/temperature", name="veterinary-cat-temperature")
     */
    public function catTemperature(Request $request, Cat $cat, MeasureRepository $measureRepository, PaginatorInterface $paginator, ChartBuilderInterface $chartBuilder): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $measures = $measureRepository->findCatTemperatures($cat);

        $paginatedMeasures = $paginator->paginate(
            $measures,
            $request->query->getInt('page', 1),
            5
        );

        $temperatures = [];
        
        foreach ($measures as $measure) {
            $temperatures[$measure->getDate()->format('d/m/y')] = $measure->getTemperature();
        }

        $temperatures = array_reverse($temperatures);

        if ($temperatures != null) {
            $minDataValue = min(array_values($temperatures));
            $maxDataValue = max(array_values($temperatures));
        } else {
            $minDataValue = 0;
            $maxDataValue = 1;
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => array_keys($temperatures),
            'datasets' => [
                [
                    'label' => 'Courbe de température',
                    'borderColor' => '#907AD6',
                    'pointBorderColor' => '#240046',
                    'pointBackgroundColor' => '#240046',
                    'data' => array_values($temperatures),
                    'fill' => false,
                ],
            ],
        ]);

        $chart->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'yAxes' => [
                    ['stacked' => true],
                    'suggestedMin' => $minDataValue,
                    'suggestedMax' => $maxDataValue
                ],
            ],
        ]);

        return $this->render('cat-interface/cat-measures/cat_measures_temperatures.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'cat' => $cat,
            'paginatedMeasures' => $paginatedMeasures,
            'chart' => $chart,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/mesures/chaleurs", name="cat-heat")
     * @Route("/espace-veterinaire/chat/{id}/mesures/chaleurs", name="veterinary-cat-heat")
     */
    public function catHeat(Request $request, Cat $cat, MeasureRepository $measureRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        // We create 2 sets of data : those that are finished (with end date) and those that are ongoing.
        $measures = $measureRepository->findCatHeat($cat);

        $paginatedMeasures = $paginator->paginate(
            $measures,
            $request->query->getInt('page', 1),
            5,
            [
                'pageParameterName' => 'page',
                'sortFieldParameterName' => 'sort1',
                'sortDirectionParameterName' => 'direction1',
            ]
        );

        $currentMeasures = $measureRepository->findCatCurrentHeat($cat);

        $paginatedCurrentMeasures = $paginator->paginate(
            $currentMeasures,
            $request->query->getInt('page2', 1),
            5,
            [
                'pageParameterName' => 'page2',
                'sortFieldParameterName' => 'sort2',
                'sortDirectionParameterName' => 'direction2',
            ]
        );

        return $this->render('cat-interface/cat-measures/cat_measures_heat.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'cat' => $cat,
            'paginatedMeasures' => $paginatedMeasures,
            'paginatedCurrentMeasures' => $paginatedCurrentMeasures,
            'currentMeasures' => $currentMeasures,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/mesures/poids/ajouter", name="add-weight")
     */
    public function addWeight(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $measure = new Measure;

        $form = $this->createForm(CatMeasureFormType::class, $measure, [
            'action' => $this->generateUrl('add-weight', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getWeight() == null) {
                $this->addFlash('danger', "La mesure n'a pas été ajoutée. Les deux champs sont requis.");

                return $this->redirectToRoute('cat-weight', ['id' => $cat->getId() ]);
            }

            $measure->setCat($cat);

            $manager->persist($measure);
            $manager->flush();

            $this->addFlash('success', "La mesure a été ajoutée");

            return $this->redirectToRoute('cat-weight', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "La mesure n'a pas été ajoutée. Les deux champs sont requis.");

            return $this->redirectToRoute('cat-weight', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-measures/_add_edit_cat_weight.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'measureForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/mesures/temperature/ajouter", name="add-temperature")
     */
    public function addTemperature(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $measure = new Measure;

        $form = $this->createForm(CatMeasureFormType::class, $measure, [
            'action' => $this->generateUrl('add-temperature', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getTemperature() == null) {
                $this->addFlash('danger', "La mesure n'a pas été ajoutée. Les deux champs sont requis.");

                return $this->redirectToRoute('cat-temperature', ['id' => $cat->getId() ]);
            }

            $measure->setCat($cat);

            $manager->persist($measure);
            $manager->flush();

            $this->addFlash('success', "La mesure a été ajoutée");

            return $this->redirectToRoute('cat-temperature', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "La mesure n'a pas été ajoutée. Les deux champs sont requis.");

            return $this->redirectToRoute('cat-temperature', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-measures/_add_edit_cat_temperature.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'measureForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/mesures/chaleur/ajouter", name="add-heat")
     */
    public function addHeat(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $measure = new Measure;

        $form = $this->createForm(CatMeasureFormType::class, $measure, [
            'action' => $this->generateUrl('add-heat', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getDate() == null) {
                $this->addFlash('danger', "La mesure n'a pas été ajoutée. La date de début est requise.");

                return $this->redirectToRoute('cat-heat', ['id' => $cat->getId() ]);
            }

            $measure->setCat($cat);
            $measure->setIsInHeat(true);

            $manager->persist($measure);
            $manager->flush();

            $this->addFlash('success', "La mesure a été ajoutée");

            return $this->redirectToRoute('cat-heat', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "La mesure n'a pas été ajoutée. La date de début est requise.");

            return $this->redirectToRoute('cat-heat', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-measures/_add_edit_cat_heat.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'measureForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/mesures/poids/{measureId}/editer", name="edit-weight")
     */
    public function editWeight(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, MeasureRepository $measureRepository, Cat $cat = null, Measure $measure = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $measureId = $request->attributes->get('measureId');
        $measure = $measureRepository->findOneBy(['id' => $measureId]);

        $form = $this->createForm(CatMeasureFormType::class, $measure, [
            'action' => $this->generateUrl('edit-weight', ['catId' => $cat->getId(), 'measureId' => $measure->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getWeight() == null) {
                $this->addFlash('danger', "La mesure n'a pas été modifiée. Les deux champs sont requis.");

                return $this->redirectToRoute('cat-weight', ['id' => $cat->getId() ]);
            }

            $manager->persist($measure);
            $manager->flush();

            $this->addFlash('success', "La mesure a été modifiée");

            return $this->redirectToRoute('cat-weight', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "La mesure n'a pas été modifiée. Les deux champs sont requis.");

            return $this->redirectToRoute('cat-weight', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-measures/_add_edit_cat_weight.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'measureForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/mesures/temperature/{measureId}/editer", name="edit-temperature")
     */
    public function editTemperature(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, MeasureRepository $measureRepository, Cat $cat = null, Measure $measure = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $measureId = $request->attributes->get('measureId');
        $measure = $measureRepository->findOneBy(['id' => $measureId]);

        $form = $this->createForm(CatMeasureFormType::class, $measure, [
            'action' => $this->generateUrl('edit-temperature', ['catId' => $cat->getId(), 'measureId' => $measure->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getTemperature() == null) {
                $this->addFlash('danger', "La mesure n'a pas été modifiée. Les deux champs sont requis.");

                return $this->redirectToRoute('cat-temperature', ['id' => $cat->getId() ]);
            }

            $manager->persist($measure);
            $manager->flush();

            $this->addFlash('success', "La mesure a été modifiée");

            return $this->redirectToRoute('cat-temperature', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "La mesure n'a pas été modifiée. Les deux champs sont requis.");

            return $this->redirectToRoute('cat-temperature', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-measures/_add_edit_cat_temperature.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'measureForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/mesures/chaleurs/{measureId}/editer", name="edit-heat")
     */
    public function editHeat(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, MeasureRepository $measureRepository, Cat $cat = null, Measure $measure = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $measureId = $request->attributes->get('measureId');
        $measure = $measureRepository->findOneBy(['id' => $measureId]);

        $form = $this->createForm(CatMeasureFormType::class, $measure, [
            'action' => $this->generateUrl('edit-heat', ['catId' => $cat->getId(), 'measureId' => $measure->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getDate() == null) {
                $this->addFlash('danger', "La mesure n'a pas été modifiée. La date de début est requise.");

                return $this->redirectToRoute('cat-heat', ['id' => $cat->getId() ]);
            }

            $manager->persist($measure);
            $manager->flush();

            $this->addFlash('success', "La mesure a été modifiée");

            return $this->redirectToRoute('cat-heat', ['id' => $cat->getId() ]);
            
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "La mesure n'a pas été modifiée. Les deux champs sont requis.");

            return $this->redirectToRoute('cat-heat', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-measures/_add_edit_cat_heat.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'measureForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/mesures/poids/{measureId}/supprimer", name="delete-measure")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/mesures/temperature/{measureId}/supprimer", name="delete-measure")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/mesures/chaleurs/{measureId}/supprimer", name="delete-measure")
     */
    public function deleteMeasure(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, MeasureRepository $measureRepository, Cat $cat = null, Measure $measure = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $measureId = $request->attributes->get('measureId');
        $measure = $measureRepository->findOneBy(['id' => $measureId]);

        // To avoir having to create a delete method for each kind of measure data, we create variables that will store the corresponding data. 
        // The one we are deleting is the only variable that is not empty. We then use this variable to determine the redirection route.
        $weight = $measure->getWeight();
        $temperature = $measure->getTemperature();
        $heat = $measure->getIsInHeat();

        $form = $this->createForm(CatMeasureFormType::class, $measure, [
            'action' => $this->generateUrl('delete-measure', ['catId' => $cat->getId(), 'measureId' => $measure->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->remove($measure);
            $manager->flush();

            $this->addFlash('success', "La mesure a été supprimée");

            // Here we check each variable to find the one that is not empty and will determine the redirection route in case of success or failure.
            if ($weight != null) {
                return $this->redirectToRoute('cat-weight', ['id' => $cat->getId() ]);
            } else if ($temperature != null) {
                return $this->redirectToRoute('cat-temperature', ['id' => $cat->getId() ]);
            } else if ($heat != null) {
                return $this->redirectToRoute('cat-heat', ['id' => $cat->getId() ]);
            } else {
                return $this->redirectToRoute('cat-measures', ['id' => $cat->getId() ]);
            }
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "La mesure n'a pas été supprimée");

            if ($weight  != null) {
                return $this->redirectToRoute('cat-weight', ['id' => $cat->getId() ]);
            } else if ($temperature != null) {
                return $this->redirectToRoute('cat-temperature', ['id' => $cat->getId() ]);
            } else if ($heat != null) {
                return $this->redirectToRoute('cat-heat', ['id' => $cat->getId() ]);
            } else {
                return $this->redirectToRoute('cat-measures', ['id' => $cat->getId() ]);
            }
        }

        return $this->render('cat-interface/cat-measures/_delete_cat_measure.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'measureForm' => $form->createView(),
            'catId' => $cat->getId(),
            'measureId' => $measure->getId(),
        ]);
    }
}
