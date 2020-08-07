<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Video;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/{id}/videos")
 */
class VideoController extends AbstractController
{
    /** @Route("/", name="videos_list", methods={"GET"}) */
    public function listVideos(Figure $figure, VideoRepository $videoRepository): Response
    {
        $videos = $videoRepository->findByFigure($figure);

        $serializer = new Serializer([new ObjectNormalizer], [new JsonEncoder]);

        $jsonContent = $serializer->serialize($videos, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['figure', 'file', 'extension', 'id']]);

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /** @Route("{video}/delete", name="videos_remove", methods={"DELETE"}) */
    public function removeVideo(Figure $figure, Video $video)
    {
        return $this->json('ok');
    }

    /** @Route("/add", name="videos_add", methods={"POST"}) */
    public function addVideo(Request $request, Figure $figure)
    {
        /*
        $file = $request->files->get('file');
        $picture = new Video;
        $picture->setFile($file);
        $picture->setFigure($figure);
        $em = $this->getDoctrine()->getManager();
        $em->persist($picture);
        $em->flush();
           */
        return $this->json("c'est Toto qui pète", 201);
    }
}
