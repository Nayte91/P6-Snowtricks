<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/** @Route("/figures/{slug}/videos") */
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

    /**
     * @Route("/{video}/delete", name="videos_remove", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function removeVideo(Figure $figure, Video $video)
    {
        $figure->removeVideo($video);
        $this->getDoctrine()->getManager()->flush();

        return $this->json('ok');
    }

    /**
     * @Route("/add", name="videos_add", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function addVideo(Request $request, Figure $figure)
    {
        $video = new Video;
        $form = $this->createForm(VideoType::class, $video);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $video->setFigure($figure);
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();

            return $this->json("Video added !", 201);
        }

        return $this->json("Video not added.", 201);
    }
}
