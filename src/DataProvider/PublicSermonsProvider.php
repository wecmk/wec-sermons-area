<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\AttachmentMetadata;
use App\Entity\Event;
use App\Entity\PublicSermon;
use App\Repository\EventRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class PublicSermonsProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private readonly EventRepository $eventRepository, private readonly RouterInterface $router)
    {
    }


    public function getCollection(string $resourceClass, string $operationName = null)
    {
        $sermons = $this->eventRepository->findAllPublicEvents();
        $output = [];
        /** @var Event $sermon */
        foreach ($sermons as $sermon) {
            $attachmentMetadata = $sermon->getAttachmentMetadata()->filter(fn (AttachmentMetadata $element) => $element->getType()->getType() == 'sermon-recording')->get(0);

            if (null == $attachmentMetadata) {
                continue;
            }

            $publicSermon = new PublicSermon();
            $output[] = $publicSermon
                ->setId($sermon->getId())
                ->setDate($sermon->getDate())
                ->setApm($sermon->getApm())
                ->setReading($sermon->getReading())
                ->setTitle($sermon->getTitle())
                ->setSpeaker($sermon->getSpeaker()->getName())
                ->setAudioUrl($this->router->generate('public_sermon_download_v2', ['id' => $attachmentMetadata->getId()], UrlGeneratorInterface::ABSOLUTE_URL));
        }
        return [$output];
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === PublicSermon::class;
    }
}
