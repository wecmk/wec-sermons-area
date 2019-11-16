<?php

namespace App\Services\Attachment;

use Psr\Log\LoggerInterface;
use App\Entity\AttachmentMetadata;
use Ramsey\Uuid\Uuid;
use App\Entity\UploadedContent;

/*
 * @author Samuel Pearce <samuel.pearce@open.ac.uk>
 */

class UploadService
{
    /* @var $logger LoggerInterface */

    private $logger;

    /* @var $repository \Doctrine\Common\Persistence\ObjectManager */
    private $repository;

    /** @var \Doctrine\ORM\EntityManagerInterface $em */
    private $em;
    
    /** @var string $fileRootPath */
    private $fileRootPath;

    public function __construct(LoggerInterface $logger, \Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->repository = $em->getRepository(AttachmentMetadata::class);
        $this->fileRootPath = "/tmp";
    }

    /**
     * Returns an array of matching items
     * @param string $name
     * @return array
     */
    public function create($mimeType, $contentLength)
    {
        // Based on https://developers.google.com/drive/api/v3/manage-uploads
        $metadata = new \App\Entity\AttachmentMetadata();
        $metadata->setMimeType($mimeType);
        $metadata->setContentLength($contentLength);
        $this->em->persist($metadata);
        $this->em->flush();
        
        $temp_file = $metadata->getFileLocation();
        $handle = fopen($temp_file, "c+b");
        fclose($handle);
                
        return $metadata;
    }

    /**
     * Returns an array of matching items
     * @param string $name
     * @return int Pointer of Stream
     */
    public function update(Uuid $id, UploadedContent $uploadedContent)
    {
        // Based on https://developers.google.com/drive/api/v3/manage-uploads
        /** @var AttachmentMetadata $file */
        $file = $this->getFile($id);
        if (empty($file)) {
            throw new FileNotFoundException("The uploaded file was not created");
        }
        
        // Create a session token
        $temp_file = $file->getFileLocation();
        $outputStream = fopen($temp_file, "c+b");
        fseek($outputStream, $uploadedContent->getUploadedContentRange()->startsAt());
        
        fwrite($outputStream, $uploadedContent->getContent(), $uploadedContent->getUploadedContentRange()->length());
        $pointer = ftell($outputStream);
        fclose($outputStream);
        return $pointer;
    }
    
    public function completeUpload(AttachmentMetadata $completedFile)
    {
        $completedFile->setComplete(true);
        $this->em->persist($completedFile);
        $this->em->commit();
    }
    
    public function getFile(Uuid $uuid): AttachmentMetadata
    {
        return $this->em->getRepository(AttachmentMetadata::class)->find($uuid);
    }
}
