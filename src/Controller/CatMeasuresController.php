<?php

namespace App\Controller;

use App\Entity\Cat;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\MeasureRepository;
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
}
