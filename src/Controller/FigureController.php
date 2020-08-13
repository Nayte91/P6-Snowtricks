<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\FigureType;
use App\Form\PictureType;
use App\Form\VideoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/figures") */
class FigureController extends AbstractController
{
    /**
     * @Route("/new", name="figure_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(): Response
    {
        $figure = new Figure;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($figure);
        $entityManager->flush();
        $figure->setSlug($figure->getId());
        $entityManager->flush();

        $this->addFlash('success', 'Your new trick is created ! Edit it and save it to make it public.');

        return $this->redirectToRoute('figure_edit', [
                'slug' => $figure->getSlug(),
            ]);
    }

    /**
     * @Route("/{slug}", name="figure_show", methods={"GET"})
     */
    public function show(Figure $figure): Response
    {
        return $this->render('figure/show.html.twig', [
                'figure' => $figure,
            ]);
    }

    /**
     * @Route("/{slug}/edit", name="figure_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Figure $figure): Response
    {
        $pictureForm = $this->createForm(PictureType::class, null, [
            'action' => $this->generateUrl('pictures_add', [
                'slug' => $figure->getSlug(),
            ]),
        ]);
        $videoForm = $this->createForm(VideoType::class, null, [
            'action' => $this->generateUrl('videos_add', [
                'slug' => $figure->getSlug(),
            ]),
        ]);
        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Trick saved, modifications are now published online !');

            return $this->redirectToRoute('figure_index');
        }

        return $this->render('figure/edit.html.twig', [
                'figure' => $figure,
                'form' => $form->createView(),
                'pictureform' => $pictureForm->createView(),
                'videoform' => $videoForm->createView(),
            ]);
    }

    /**
     * @Route("/{slug}", name="figure_delete", methods={"DELETE"})
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
