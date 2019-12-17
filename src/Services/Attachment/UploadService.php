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
    public function create($metadata)
    {
        $this->em->persist($metadata);
        $this->em->flush();
        
        $temp_file = $this->getFullFileName($metadata);
        touch($temp_file);
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
        $temp_file = $this->getFullFileName($file);
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
        $this->em->flush();
        return $completedFile;
    }
    
    public function getFile(Uuid $uuid): AttachmentMetadata
    {
        return $this->em->getRepository(AttachmentMetadata::class)->find($uuid);
    }
    
    public function getFullFileName(AttachmentMetadata $attachmentMetadata) {
        $path = "/data/media/tmp/uploaded_file/";
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }
        return $path . $attachmentMetadata->getFileLocation();
    }
    
    public function getHash($algo = "sha512", $fileName) {
        return hash_file($algo, $fileName);
    }
}
