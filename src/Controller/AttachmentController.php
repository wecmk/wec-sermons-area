<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Entity\AttachmentMetadata;
use App\Services\Attachment\UploadService;
use App\Entity\CanBeDownloaded;

/**
 * @Route("/attachment", name="attachment_")
 */
class AttachmentController extends AbstractController
{

    /**
     *
     * Optional GET parameter force-dl=true (default to force the download or
     * false to stream
     *
     * @Route("/{id}", name="index")
     */
    public function index(Request $request, \Psr\Log\LoggerInterface $logger, UploadService $uploadService, AttachmentMetadata $attachment)
    {
        $forceDownload = $request->query->get("force-dl", "true") == "true";
        $deposition = ($forceDownload) ? ResponseHeaderBag::DISPOSITION_ATTACHMENT : ResponseHeaderBag::DISPOSITION_INLINE;
        $fileName = $uploadService->getFullFileName($attachment);
        
        $response = new BinaryFileResponse($fileName, 200, array());
        if ($attachment->getEvent() instanceof CanBeDownloaded
                && !$attachment->isDeleted()
                && $attachment->getIsPublic()
                && $attachment->getType()->getCanBePublic()) {
            /** @var CanBeDownloaded $canBeDownloaded */
            $canBeDownloaded = $attachment->getEvent();
            $response->setContentDisposition($deposition, $canBeDownloaded->getFilename($attachment->getExtension()));
        } else {
            throw $this->createAccessDeniedException("File is not downloadable - ERROR: Does not implement interface CanBeDownloaded");
        }
        return $response;
    }
}
