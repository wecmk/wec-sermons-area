<?php

namespace App\Controller;

use App\Services\Attachment\AttachmentService;
use App\Services\Event\EventService;
use App\Services\Filesystem\FilesystemService;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;

/**
 * Download controller.
 *
 * @Route("/api/v1/eventattachment", name="api_v1_eventattachment_")
 */
class ApiV1EventAttachmentController extends AbstractFOSRestController
{

    /**
     * Saves a link between and Event and an AttachmentMetadata
     *
     * Post a json containing:
     *
     * {
     *    "attachmentMetadataId": "UUID"
     * }
     * @todo #2 Move reference of EntityManager to it's own service
     * @Route("/{eventId}", name="link", methods={"POST"})
     * @param Request $request
     * @param $eventId
     * @param EventService $eventService
     * @param FilesystemService $filesystemService
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function postLink(
        Request $request,
        $eventId,
        EventService $eventService,
        FilesystemService $filesystemService,
        EntityManagerInterface $em
    ) {
        $event = $eventService->getById($eventId);
        if (isEmpty($event)) {
            $this->createNotFoundException("$eventId is not found");
        }
        $link = new \App\Entity\EventAttachment();
        $link->addEvent($event);
                
        try {
            $attachmentId = json_decode($request->getContent(), true)['attachmentMetadataId'];
            $attachmentId = Uuid::fromString($attachmentId);
            $file = $filesystemService->getFileMetadata($attachmentId);
            $link->addAttachmentMetadata($file);
            $em->persist($link);
            $em->commit();
        } catch (\Ramsey\Uuid\Exception\InvalidUuidStringException $ex) {
            $this->logger->warning($ex->getMessage());
            throw new BadRequestHttpException("The request body is not valid");
        }
        
        return new Response('', 204);
    }

    /**
     *
     * @Route("/{eventId}", name="delete", methods={"DELETE"})
     * @param AttachmentService $attachmentService
     * @param $eventId
     * @return Response
     */
    public function delete(AttachmentService $attachmentService, $eventId)
    {
        if ($attachmentService->delete($eventId)) {
            return new Response('', 204);
        } else {
            return new Response('', 500);
        }
    }
}
