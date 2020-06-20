<?php

namespace App\Controller;

use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /** @Route("/login", name="app_login", methods={"GET", "POST"}) */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) return $this->redirectToRoute('figure_index');

        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /** @Route("/register", name="app_register") */
    public function register(Request $request): Response
    {
        $form = $this->createForm(RegistrationType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('notice', 'The account was successfully created !');

            return $this->redirectToRoute('figure_index');
        }

        return $this->render('security/register.html.twig', [
            'registration' => $form->createView(),
        ]);
    }

    /**
     * @Route("/password", methods="GET|POST", name="app_password")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice','Please reconnect to try your fresh new password');

            return $this->redirectToRoute('app_logout');
        }

        return $this->render('security/change_password.html.twig', [
            'password' => $form->createView(),
        ]);
    }

    /** @Route("/logout", name="app_logout") */
    public function logout(): void { }
}
