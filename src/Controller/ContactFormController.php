<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/contact", name="contact_")
 */
class ContactFormController extends AbstractController {

    /**
     * @Route("/", name="form")
     */
    public function index(Request $request, \Swift_Mailer $mailer) {
        $form = $this->createForm(\App\Form\ContactUsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            /** @var \App\Entity\ContactUsFormResults $contactUs */
            $contactUs = $form->getData();

            $message = $mailer->createMessage()
                    ->setSubject("Message from Members Area - " . date("Y-m-d H:i:s"))
                    ->setTo(getenv("DEFAULT_TO_ADDRESS"))
                    ->setReplyTo(array(
                        $contactUs->getEmail(),
                        getenv("DEFAULT_TO_ADDRESS"),
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
    function contactUsComplete() {
        return $this->render('contact_form/complete.html.twig', [
        ]);
    }

}
