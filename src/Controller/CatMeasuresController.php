<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Entity\Measure;
use App\Form\CatMeasureFormType;
use App\Repository\CatRepository;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\MeasureRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatMeasuresController extends AbstractController
{
    /**
     * @Route("/espace-utilisateur/chat/{id}/mesures", name="cat-measures")
     */
    public function catMeasures(Cat $cat): Response
    {
        return $this->render('cat-interface/cat-measures/cat_measures.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'cat' => $cat
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/mesures/poids", name="cat-weight")
     */
    public function catWeight(Request $request, Cat $cat, MeasureRepository $measureRepository, PaginatorInterface $paginator, ChartBuilderInterface $chartBuilder): Response
    {
        $measures = $measureRepository->findCatWeights($cat);

        $paginatedMeasures = $paginator->paginate(
            $measures,
            $request->query->getInt('page', 1),
            5
        );

        $weights = [];
        
        foreach ($measures as $measure) {
            $weights[$measure->getDate()->format('d/m/y')] = $measure->getWeight();
        }

        $weights = array_reverse($weights);

        $minDataValue = min(array_values($weights)) - 1;

        $maxDataValue = max(array_values($weights)) + 1;

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
     */
    public function catTemperature(Request $request, Cat $cat, MeasureRepository $measureRepository, PaginatorInterface $paginator, ChartBuilderInterface $chartBuilder): Response
    {
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

        $minDataValue = min(array_values($temperatures)) - 1;

        $maxDataValue = max(array_values($temperatures)) + 1;

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
     * @Route("/espace-utilisateur/chat/{id}/mesures/poids/ajouter", name="add-weight")
     */
    public function addWeight(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

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

        return $this->render('cat-interface/cat-measures/_add_cat_weight.html.twig', [
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

        return $this->render('cat-interface/cat-measures/_add_cat_temperature.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'measureForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/mesures/poids/{measureId}/editer", name="edit-weight")
     */
    public function editWeight(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, MeasureRepository $measureRepository, Cat $cat = null, Measure $measure = null): Response 
    {
        $catID = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catID]);

        $measureID = $request->attributes->get('measureId');
        $measure = $measureRepository->findOneBy(['id' => $measureID]);

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

        return $this->render('cat-interface/cat-measures/_edit_cat_weight.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'measureForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/mesures/temperature/{measureId}/editer", name="edit-temperature")
     */
    public function editTemperature(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, MeasureRepository $measureRepository, Cat $cat = null, Measure $measure = null): Response 
    {
        $catID = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catID]);

        $measureID = $request->attributes->get('measureId');
        $measure = $measureRepository->findOneBy(['id' => $measureID]);

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

        return $this->render('cat-interface/cat-measures/_edit_cat_temperature.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'measureForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/mesures/poids/{measureId}/supprimer", name="delete-weight")
     */
    public function deleteWeight(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, MeasureRepository $measureRepository, Cat $cat = null, Measure $measure = null): Response 
    {
        $catID = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catID]);

        $measureID = $request->attributes->get('measureId');
        $measure = $measureRepository->findOneBy(['id' => $measureID]);

        $form = $this->createForm(CatMeasureFormType::class, $measure, [
            'action' => $this->generateUrl('delete-weight', ['catId' => $cat->getId(), 'measureId' => $measure->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->remove($measure);
            $manager->flush();

            $this->addFlash('success', "La mesure a été supprimée");

            return $this->redirectToRoute('cat-weight', ['id' => $cat->getId() ]);
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "La mesure n'a pas été supprimée");

            return $this->redirectToRoute('cat-weight', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-measures/_delete_cat_measure.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'measureForm' => $form->createView(),
            'catId' => $cat->getId(),
            'measureId' => $measure->getId(),
        ]);
    }
    /**
     * @Route("/espace-utilisateur/chat/{catId}/mesures/temperature/{measureId}/supprimer", name="delete-temperature")
     */
    public function deleteTemperature(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, MeasureRepository $measureRepository, Cat $cat = null, Measure $measure = null): Response 
    {
        $catID = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catID]);

        $measureID = $request->attributes->get('measureId');
        $measure = $measureRepository->findOneBy(['id' => $measureID]);

        $form = $this->createForm(CatMeasureFormType::class, $measure, [
            'action' => $this->generateUrl('delete-weight', ['catId' => $cat->getId(), 'measureId' => $measure->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->remove($measure);
            $manager->flush();

            $this->addFlash('success', "La mesure a été supprimée");

            return $this->redirectToRoute('cat-temperature', ['id' => $cat->getId() ]);
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "La mesure n'a pas été supprimée");

            return $this->redirectToRoute('cat-temperature', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-measures/_delete_cat_measure.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'measureForm' => $form->createView(),
            'catId' => $cat->getId(),
            'measureId' => $measure->getId(),
        ]);
    }
}
