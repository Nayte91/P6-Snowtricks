<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Picture;
use App\Form\FigureType;
use App\Repository\FigureRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/") */
class FigureController extends AbstractController
{
    /**
     * @Route("/", name="figure_index", methods={"GET"})
     */
    public function index(FigureRepository $figureRepository, Profiler $profiler): Response
    {
        $profiler->enable();
        return $this->render('figure/index.html.twig', [
            'figures' => $figureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="figure_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request): Response
    {
        $figure = new Figure;
        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $picture = new Picture;
            $picture->setFile($form->get('pictures')->getData());

            if ($picture->getFile()) {
                $picture->setFigure($figure);
                $entityManager->persist($picture);
            }

        $figure->setCreatedAt(new \DateTime);

        $entityManager->persist($figure);
        $entityManager->flush();

        $this->addFlash('notice', 'La figure a bien été enregistrée !');

        return $this->redirectToRoute('figure_index');
        }

        return $this->render('figure/new.html.twig', [
            'post' => $figure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="figure_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show(Figure $figure): Response
    {
        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="figure_edit", methods={"GET","POST"}, requirements={"id":"\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Figure $figure): Response
    {
        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['imageFile']->getData();

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('figure_index');
        }

        return $this->render('figure/edit.html.twig', [
            'figure' => $figure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="figure_delete", methods={"DELETE"}, requirements={"id":"\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Figure $figure): Response
    {
        if ($this->isCsrfTokenValid('delete'.$figure->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($figure);
            $entityManager->flush();
        }

        return $this->redirectToRoute('figure_index');
    }
}
