<?php


namespace App\EventListener;

use App\Entity\Event;
use App\Services\Google\YouTubeVideoMetadataService;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 *
 * @package App\EventListener
 */
class EventUpdateYouTubeNotifier
{
    private LoggerInterface $logger;

    private YouTubeVideoMetadataService $youTubeVideoMetadataService;

    public function __construct(LoggerInterface $logger, YouTubeVideoMetadataService $youTubeVideoMetadataService)
    {
        $this->logger = $logger;
        $this->youTubeVideoMetadataService = $youTubeVideoMetadataService;
    }

    public function postUpdate(Event $event, LifecycleEventArgs $args)
    {
        try {
            $this->logger->info("EventUpdateYouTubeNotifier notified");
            $this->youTubeVideoMetadataService->updateVideo($event);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
