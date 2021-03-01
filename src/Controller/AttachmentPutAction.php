<?php

namespace App\Controller;

use App\Entity\MediaObject;
use App\Entity\UploadedContent;
use App\Services\Filesystem\FilesystemService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Attachment\UploadService;
use App\Entity\CanBeDownloaded;
use Symfony\Component\HttpFoundation\Response;

class AttachmentPutAction extends AbstractController
{
    public function __invoke(
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

        $uploadedContent = new UploadedContent(
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
                $response = new Response();
                $response->setStatusCode(400, "Hash does not match. Retry upload");
            }
        } else {
            $response = new Response();
            $response->setStatusCode(308, "Resume Incomplete");
            $rangeValue = "0-" . strval($pointer - 1);
            $response->headers->set("Content-Range", $rangeValue);
        }
        return $response;
    }
}
