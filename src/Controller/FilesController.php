<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/files", name="files_")
 */
class FilesController extends AbstractController
{

    /**
     * Download file
     *
     * @Route("/{downloadType}/{id}", name="download")
     */
    public function serveFile(Request $request, $downloadType, $id)
    {
        $response = $this->fileResponse($request, "");
        
        switch ($downloadType) {
            case "steam":
                $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE);
            break;
            case "download":
            default:
                $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
            break;
        }
        
        return $response;
    }

    private function fileResponse(Request $request, $pathToFile)
    {
        BinaryFileResponse::trustXSendfileTypeHeader();
        $response = new BinaryFileResponse($file);
    }
}
