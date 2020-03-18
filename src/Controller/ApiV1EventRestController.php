<?php

namespace App\Controller;

use App\Entity\AttachmentMetadata;
use App\Entity\Event;
use App\Services\Attachment\AttachmentService;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Method;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Wec\MediaBundle\Entity\Series;

/**
 * Download controller.
 *
 * @Route("/api/v1/events", name="api_v1_events_")
 */
class ApiV1EventRestController extends AbstractFOSRestController
{

    /**
     * Return event object
     *
     * @Route("/{id}", name="get", methods={"GET"})
     * @param SerializerInterface $serializer
     * @param Event $event
     * @return JsonResponse
     */
    public function getEventAction(SerializerInterface $serializer, Event $event)
    {
        $serializedData = $serializer->serialize($event, 'json');
        return new Response($serializedData);
    }

    /**
     * Return all event attachments
     *
     * @Route("/{id}/attachments", name="get_attachments", methods={"GET"})
     * @param SerializerInterface $serializer
     * @param Event $event
     * @return JsonResponse
     */
    public function getAllEventAttachmentsAction(SerializerInterface $serializer, Event $event)
    {
        $serializedData = $serializer->serialize($event->getAttachmentMetadata(), 'json');
        return new Response($serializedData);
    }


    /**
     *
     * @Route("/{id}/attachments/{attachmentId}", name="attachment_delete", methods={"DELETE"})
     * @param AttachmentService $attachmentService
     * @param $eventId
     * @return Response
     */
    public function delete(AttachmentService $attachmentService, $attachmentId)
    {
        if ($attachmentService->delete($attachmentId)) {
            return new Response('', 204);
        } else {
            return new Response('', 500);
        }
    }
}
