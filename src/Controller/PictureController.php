<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\PictureType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{id}/pictures/")
 */
class PictureController extends AbstractController
{
    /** @Route("create", name="pictures_add") */
    public function pictureCreate(Figure $figure)
    {
        return $this->json('ok, figure id is '. $figure->getId());
    }

    /** @Route("{picture}/delete", name="pictures_remove") */
    public function pictureRemove(Figure $figure, $picture)
    {
        return $this->json('ok');
    }

    /** @Route("add", name="pictures_test", methods={"POST"}) */
    public function pictureAdd(Request $request, Figure $figure)
    {
        $form = $this->createForm(PictureType::class);
        $form->handleRequest($request);
        return $this->json($request, 201);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()
                ->getRepository('AppBundle:File')
                ->store($form->getData());

            return $this->json([], 201);
        }

        return $form;
    }
}
