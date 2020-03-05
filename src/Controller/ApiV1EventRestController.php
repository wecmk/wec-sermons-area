<?php

namespace App\Controller;

use App\Entity\AttachmentMetadata;
use App\Entity\Event;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\SerializerInterface;
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
     * Return the overall user list.
     *
     * @Route("/{id}/attachments", name="get_attachments", methods={"GET"})
     * @param SerializerInterface $serializer
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAllEventsAction(SerializerInterface $serializer, Event $event)
    {
        $serializedData = $serializer->serialize($event->getAttachmentMetadata(), 'json');
        return new Response($serializedData);
    }
}
