<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SpeakerRepository;

/**
 * [#ApiResource    ]
 */
#[ORM\Entity(repositoryClass: SpeakerRepository::class)]
class Teams
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 20)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $link;

    #[ORM\Column(type: 'text')]
    private string $imageAsBase64;

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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getImageAsBase64(): ?string
    {
        return $this->imageAsBase64;
    }

    public function setImageAsBase64(string $imageAsBase64): self
    {
        $this->imageAsBase64 = $imageAsBase64;

        return $this;
    }
}
