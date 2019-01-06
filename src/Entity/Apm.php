<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApmRepository")
 */
class Apm
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $Apm;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sermon", mappedBy="Apm")
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

    public function getApm(): ?string
    {
        return $this->Apm;
    }

    public function setApm(string $Apm): self
    {
        $this->Apm = $Apm;

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
            $sermon->setApm($this);
        }

        return $this;
    }

    public function removeSermon(Sermon $sermon): self
    {
        if ($this->sermons->contains($sermon)) {
            $this->sermons->removeElement($sermon);
            // set the owning side to null (unless already changed)
            if ($sermon->getApm() === $this) {
                $sermon->setApm(null);
            }
        }

        return $this;
    }
}
