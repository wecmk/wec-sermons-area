<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeriesRepository")
 */
class Series
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
     * @ORM\Column(type="boolean")
     */
    private $Complete;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Sermon", mappedBy="Series")
     */
    private $sermons;

    public function __construct()
    {
        $this->sermons = new ArrayCollection();
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

    public function getComplete(): ?bool
    {
        return $this->Complete;
    }

    public function setComplete(bool $Complete): self
    {
        $this->Complete = $Complete;

        return $this;
    }

    /**
     * @return Collection|Sermon[]
     */
    public function getSermons(): Collection
    {
        return $this->sermons;
    }

    public function addSermon(Sermon $sermon): self
    {
        if (!$this->sermons->contains($sermon)) {
            $this->sermons[] = $sermon;
            $sermon->addSeries($this);
        }

        return $this;
    }

    public function removeSermon(Sermon $sermon): self
    {
        if ($this->sermons->contains($sermon)) {
            $this->sermons->removeElement($sermon);
            $sermon->removeSeries($this);
        }

        return $this;
    }
}
