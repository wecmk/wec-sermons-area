<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use JMS\Serializer\SerializerInterface;

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
     */
    public function postLink(
        Request $request,
        $eventId,
        \App\Services\Event\EventService $eventService,
        \App\Services\File\UploadService $uploadService,
        \Doctrine\ORM\EntityManagerInterface $em
    ) {
        $event = $eventService->getById($eventId);
        if (isEmpty($event)) {
            $this->createNotFoundException("$eventId is not found");
        }
        $link = new \App\Entity\EventAttachment();
        $link->addEvent($event);
                
        try {
            $attachmentId = json_decode($request->getContent(), true)['attachmentMetadataId'];
            $attachmentId = \Ramsey\Uuid\Uuid::fromString($attachmentId);
            $file = $uploadService->getFile($attachmentId);
            $link->addAttachmentMetadata($file);
            $em->persist($link);
            $em->commit();
        } catch (\Ramsey\Uuid\Exception\InvalidUuidStringException $ex) {
            $this->logger->warning($ex->getMessage());
            throw new BadRequestHttpException("The request body is not valid");
        }
        
        return new \Symfony\Component\HttpFoundation\Response('', 204);
    }
}
