<?php

namespace App\Controller;

use App\Form\UserLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route(path: '/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): \Symfony\Component\HttpFoundation\Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(UserLoginType::class);
        return $this->render(
            'security\login.html.twig',
            [
                'form' => $form,
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }
}
