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
use AppBundle\Entity\User;
use AppBundle\Entity\Reward;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class ApiV2PublicSermonsController extends AbstractController
{
    /**
     * Lists links to last weeks sermons on YouTube and next weeks (if available)
     *
     * @Route("/api/v2/publicsermons/youtube", name="api_v2_public_sermons_youtube", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns an array of dates (YYYY-MM-DD) and another key-value pairs of [AM|PM] => Link",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type="array", groups={"full"}))
     *     )
     * )
     * @OA\Tag(name="v2")
     */
    public function youTubeSermons(): View
    {
        $response = array();
        $response['lastSunday'] = $this->getServices("Last Sunday");
        $text = (date('D') == 'Sun') ? "Today" : "Next Sunday";
        $response['nextSunday'] = $this->getServices($text);

        $view = View::create();

        $view->setData($response)->setStatusCode(200);
        return $view;
    }

    private function getServices($search)
    {
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
     * Lists all sermons which are accessible to the public
     *
     * @Route("/api/v2/publicsermons", name="api_v2_public_sermons", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="List all sermons available to the public",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type="array", groups={"full"}))
     *     )
     * )
     * @OA\Tag(name="v2")
     */
    public function all(): View
    {
        $sermonsRepository = $this->getDoctrine()->getRepository(Event::class);

        $seriesRepository = $this->getDoctrine()->getRepository(Series::class);
        $publicSeries = $seriesRepository->findBy(['isPublic' => true], ['Name' => 'ASC']);

        $publicEvents = [];

        foreach ($publicSeries as $series) {
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
     * Optional GET parameter force-dl=true (default to force the download or
     * false to stream
     *
     * @Route("/api/v2/publicsermon/{id}/download", name="public_sermon_download_v2", methods={"GET"})
     * @OA\Parameter(
     *    description="ID of event",
     *    in="path",
     *    name="id",
     *    required=true,
     *    example="72925096-6ae2-42b3-929f-16b4910478d9",
     *    @OA\Schema(
     *       type="string",
     *       format="string"
     *    )
     * )
     * @OA\Parameter(
     *    description="Force the download of the attachment (default: false, streams in browser)",
     *    in="query",
     *    name="force-dl",
     *    required=false,
     *    example="false",
     *    @OA\Schema(
     *       type="boolean",
     *       format="string"
     *    )
     * )
     * @OA\Response(
     *     response=200,
     *     description="List all sermons available to the public",
     *     content={
     *             @OA\MediaType(
     *                 mediaType="audio/mpeg"
     *             )
     *     }
     * )
     * @OA\Tag(name="v2")
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
