<?php

namespace App\Controller;

use App\Entity\AttachmentMetadata;
use App\Entity\CanBeDownloaded;
use App\Entity\Event;
use App\Entity\Series;
use App\Services\Filesystem\FilesystemService;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Entity\User;
use AppBundle\Entity\Reward;

class PublicSermonsController extends AbstractController
{
    /**
     * Optional GET parameter force-dl=true (default to force the download or
     * false to stream
     *
     * @Route("/publicsermon/{id}/download", name="public_sermon_download_v2", methods={"GET"})
     */
    public function download(Request $request, FilesystemService $filesystemService, AttachmentMetadata $attachment): BinaryFileResponse
    {
        $forceDownload = $request->query->get("force-dl", "true") == "true";
        $deposition = ($forceDownload) ? ResponseHeaderBag::DISPOSITION_ATTACHMENT : ResponseHeaderBag::DISPOSITION_INLINE;


        $response = $filesystemService->generateBinaryFileResponse($attachment->getId(), 200);
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
