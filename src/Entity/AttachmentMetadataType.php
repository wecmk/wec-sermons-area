<?php

namespace App\Entity;

use App\Repository\AttachmentMetadataTypeRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AttachmentMetadataTypeRepository::class)
 */
class AttachmentMetadataType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private ?string $type = null;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="boolean", options={"default":true}) */
    private ?bool $canBePublic = null;

    public function __construct($type, $name, $description, $canBePublic = true)
    {
        $this->type = $type;
        $this->name = $name;
        $this->description = $description;
        $this->canBePublic = true;
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
