<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\DiscussionType;
use App\Form\FigureType;
use App\Form\PictureType;
use App\Form\VideoType;
use App\Repository\FigureRepository;
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
    public function new(FigureRepository $figureRepository): Response
    {
        $figure = new Figure;
        $entityManager = $this->getDoctrine()->getManager();
        $figure->setName('new figure '. (string) $figureRepository->findLastId());
        $entityManager->persist($figure);
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
        $discussionForm = $this->createForm(DiscussionType::class, null, [
            'action' => $this->generateUrl('discussions_add', [
                'slug' => $figure->getSlug(),
                ]),
            ]);

        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
            'discussionform' => $discussionForm->createView(),
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
    public function delete(Figure $figure): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($figure);
        $entityManager->flush();

        $this->addFlash('danger', 'Figure '.$figure->getName().' was deleted. Farewell !');

        return $this->json("figure deleted");
    }
}
