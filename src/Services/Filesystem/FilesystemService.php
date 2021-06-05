<?php

namespace App\Services\Filesystem;

use App\Entity\AttachmentMetadata;
use App\Entity\AttachmentMetadataType;
use App\Entity\Event;
use App\Entity\UploadedContent;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/*
 * Manages files, it's upload, storage and other general lifecycle of a file
 *
 * Upload features inspired by https://developers.google.com/drive/api/v3/manage-uploads
 *
 * @author Samuel Pearce <samuel.pearce@open.ac.uk>
 */
class FilesystemService
{
    private $paramRootPath = "fileRootPath";

    /* @var $logger LoggerInterface */
    private $logger;

    /* @var $repository ObjectManager */
    private $repository;

    /** @var EntityManagerInterface $em */
    private $em;
    
    /** @var string $fileRootPath */
    private $fileRootPath;

    /**
     * @var WecFilesystem
     */
    private $filesystem;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, WecFilesystem $filesystem, $fileRootPath)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->repository = $em->getRepository(AttachmentMetadata::class);
        $this->filesystem = $filesystem;
        $this->fileRootPath = $fileRootPath;
    }

    /**
     * Create a new managed file
     * @param AttachmentMetadata $attachmentMetadata
     * @return AttachmentMetadata
     */
    public function create(AttachmentMetadata $attachmentMetadata): AttachmentMetadata
    {
        $this->filesystem->touch($attachmentMetadata->getUuid());

        return $attachmentMetadata;
    }

    /**
     * Returns an array of matching items
     * @param UuidInterface $id
     * @param UploadedContent $uploadedContent
     *
     * @return false|int
     */
    public function appendContentToFile(UuidInterface $id, UploadedContent $uploadedContent)
    {
        /** @var AttachmentMetadata $fileMetadata */
        $fileMetadata = $this->getFileMetadata($id);

        return $this->filesystem->writeContentToFile(
            $fileMetadata->getUuid(),
            $uploadedContent->getContent(),
            $uploadedContent->getUploadedContentRange()->startsAt(),
            $uploadedContent->getUploadedContentRange()->length()
        );
    }

    /**
     * Sets the metadata of the file to complete, if the hash matches
     * @param UuidInterface $id
     * @return AttachmentMetadata check attachmentMetadata->getComplete() to validate if upload was successful
     */
    public function completeAndValidateUpload(UuidInterface $id)
    {
        $fileMetadata = $this->getFileMetadata($id);
        $isValid = $this->hashIsValidFromAttachmentMetadata($fileMetadata);
        if ($isValid) {
            $fileMetadata->setComplete(true);
            $this->em->persist($fileMetadata);
            $this->em->flush();
        }
        return $fileMetadata;
    }

    public function hashIsValid(UuidInterface $id)
    {
        $fileMetadata = $this->getFileMetadata($id);
        return $this->hashIsValidFromAttachmentMetadata($fileMetadata);
    }

    private function hashIsValidFromAttachmentMetadata(AttachmentMetadata $fileMetadata)
    {
        return $this->filesystem->hashIsValid(
            $fileMetadata->getUuid(),
            $fileMetadata->getHash(),
            $fileMetadata->getHashAlgo()
        );
    }

    /**
     * @param UuidInterface $uuid
     *
     * @return AttachmentMetadata
     *
     * @throws FileNotFoundException if file metadata does not exist
     */
    public function getFileMetadata(UuidInterface $uuid): AttachmentMetadata
    {
        $result = $this->em->getRepository(AttachmentMetadata::class)->findOneBy(['uuid' => $uuid]);
        if (empty($result)) {
            throw new FileNotFoundException($uuid->toString() . " does not exist");
        }
        return $result;
    }

    public function getFileSize(UuidInterface $uuid)
    {
        return $this->filesystem->size($uuid->toString());
    }

    /**
     * @param int                 $id               The Uuid of the file to serve
     * @param int                 $status             The response status code
     * @param array               $headers            An array of response headers
     * @param bool                $public             Files are public by default
     * @param string|null         $contentDisposition The type of Content-Disposition to set automatically with the filename
     * @param bool                $autoEtag           Whether the ETag header should be automatically set
     * @param bool                $autoLastModified   Whether the Last-Modified header should be automatically set
     *
     *
     * @return BinaryFileResponse
     */
    public function generateBinaryFileResponse(UuidInterface $uuid, int $status = 200, array $headers = [], bool $public = true, string $contentDisposition = null, bool $autoEtag = false, bool $autoLastModified = true)
    {
        $fileMetadata = $this->getFileMetadata($uuid);
        if ($fileMetadata->getIsPublic()) {
            return new BinaryFileResponse($this->filesystem->getFilePath($uuid->toString()), $status, $headers, $public, $contentDisposition, $autoEtag, $autoLastModified);
        } else {
            throw new AccessDeniedException("This file is not publicly accessible");
        }
    }
}
