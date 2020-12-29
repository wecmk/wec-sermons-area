<?php

namespace App\Controller;

use App\Entity\AttachmentMetadata;
use App\Entity\CanBeDownloaded;
use App\Entity\Event;
use App\Entity\Series;
use App\Services\Filesystem\FilesystemService;
use FOS\RestBundle\View\View;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiV2PublicSermonsController extends AbstractController
{
    /**
     * @Route("/api/v2/publicsermons/youtube", name="api_v2_public_sermons_youtube")
     */
    public function youTubeSermons()
    {
        $response = array();
        $response['lastSunday'] = $this->getServices("Last Sunday");
        $text = (date('D') == 'Sun') ? "Today" : "Next Sunday";
        $response['nextSunday'] = $this->getServices($text);

        $view = View::create();

        $view->setData($response)->setStatusCode(200);
        return $view;

    }

    private function getServices($search) {
        $sermonsRepository = $this->getDoctrine()->getRepository(Event::class);
        $lastSundayServices = $sermonsRepository->servicesByDate($search);

        $response = [];
        foreach ($lastSundayServices as $event) {
            foreach ($event->getEventUrls() as $url) {
                if ($url->getTitle() == "Watch") {
                    $response[$url->getEvent()->getApm()] = $url->getUrl();
                }
            }
        }
        return $response;
    }


    /**
     * @Route("/api/v2/publicsermons/all", name="api_v2_public_sermons")
     */
    public function all()
    {
        $sermonsRepository = $this->getDoctrine()->getRepository(Event::class);

        $seriesRepository = $this->getDoctrine()->getRepository(Series::class);
        $publicSeries = $seriesRepository->findBy(['isPublic' => true], ['Name' => 'ASC']);

        $publicEvents = [];

        foreach ($publicSeries as $series)
        {
            $events = $sermonsRepository->findBySeries($series);

            foreach ($events as $event) {

                $attachmentMetadata = $event->getAttachmentMetadata()->filter(function (AttachmentMetadata $element) {
                    return $element->getType()->getType() == 'sermon-recording';
                })->get(0);

                if (null == $attachmentMetadata) {
                    continue;
                }

                $publicEvents[] = [
                    'Id' => $event->getId()->toString(),
                    'Date' => $event->getDate(),
                    'Apm' => $event->getApm(),
                    'Series' => $event->getSeries(),
                    'Reading' => $event->getReading(),
                    'Title' => $event->getTitle(),
                    'Speaker' => $event->getSpeaker()->getName(),
                    'AudioUrl' => $this->generateUrl('public_sermon_download_v2', ['id' => $attachmentMetadata->getId()], UrlGeneratorInterface::ABSOLUTE_URL)

                ];
            }
        }

        $view = View::create();

        $view->setData($publicEvents)->setStatusCode(200);
        return $view;
    }

    /**
     *
     * Optional GET parameter force-dl=true (default to force the download or
     * false to stream
     *
     * @Route("/public/sermon/download/{id}", name="public_sermon_download_v2")
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
