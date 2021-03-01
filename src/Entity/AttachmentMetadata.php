<?php

namespace App\Entity;

use App\Repository\AttachmentMetadataRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Doctrine\ORM\Id\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=AttachmentMetadataRepository::class)
 * @ApiResource()
 */
class AttachmentMetadata implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected ?UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $mimeType = null;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private ?string $extension = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $contentLength = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $complete = false;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private string $hash = "";

    /**
     * @ORM\Column(type="string", length=10)
     */
    private ?string $hashAlgo = "sha512";

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="attachmentMetadata")
     */
    private ?Event $event = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AttachmentMetadataType", cascade={"persist"})
     */
    private ?AttachmentMetadataType $type = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isPublic = false;

    public function __construct()
    {
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
