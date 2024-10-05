<?php

namespace App\Services\Attachment;

use App\Entity\AttachmentMetadata;
use App\Entity\Event;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class AttachmentService
{
    /* @var $repository ObjectManager */
    private $repository;

    public function __construct(private readonly LoggerInterface $logger, private readonly EntityManagerInterface $em)
    {
        $this->repository = $this->em->getRepository(AttachmentMetadata::class);
    }

    public function delete($id)
    {
        $attachment = $this->em->getRepository(AttachmentMetadata::class)->find($id);
        $this->em->remove($attachment);
        $this->em->flush();
        return true;
    }
}
