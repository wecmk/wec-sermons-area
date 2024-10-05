<?php

namespace App\Controller;

use App\Form\UserLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

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

    #[Route(path: '/api/loginlink', name: 'api-login-link', methods: ['GET'])]
    public function loginLink(Request $request,
                              #[Autowire(service: 'security.authenticator.login_link_handler.main')]
                              LoginLinkHandlerInterface $loginLinkHandler,
                              UserInterface $user): JsonResponse
    {
        return $this->json($loginLinkHandler->createLoginLink($user, $request, 2592000));
    }

}
