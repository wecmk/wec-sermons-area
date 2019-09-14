<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     */
    public function resumableUpload(Request $request, \App\Services\File\UploadService $uploadService)
    {
        // Based on https://developers.google.com/drive/api/v3/manage-uploads
        $metadata = new \App\Entity\AttachmentMetadata();
        $metadata->setMimeType($request->headers->get('X-Upload-Content-Type', 'application/octet-stream'));
        if ($request->headers->has('X-Upload-Content-Length')) {
            $metadata->setContentLength($request->headers->get('X-Upload-Content-Length'));
        } else {
            $response = new Response();
            $response->setStatusCode(411, "X-Upload-Content-Length Required");
            return $response;            
        }
        $contentType = $request->headers->get('Content-Type', "*");

        // Content of the body (not the overall file length)
        $contentLength = $request->headers->get('Content-Length', "*");
        
        $uploadedFile = $uploadService->create($metadata->getMimeType(), $metadata->getContentLength());

        // return a Location Header
        $response = new \Symfony\Component\HttpFoundation\Response('', 201);
        $response->headers->set("Location", $this->generateUrl('api_v2_files_upload_resumable_put', ['id' => $uploadedFile->getId()->toString()]), true);
        return $response;
    }
    
    /**
     * @Route("/upload/resumable/{id}", name="upload_resumable_put", methods={"PUT"})
     */
    public function resumableUploadContinue(Request $request, 
            \App\Services\File\UploadService $uploadService,
            $id)
    {
        $uuid = \Ramsey\Uuid\Uuid::fromString($id);        
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
        $file = $uploadService->getFile($uuid);
        $pointer = $uploadService->update(\Ramsey\Uuid\Uuid::fromString($uuid), $uploadedContent);
        
        // once the upload is complete, return 200/201, along with any metadata associated with the resource
        $response = new \Symfony\Component\HttpFoundation\Response();
        if ($pointer == intval($file->getContentLength())) {
            $response->setStatusCode(201);
            $body = [
                "algo" => $this->algo,
                "hash" => $file->getHash($this->algo),
            ];
            $response->setContent(json_encode($body));
            $file->setHash($body['hash']);
            $uploadService->completeUpload($file);
        } else {
            $response->setStatusCode(308, "Resume Incomplete");
            $rangeValue = "0-" . strval($pointer-1);
            $response->headers->set("Content-Range", $rangeValue);
        }
        return $response;
    }
}
