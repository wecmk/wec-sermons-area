<?php

namespace App\Entity;

use App\Repository\AttachmentMetadataTypeRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 */
#[ORM\Entity(repositoryClass: AttachmentMetadataTypeRepository::class)]
class AttachmentMetadataType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::BOOLEAN, options: ['default' => true])]
    private ?bool $canBePublic = true;

    public function __construct(#[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 30)]
    private ?string $type, #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 20)]
    private ?string $name, #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private ?string $description, $canBePublic = true)
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCanBePublic(): ?bool
    {
        return $this->canBePublic;
    }

    public function setCanBePublic(bool $CanBePublic): self
    {
        $this->canBePublic = $CanBePublic;

        return $this;
    }
}
