<?php

namespace App\Services\Attachment;

use Psr\Log\LoggerInterface;
use App\Entity\AttachmentMetadataType;
use Ramsey\Uuid\Uuid;
use App\Entity\UploadedContent;

/*
 * @author Samuel Pearce <samuel.pearce@open.ac.uk>
 */
class AttachmentTypeService
{
    /* @var $logger LoggerInterface */

    private $logger;

    /* @var $repository \Doctrine\Common\Persistence\ObjectManager */
    private $repository;

    /** @var \Doctrine\ORM\EntityManagerInterface $em */
    private $em;

    public function __construct(LoggerInterface $logger, \Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->repository = $em->getRepository(AttachmentMetadataType::class);
    }

    /**
     * Returns an array of matching items
     * @param string $name
     * @return array
     */
    public function create($name, $type, $description, $canBePublic = true)
    {
        $attachment = new AttachmentMetadataType();
        $attachment->setName($name);
        $attachment->setType($type);
        $attachment->setDescription($description);
        $attachment->setCanBePublic($canBePublic);

        $this->em->persist($attachment);
        $this->em->flush();
        return $attachment;
    }

    public function findByType($type)
    {
        return $this->repository->findOneBy([
            'type' => $type
        ]);
    }
}
