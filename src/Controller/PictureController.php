<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Picture;
use App\Repository\PictureRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/{id}/pictures/")
 */
class PictureController extends AbstractController
{
    /** @Route("list", name="pictures_list") */
    public function list(Figure $figure, PictureRepository $pictureRepository)
    {
        $pictures = $pictureRepository->findByFigure($figure);

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($pictures, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['figure', 'file', 'extension', 'id']]);

        return $this->json($jsonContent, 200);
    }

    /** @Route("{picture}/delete", name="pictures_remove") */
    public function pictureRemove(Figure $figure, $picture)
    {
        return $this->json('ok');
    }

    /** @Route("add", name="pictures_add", methods={"POST"}) */
    public function pictureAdd(Request $request, Figure $figure, LoggerInterface $logger)
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
