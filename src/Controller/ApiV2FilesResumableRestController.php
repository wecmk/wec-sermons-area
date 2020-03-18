<?php

namespace App\Controller;

use App\Services\Event\EventService;
use App\Services\Filesystem\FilesystemService;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Attachment\AttachmentTypeService;

/**
 *
 * @Route("/api/v2/files", name="api_v2_files_")
 */
class ApiV2FilesResumableRestController extends AbstractController
{
    /**
     * Must be compatible with PHP hash function
     * @var string Name of selected hashing algorithm (e.g. "md5", "sha256", "haval160,4", etc..)
     */
    private $algo = "sha512";

    /**
     * X-Upload-Content-Type. Optional. Set to the MIME type of the file data,
     *      which will be transferred in subsequent requests. If the MIME type
     *      of the data is not specified in metadata or through this header,
     *      the object will be served as application/octet-stream.
     *
     * X-Upload-Content-Length. Optional. Set to the number of bytes of file
     *      data, which will be transferred in subsequent requests.
     *
     * Content-Type. Required if you have metadata for the file. Set to
     *      application/json; charset=UTF-8.
     *
     * Content-Length. Required unless you are using chunked transfer encoding.
     *      Set to the number of bytes in the body of this initial request.
     *
     * @Route("/upload/resumable", name="upload_resumable_post", methods={"POST"})
     * @param Request $request
     * @param LoggerInterface $logger
     * @param EventService $eventService
     * @param FilesystemService $filesystemService
     * @param AttachmentTypeService $attachmentTypeService
     * @return Response
     */
    public function resumableUpload(
        Request $request,
        LoggerInterface $logger,
        EventService $eventService,
        FilesystemService $filesystemService,
        AttachmentTypeService $attachmentTypeService
    ) {
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
            $request->headers->get('X-Upload-Content-Length'),
            $attachmentTypeService->findByType($typeString),
            $hash,
            $hashAlgo,
            $extension,
            $isPublic
        );

        // return a Location Header
        $response = new \Symfony\Component\HttpFoundation\Response('', 201);
        $response->headers->set("AttachmentId", $attachmentMetadata->getId()->toString());
        $response->headers->set("Location", $this->generateUrl('api_v2_files_upload_resumable_put', ['id' => $attachmentMetadata->getId()->toString()]), true);
        return $response;
    }

    /**
     * @Route("/upload/resumable/{id}", name="upload_resumable_put", methods={"PUT"})
     *
     * @param Request $request
     * @param LoggerInterface $logger
     * @param FilesystemService $filesystemService
     * @param $id
     * @return JsonResponse|Response
     */
    public function resumableUploadContinue(
        Request $request,
        LoggerInterface $logger,
        FilesystemService $filesystemService,
        $id
    ) {
        $uuid = Uuid::fromString($id);
        // Based on https://developers.google.com/drive/api/v3/manage-uploads
        // input request
        /*
            PUT https://www.googleapis.com/upload/drive/v3/files?uploadType=resumable&upload_id=xa298sd_sdlkj2 HTTP/1.1
            Content-Length: 524288
            Content-Type: image/jpeg
            Content-Range: bytes 0-524287/2000000

            [BYTES 0-524287]
        */
        // Based on https://developers.google.com/drive/api/v3/manage-uploads

        $uploadedContent = new \App\Entity\UploadedContent(
            $request->headers->get('Content-Range'),
            $request->getContent()
        );
        $pointer = $filesystemService->appendContentToFile($uuid, $uploadedContent);
        $fileMetadata = $filesystemService->getFileMetadata($uuid);

        // once the upload is complete, return 200/201, along with any metadata associated with the resource
        $response = null;
        if ($pointer == intval($fileMetadata->getContentLength())) {
            $hashIsValid =  $filesystemService->hashIsValid($uuid);
            $logger->info("Is Hash Valid: " . $hashIsValid);
            if ($hashIsValid) {
                $status = 201;
                $attachmentMetadata = $filesystemService->completeAndValidateUpload($uuid);
                $body = [
                    'id' => $attachmentMetadata->getId(),
                    'mimeType' => $attachmentMetadata->getMimeType(),
                    'contentLength' => $attachmentMetadata->getContentLength(),
                    'complete' => $attachmentMetadata->getComplete(),
                    'hash' => $attachmentMetadata->getComplete()
                ];
                $response = $this->json($body, $status);
            } else {
                $response = new \Symfony\Component\HttpFoundation\Response();
                $response->setStatusCode(400, "Hash does not match. Retry upload");
            }
        } else {
            $response = new \Symfony\Component\HttpFoundation\Response();
            $response->setStatusCode(308, "Resume Incomplete");
            $rangeValue = "0-" . strval($pointer - 1);
            $response->headers->set("Content-Range", $rangeValue);
        }
        return $response;
    }
}
