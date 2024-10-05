<?php

namespace App\Controller;

use App\Entity\UploadedContent;
use App\Services\Filesystem\FilesystemService;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/api', name: 'api_')]
class ApiFilesResumableRestController extends AbstractController
{
    /**
     *
     * @param Request $request
     * @param LoggerInterface $logger
     * @param FilesystemService $filesystemService
     * @param $id
     * @return Response
     */
    #[Route(path: '/attachment_metadatas/{id}/binary', name: 'attachment_binary_put', methods: ['PUT'])]
    public function resumableUploadContinue(
        Request $request,
        LoggerInterface $logger,
        FilesystemService $filesystemService,
        $id
    ): \Symfony\Component\HttpFoundation\Response {
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
                    'uuid' => $attachmentMetadata->getUuid(),
                    'mimeType' => $attachmentMetadata->getMimeType(),
                    'contentLength' => $attachmentMetadata->getContentLength(),
                    'complete' => $attachmentMetadata->getComplete(),
                    'hash' => $attachmentMetadata->getComplete()
                ];
                $response = $this->json($body, $status);
            } else {
                $response = new Response();
                $response->setStatusCode(\Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST, "Hash does not match. Retry upload");
            }
        } else {
            $response = new Response();
            $response->setStatusCode(\Symfony\Component\HttpFoundation\Response::HTTP_PERMANENTLY_REDIRECT, "Resume Incomplete");
            $rangeValue = "0-" . strval($pointer - 1);
            $response->headers->set("Content-Range", $rangeValue);
        }
        return $response;
    }
}
