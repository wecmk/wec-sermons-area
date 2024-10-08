<?php

namespace App\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\NewPasswordType;
use AppBundle\Form\PasswordRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResettingController extends AbstractController
{
    #[Route(path: '/reset_password', name: 'reset_password', methods: ['GET', 'POST'])]
    public function resetPassword(
        Request $request,
        EntityManagerInterface $entityManager
    ) {
        $form = $this->createForm(PasswordRequestType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $token = bin2hex(random_bytes(32));
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($user instanceof User) {
                $user->setPasswordRequestToken($token);
                $entityManager->flush();
                // send your email with SwiftMailer or anything else here
                $this->addFlash('success', "An email has been sent to your address");
                return $this->redirectToRoute('reset_password');
            }
        }
        return $this->render('reset-password.html.twig', ['form' => $form]);
    }

    #[Route(path: '/reset_password/confirm/{token}', name: 'reset_password_confirm', methods: ['GET', 'POST'])]
    public function resetPasswordCheck(
        Request $request,
        string $token,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $encoder,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session
    ) {
        $user = $entityManager->getRepository(User::class)->findOneBy(['passwordRequestToken' => $token]);
        if (!$token || !$user instanceof User) {
            $this->addFlash('danger', "User not found");
            return $this->redirectToRoute('reset_password');
        }
        $form = $this->createForm(NewPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            $password = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($password);
            $user->setPasswordRequestToken(null);
            $entityManager->flush();
            $token = new UsernamePasswordToken($user, $password, 'main');
            $tokenStorage->setToken($token);
            $session->set('_security_main', serialize($token));
            $this->addFlash('success', "Your new password has been set");
            return $this->redirectToRoute('homepage');
        }
        return $this->render('reset-password-confirm.html.twig', ['form' => $form]);
    }
}
