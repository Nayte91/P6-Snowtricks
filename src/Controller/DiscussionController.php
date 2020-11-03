<?php


namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Figure;
use App\Form\DiscussionType;
use App\Repository\DiscussionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/** @Route("/figures/{slug}/discussions") */
class DiscussionController extends AbstractController
{
    /** @Route("/", name="discussions_list", methods={"GET"}) */
    public function listDiscussions(Figure $figure, DiscussionRepository $discussionRepository): Response
    {
        $discussions = $discussionRepository->findByFigure($figure);

        $serializer = new Serializer([new ObjectNormalizer], [new JsonEncoder]);

        $jsonContent = $serializer->serialize($discussions, 'json',  [AbstractNormalizer::IGNORED_ATTRIBUTES => ['figure']]);

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/{discussion}/delete", name="discussions_remove", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function removeDiscussion(Figure $figure, Discussion $discussion)
    {
        $figure->removeDiscussion($discussion);
        $this->getDoctrine()->getManager()->flush();

        return $this->json('ok');
    }

    /**
     * @Route("/add", name="discussions_add", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function addDiscussion(Request $request, Figure $figure)
    {
        $discussion = new Discussion;
        $form = $this->createForm(DiscussionType::class, $discussion);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $discussion->setFigure($figure);
            $discussion->setAuthor($this->getUser());
            $figure->addDiscussion($discussion);
            $em = $this->getDoctrine()->getManager();
            $em->persist($discussion);
            $em->flush();

            return $this->json("Discussion added !", 201);
        }

        return $this->json("Discussion not added.", 201);
    }
}