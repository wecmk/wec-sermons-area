<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpeakerRepository")
 */
class Speaker
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Organisation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Website;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sermon", mappedBy="speaker")
     */
    private $Sermon;

    public function __construct()
    {
        $this->Sermon = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getOrganisation(): ?string
    {
        return $this->Organisation;
    }

    public function setOrganisation(string $Organisation): self
    {
        $this->Organisation = $Organisation;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->Website;
    }

    public function setWebsite(string $Website): self
    {
        $this->Website = $Website;

        return $this;
    }

    /**
     * @return Collection|Sermon[]
     */
    public function getSermon(): Collection
    {
        return $this->Sermon;
    }

    public function addSermon(Sermon $sermon): self
    {
        if (!$this->Sermon->contains($sermon)) {
            $this->Sermon[] = $sermon;
            $sermon->setSpeaker($this);
        }

        return $this;
    }

    public function removeSermon(Sermon $sermon): self
    {
        if ($this->Sermon->contains($sermon)) {
            $this->Sermon->removeElement($sermon);
            // set the owning side to null (unless already changed)
            if ($sermon->getSpeaker() === $this) {
                $sermon->setSpeaker(null);
            }
        }

        return $this;
    }
}
