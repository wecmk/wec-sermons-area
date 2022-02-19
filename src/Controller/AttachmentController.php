<?php

namespace App\Controller;

use App\Services\Filesystem\FilesystemService;
use Psr\Log\LoggerInterface;
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
     * @Route("/{uuid}", name="index")
     * @param Request $request
     * @param LoggerInterface $logger
     * @param FilesystemService $filesystemService
     * @param AttachmentMetadata $attachment
     * @return BinaryFileResponse
     */
    public function index(Request $request, LoggerInterface $logger, FilesystemService $filesystemService, AttachmentMetadata $attachment)
    {
        $forceDownload = $request->query->get("force-dl", "true") == "true";
        $deposition = ($forceDownload) ? ResponseHeaderBag::DISPOSITION_ATTACHMENT : ResponseHeaderBag::DISPOSITION_INLINE;


        $response = $filesystemService->generateBinaryFileResponse($attachment->getUuid(), 200);
        if ($attachment->getEvent() instanceof CanBeDownloaded
            && $attachment->getIsPublic()
            && $attachment->getType()->getCanBePublic()
            && $attachment->getComplete()) {
            /** @var CanBeDownloaded $canBeDownloaded */
            $canBeDownloaded = $attachment->getEvent();
            $response->setContentDisposition($deposition, $canBeDownloaded->getFilename($attachment->getExtension()));
        } else {
            throw $this->createAccessDeniedException("File is not downloadable - ERROR: Does not implement interface CanBeDownloaded " . $attachment->getId());
        }
        return $response;
    }
}
