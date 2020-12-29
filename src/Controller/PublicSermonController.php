<?php

namespace App\Controller;

use App\Entity\AttachmentMetadata;
use App\Entity\CanBeDownloaded;
use App\Entity\Event;
use App\Entity\PublicSermon;
use App\Entity\Series;
use App\Form\PublicSermonType;
use App\Repository\PublicSermonRepository;
use App\Services\Filesystem\FilesystemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/public/sermon")
 */
class PublicSermonController extends AbstractController
{
    /**
     * @Route("/", name="public_sermon_index", methods={"GET"})
     */
    public function index(PublicSermonRepository $publicSermonRepository): Response
    {
        return $this->render('public_sermon/index.html.twig', [
            'public_sermons' => $publicSermonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/download/{event}", name="public_sermon_download", methods={"GET"})
     */
    public function downloadById(Request $request, FilesystemService $filesystemService, Event $event): Response
    {
        $publicSermonHasOnePublicSeries = $event->getSeries()->filter(function (Series $element) {
            return $element->getIsPublic();
        })->count() > 0;

        if (!$event->getIsPublic() || !$publicSermonHasOnePublicSeries) {
            return $this->createAccessDeniedException("the file is not available for download");
        }

        $forceDownload = $request->query->get("force-dl", "true") == "true";
        $deposition = ($forceDownload) ? ResponseHeaderBag::DISPOSITION_ATTACHMENT : ResponseHeaderBag::DISPOSITION_INLINE;

        $event = $event->getEvent();
        $attachmentList = $event->getAttachmentMetadata()->filter(
            function (AttachmentMetadata $attachmentMetadata) {
                return $attachmentMetadata->getType()->getType() == "sermon-recording";
            }
        );
        if (count($attachmentList) > 0) {
            /** @var $attachment AttachmentMetadata */
            $attachment = $attachmentList[0];
            $response = $filesystemService->generateBinaryFileResponse($attachment->getId(), 200);
            if ($attachment->getEvent() instanceof CanBeDownloaded
                && $attachment->getIsPublic()
                && $attachment->getType()->getCanBePublic()
                && $attachment->getComplete()) {
                /** @var CanBeDownloaded $canBeDownloaded */
                $canBeDownloaded = $attachment->getEvent();
                $response->setContentDisposition($deposition, $canBeDownloaded->getFilename($attachment->getExtension()));
            } else {
                throw $this->createAccessDeniedException("File is not downloadable - ERROR: Does not implement interface CanBeDownloaded " . $attachment->getId());
            }
            return $response;
        }
    }

    /**
     * @Route("/new/{id}", name="public_sermon_new", methods={"GET","POST"})
     */
    public function newById(Request $request, Event $event): Response
    {
        $publicSermon = new PublicSermon();
        $publicSermon->setEvent($event);
        $publicSermon->setSpeaker($event->getSpeaker()->getName());
        $publicSermon->setTitle($event->getTitle());
        $form = $this->createForm(PublicSermonType::class, $publicSermon, [
            'event' => $event,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($publicSermon);
            $entityManager->flush();

            return $this->redirectToRoute('public_sermon_index');
        }

        return $this->render('public_sermon/new.html.twig', [
            'public_sermon' => $publicSermon,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="public_sermon_show", methods={"GET"})
     */
    public function show(PublicSermon $publicSermon): Response
    {
        return $this->render('public_sermon/show.html.twig', [
            'public_sermon' => $publicSermon,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="public_sermon_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PublicSermon $publicSermon): Response
    {
        $form = $this->createForm(PublicSermonType::class, $publicSermon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('public_sermon_index');
        }

        return $this->render('public_sermon/edit.html.twig', [
            'public_sermon' => $publicSermon,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="public_sermon_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PublicSermon $publicSermon): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publicSermon->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($publicSermon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('public_sermon_index');
    }
}
