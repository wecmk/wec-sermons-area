<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AttachmentMetadataRepository")
 */
class AttachmentMetadata
{
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
     * @ORM\Column(type="string", length=10)
     */
    private $extension;

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
    private $hash = "";

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $hashAlgo = "sha512";

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="attachmentMetadata")
     * @JMS\Exclude()
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AttachmentMetadataType", cascade={"persist"})
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublic = false;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    public function getId(): ?UuidInterface
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

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

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

    public function getHash()
    {
        return $this->hash;
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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getType(): ?AttachmentMetadataType
    {
        return $this->type;
    }

    public function setType(?AttachmentMetadataType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        if ($this->type->getCanBePublic()) {
            return $this->isPublic;
        } else {
            return $this->isPublic = false;
        }
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;
     
        return $this;
    }

    public function getHashAlgo(): ?string
    {
        return $this->hashAlgo;
    }

    public function setHashAlgo(string $hashAlgo): self
    {
        $this->hashAlgo = $hashAlgo;

        return $this;
    }
}
