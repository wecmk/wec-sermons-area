<?php

namespace App\EventListener;

use App\Entity\AttachmentMetadata;
use App\Services\Filesystem\WecFilesystem;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * This subscribed to the preRemove event in doctrine
 * There is a bug/issue where the id of a modified document is not sent in postRemove
 * This means we have to delete the AttachmentMetadata file before it is removed from the DB
 * The risk is that a failed transaction means we loose the file
 *
 *
 * Class AttachmentMetadataDeletedNotifier
 * @package App\EventListener
 */
class AttachmentMetadataDeletedNotifier
{
    private $id;

    public function __construct(private readonly LoggerInterface $logger, private readonly WecFilesystem $filesystem)
    {
    }

    public function preRemove(AttachmentMetadata $attachmentMetadata, LifecycleEventArgs $args)
    {
        $this->logger->alert("Deleting " . $attachmentMetadata->getId()->toString());
        $this->filesystem->remove($attachmentMetadata->getId()->toString());
    }
}
