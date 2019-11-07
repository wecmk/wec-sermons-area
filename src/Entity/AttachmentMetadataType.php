<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AttachmentMetadataTypeRepository")
 */
class AttachmentMetadataType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=30)
     */
    private $type;
    
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", options={"default":true}))
     */
    private $canBePublic;

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
