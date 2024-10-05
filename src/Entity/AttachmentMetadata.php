<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Repository\AttachmentMetadataRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Doctrine\ORM\Id\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
* @ApiResource(
*     shortName="attachment_metadata",
*     collectionOperations={"get","post"},
*     itemOperations={
*          "get",
*          "put",
*          "delete",
*          "get_binary"={
*              "method"="PUT",
*              "path"="/attachment_metadatas/{id}/binary",
*              "controller"="ApiFilesResumableRestController::class",
*              "openapi_context"= {
*                  "summary" = "Uploads [part of a] binary files",
*                  "description" = "Upload part of a binary file. The body contains raw binary data. The binary data is defined by the Content-Range. Input can be partial. The last byte must be submitted last otherwise the hash will not match and a 400 status will be returned. Use the Content-Range to define which chunk of data was submitted",
*                  "requestBody" = {
*                      "content" = {
*                          "application/octet-stream"={
                               "schema" = {
*                                  "type" = "object"
*                              }
*                          }
*                      }
*                  },
*                  "responses" = {
*                      "201" = {
*                          "description" = "The file was uploaded successfully"
*                      },
*                      "308" = {
*                          "description" = "The uploaded file is incomplete. Continue to upload more data"
*                      },
*                      "400" = {
*                          "description" = "You send the last chunk of data, but the hash did not match. Please retry"
*                      }
*                  }
*              }
*          }
*     },
* )
*/
#[ORM\Entity(repositoryClass: AttachmentMetadataRepository::class)]
class AttachmentMetadata implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @ApiProperty(identifier=false)
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    private int $id;

    /**
     * @ApiProperty(identifier=true)
     */
    #[ORM\Column(type: 'uuid', unique: true)]
    #[SerializedName('id')]
    #[Groups(['user:write'])]
    private UuidInterface $uuid;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private ?string $mimeType = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 10)]
    private ?string $extension = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private ?string $contentLength = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::BOOLEAN)]
    private ?bool $complete = false;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 128)]
    private string $hash = "";

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 10)]
    private ?string $hashAlgo = "sha512";

    #[ORM\ManyToOne(targetEntity: \App\Entity\Event::class, inversedBy: 'attachmentMetadata')]
    private ?Event $event = null;

    #[ORM\ManyToOne(targetEntity: \App\Entity\AttachmentMetadataType::class, cascade: ['persist'])]
    private ?AttachmentMetadataType $type = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::BOOLEAN)]
    private bool $isPublic = false;

    public function __construct(UuidInterface $uuid = null)
    {
        $this->uuid = $uuid ?: Uuid::uuid4();
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): AttachmentMetadata
    {
        $this->id = $id;

        return $this;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
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
