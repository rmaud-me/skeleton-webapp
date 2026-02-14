<?php

namespace App\Controller;

use App\Form\FormThemePresentType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route('/submenu1', name: 'submenu_1')]
    public function submenu1(): Response
    {
        return $this->render('submenu1.html.twig');
    }

    #[Route('/submenu2', name: 'submenu_2')]
    public function submenu2(): Response
    {
        return $this->render('submenu2.html.twig');
    }

    #[Route('/chartjs', name: 'app_chartjs')]
    public function chartjs(ChartBuilderInterface $chartBuilder): Response
    {
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'Cookies eaten 🍪',
                    'backgroundColor' => 'rgb(255, 99, 132, .4)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [2, 10, 5, 18, 20, 30, 45],
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Km walked 🏃‍♀️',
                    'backgroundColor' => 'rgba(45, 220, 126, .4)',
                    'borderColor' => 'rgba(45, 220, 126)',
                    'data' => [10, 15, 4, 3, 25, 41, 25],
                    'tension' => 0.4,
                ],
            ],
        ]);

        $chart->setOptions([
            'maintainAspectRatio' => false,
        ]);

        return $this->render('charjs.html.twig', [
            'chart' => $chart,
        ]);
    }

    #[Route('/paginator', name: 'app_paginator')]
    public function paginator(PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            [
                [
                    'value' => '2/1',
                    'name' => '2/1 - Competition',
                ],
                [
                    'value' => 'SAYCBASIC',
                    'name' => 'SAYC - Basic',
                ],
                [
                    'value' => 'SAYCPLUS',
                    'name' => 'SAYC - Plus',
                ],
                [
                    'value' => 'SEF',
                    'name' => 'SEF - Competition',
                ],
                [
                    'value' => 'ACOL',
                    'name' => 'ACOL - Competition',
                ],
            ],
            $request->query->getInt('page', 1),
            2
        );

        return $this->render('pagination.html.twig', ['pagination' => $pagination]);
    }

    #[Route('/components', name: 'components', methods: ['GET'])]
    public function components(): Response
    {
        $form = $this->createForm(FormThemePresentType::class);

        return $this->render('components.html.twig', [
            'form'             => $form,
        ]);
    }
}
