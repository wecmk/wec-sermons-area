<?php

namespace App\Controller;

use App\Entity\MediaObject;
use App\Services\Attachment\AttachmentTypeService;
use App\Services\Event\EventService;
use App\Services\Filesystem\FilesystemService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Attachment\UploadService;
use App\Entity\CanBeDownloaded;
use Symfony\Component\HttpFoundation\Response;

class AttachmentPostAction extends AbstractController
{
    public function __invoke(
        $data,
        Request $request,
        LoggerInterface $logger,
        EventService $eventService,
        FilesystemService $filesystemService,
        AttachmentTypeService $attachmentTypeService
    ): Response {
        $body = json_decode($request->getContent(), true);
        $typeString = (isset($body['type'])) ? $body['type'] : null;
        $eventUuidString = (isset($body['eventUuid'])) ? $body['eventUuid'] : null;
        $isPublic = (isset($body['isPublic'])) ? boolval($body['isPublic']) : false;
        $extension = (isset($body['extension'])) ? $body['extension'] : "";
        $hash = (isset($body['hash'])) ? $body['hash'] : "";
        $hashAlgo = (isset($body['hashAlgo'])) ? $body['hashAlgo'] : "";

        $event = $eventService->getById($eventUuidString);

        $logger->info($typeString);

        $attachmentMetadata = $filesystemService->create(
            $event,
            $request->headers->get('X-Upload-Content-Type', 'application/octet-stream'),
            $request->headers->get('X-Upload-Content-Length', 0),
            $attachmentTypeService->findByType($typeString),
            $hash,
            $hashAlgo,
            $extension,
            $isPublic
        );

        // return a Location Header
        $response = new Response('', 201);
        $response->headers->set("AttachmentId", $attachmentMetadata->getId()->toString());
        $response->headers->set("Location", $this->generateUrl('api_attachments_put_item', ['id' => $attachmentMetadata->getId()->toString()]), true);
        return $response;
    }
}
