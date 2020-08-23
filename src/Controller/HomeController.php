<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /** @Route("/", name="figure_index", methods={"GET"}) */
    public function index(FigureRepository $figureRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'figures' => $figureRepository->findModified(),
        ]);
    }
}
