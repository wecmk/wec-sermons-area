<?php

namespace App\Controller;

use App\Entity\ContactUsFormResults;
use App\Form\ContactUsType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Email;

/**
 * @Route("/contact", name="contact_")
 */
class ContactFormController extends AbstractController
{

    /**
     * @Route("/", name="form")
     * @param Request $request
     * @param LoggerInterface $logger
     * @param ContainerBagInterface $params
     * @param MailerInterface $mailer
     * @return RedirectResponse|Response
     * @throws TransportExceptionInterface
     */
    public function index(Request $request, LoggerInterface $logger, MailerInterface $mailer, $mail_from_address, $mail_to_address)
    {
        $form = $this->createForm(ContactUsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            /** @var ContactUsFormResults $contactUs */
            $contactUs = $form->getData();
            $fromEmail = $mail_from_address;
            $toEmail = $mail_to_address;
            $logger->debug($toEmail);
            $message = (new Email())
                ->from($fromEmail)
                ->to($toEmail)
                ->subject("Message from Members Area - " . date("Y-m-d H:i:s"))
                ->replyTo(
                    new Address(
                    $contactUs->getEmail(),
                    $toEmail
                )
                )
                ->html(nl2br(print_r($contactUs, true)), 'text/html');
            $mailer->send($message);


            return $this->redirectToRoute("contact_form_complete");
        }

        return $this->render('contact_form/index.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/complete", name="form_complete")
     */
    public function contactUsComplete()
    {
        return $this->render('contact_form/complete.html.twig', [
        ]);
    }
}
