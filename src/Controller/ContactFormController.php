<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/contact", name="contact_")
 */
class ContactFormController extends AbstractController
{

    /**
     * @Route("/", name="form")
     */
    public function index(Request $request, LoggerInterface $logger, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(\App\Form\ContactUsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            /** @var \App\Entity\ContactUsFormResults $contactUs */
            $contactUs = $form->getData();
            $toEmail = getenv("DEFAULT_TO_ADDRESS");
            $logger->debug($toEmail);
            $message = $mailer->createMessage()
                    ->setSubject("Message from Members Area - " . date("Y-m-d H:i:s"))
                    ->setTo($toEmail)
                    ->setFrom(getenv("DEFAULT_FROM_ADDRESS"))
                    ->setReplyTo(array(
                        $contactUs->getEmail(),
                        $toEmail,
                    ))
                    ->setBody(nl2br(print_r($contactUs, true)), 'text/html');

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
