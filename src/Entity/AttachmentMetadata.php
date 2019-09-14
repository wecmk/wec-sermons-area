<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UploadedFileMetadataRepository")
 */
class AttachmentMetadata
{

    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeleteableEntity;
    use TimestampableEntity;
    
    /**
     * @var \Ramsey\Uuid\UuidInterface
     * @JMS\Type("uuid")
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mimeType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contentLength;

    /**
     * @ORM\Column(type="string", length=50, nullable=true))
     */
    private $fileLocation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $complete = false;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $hash;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EventAttachment", inversedBy="AttachmentMetadata")
     * @ORM\JoinColumn(nullable=false)
     */
    private $eventAttachment;
        
    public function getId(): ?\Ramsey\Uuid\Uuid
    {
        return $this->id;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getContentLength(): ?string
    {
        return $this->contentLength;
    }

    public function setContentLength(string $contentLength): self
    {
        $this->contentLength = $contentLength;

        return $this;
    }

    public function getFileLocation(): ?string
    {
        if (null == $this->fileLocation) {
            return "/data/media/tmp/uploaded_file/" . $this->id->toString();
        }
        return $this->fileLocation;
    }

    public function setFileLocation(string $fileLocation): self
    {
        $this->fileLocation = $fileLocation;

        return $this;
    }
    
    /**
     * Generate a hash value using the contents of a given file
     * @param string $algo Name of selected hashing algorithm (i.e. "md5", "sha256", "haval160,4", etc..)
     * @return string Returns a string containing the calculated message digest as lowercase hexits unless raw_output is set to true in which case the raw binary representation of the message digest is returned.
     */
    public function getHash($algo = "sha512")
    {
        return hash_file($algo, $this->getFileLocation());
    }

    public function getComplete(): ?bool
    {
        return $this->complete;
    }

    public function setComplete(bool $complete): self
    {
        $this->complete = $complete;

        return $this;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getEventAttachment(): ?EventAttachment
    {
        return $this->eventAttachment;
    }

    public function setEventAttachment(?EventAttachment $eventAttachment): self
    {
        $this->eventAttachment = $eventAttachment;

        return $this;
    }
}
