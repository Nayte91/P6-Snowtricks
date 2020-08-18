<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Picture;
use App\Form\PictureType;
use App\Repository\PictureRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/** @Route("/figures/{slug}/pictures") */
class PictureController extends AbstractController
{
    /** @Route("/", name="pictures_list", methods={"GET"}) */
    public function listPictures(Figure $figure, PictureRepository $pictureRepository): Response
    {
        $pictures = $pictureRepository->findByFigure($figure);

        if ($dp = $figure->getDisplayPicture()) {
            $dp->setIsDisplayPicture(true);
        }

        $serializer = new Serializer([new ObjectNormalizer], [new JsonEncoder]);

        $jsonContent = $serializer->serialize($pictures, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['figure', 'file', 'extension']]);

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/{picture}/delete", name="pictures_remove", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function removePicture(Figure $figure, Picture $picture)
    {
        $figure->removePicture($picture);
        $figure->setLastModified(new \DateTime);
        $em = $this->getDoctrine()->getManager();
        $em->remove($picture);
        $em->flush();

        return $this->json($figure->getPictures()->count());
    }

    /**
     * @Route("/add", name="pictures_add", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function addPicture(Request $request, Figure $figure)
    {
        $picture = new Picture;
        $form = $this->createForm(PictureType::class, $picture);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $figure->addPicture($picture);
            $figure->setLastModified(new \DateTime);
            $em = $this->getDoctrine()->getManager();
            $em->persist($picture);
            $em->flush();

            return $this->json("Picture added !", 201);
        }

        return $this->json("Picture not added.", 406);
    }

    /**
     * @Route("/{picture}/choose", name="pictures_choose", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     */
    public function chooseDisplayPicture(Figure $figure, Picture $picture)
    {
        $figure->setDisplayPicture($picture);
        $this->getDoctrine()->getManager()->flush();

        return $this->json('ok');
    }
}
