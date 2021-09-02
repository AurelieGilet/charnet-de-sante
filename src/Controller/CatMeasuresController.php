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
            'weightForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/mesures/poids/{weightId}/editer", name="edit-weight")
     */
    public function editWeight(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, MeasureRepository $measureRepository, Cat $cat = null, Measure $measure = null): Response 
    {
        $catID = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catID]);

        $measureID = $request->attributes->get('weightId');
        $measure = $measureRepository->findOneBy(['id' => $measureID]);

        $form = $this->createForm(CatMeasureFormType::class, $measure, [
            'action' => $this->generateUrl('edit-weight', ['catId' => $cat->getId(), 'weightId' => $measure->getId()
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
            'weightForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/mesures/poids/{weightId}/supprimer", name="delete-weight")
     */
    public function deleteWeight(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, MeasureRepository $measureRepository, Cat $cat = null, Measure $measure = null): Response 
    {
        $catID = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catID]);

        $measureID = $request->attributes->get('weightId');
        $measure = $measureRepository->findOneBy(['id' => $measureID]);

        $form = $this->createForm(CatMeasureFormType::class, $measure, [
            'action' => $this->generateUrl('delete-weight', ['catId' => $cat->getId(), 'weightId' => $measure->getId()
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

        return $this->render('cat-interface/cat-measures/_delete_cat_weight.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'weightForm' => $form->createView(),
            'catId' => $cat->getId(),
            'weightId' => $measure->getId(),
        ]);
    }
}
