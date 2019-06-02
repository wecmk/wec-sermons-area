<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @Route("/api/v2/files", name="api_v2_files_")
 */
class ApiV2FilesResumableRestController extends AbstractController
{
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
    public function resumableUpload(Request $request)
    {
        // Based on https://developers.google.com/drive/api/v3/manage-uploads
        $metadata = [];
        $uploadedMimeType = $request->headers->get('X-Upload-Content-Type', 'application/octet-stream');
        $uploadedContentLength = $request->headers->get('X-Upload-Content-Length', "*");
        $uploadedContentType = $request->headers->get('X-Upload-Content-Length', "*");
        $contentLength = $request->headers->get('Content-Length', "*");
        
        // Create a session token
        $id = \Ramsey\Uuid\Uuid::uuid4();
                
        $temp_file = "/tmp/" . $id;
        $handle = fopen($temp_file, "c+b");
        fclose($handle);
    
        // return a Location Header
        $response = new \Symfony\Component\HttpFoundation\Response('', 201);
        $response->headers->set("Location", $this->generateUrl('api_v2_files_upload_resumable_put', ['id' => $id]), true);
        return $response;
    }
    
    /**
     * @Route("/upload/resumable/{id}", name="upload_resumable_put", methods={"PUT"})
     */
    public function resumableUploadContinue(Request $request, $id)
    {
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
        $uploadedMimeType = $request->headers->get('X-Upload-Content-Type', 'application/octet-stream');
        $uploadedContentLength = $request->headers->get('X-Upload-Content-Length', "*");
        $uploadedContentType = $request->headers->get('X-Upload-Content-Length', "*");
        $contentLength = $request->headers->get('Content-Length', "*");
        
        $uploadedContentRange = new \App\Entity\UploadedContentRange($request->headers->get('Content-Range'));
        
        // Create a session token
        $temp_file = "/tmp/" . $id;
        $outputStream = fopen($temp_file, "c+b");
        fseek($outputStream, $uploadedContentRange->startsAt());
        
        fwrite($outputStream, $request->getContent(), $uploadedContentRange->length());
        $pointer = ftell($outputStream);
        fclose($outputStream);
        
        // once the upload is complete, return 200/201, along with any metadata associated with the resource
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->setStatusCode(308, "Resume Incomplete");
        $rangeValue = "0-" . strval($pointer-1);
        $response->headers->set("Content-Range", $rangeValue);
        return $response;
    }
}
