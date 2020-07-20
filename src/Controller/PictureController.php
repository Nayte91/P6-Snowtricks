<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Picture;
use App\Repository\PictureRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/{id}/pictures")
 */
class PictureController extends AbstractController
{
    /** @Route("/", name="pictures_list", methods={"GET"}) */
    public function listPictures(Figure $figure, PictureRepository $pictureRepository): Response
    {
        $pictures = $pictureRepository->findByFigure($figure);

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $jsonContent = $serializer->serialize($pictures, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['figure', 'file', 'extension', 'id']]);

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /** @Route("{picture}/delete", name="pictures_remove", methods={"DELETE"}) */
    public function removePicture(Figure $figure, Picture $picture)
    {
        return $this->json('ok');
    }

    /** @Route("/add", name="pictures_add", methods={"POST"}) */
    public function addPicture(Request $request, Figure $figure)
    {
        $file = $request->files->get('file');
        $picture = new Picture;
        $picture->setFile($file);
        $picture->setFigure($figure);
        $em = $this->getDoctrine()->getManager();
        $em->persist($picture);
        $em->flush();

        return $this->json("OK ?", 201);
    }
}
