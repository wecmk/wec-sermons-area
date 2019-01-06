<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SermonRepository")
 */
class Sermon
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $Date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Apm", inversedBy="sermons")
     */
    private $Apm;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Series", inversedBy="sermons")
     */
    private $Series;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Reading;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $SecondReading;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Title;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Speaker")
     */
    private $Speaker;

    /**
     * @ORM\Column(type="boolean")
     */
    private $corrupt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsPublic;

    /**
     * @ORM\Column(type="text")
     */
    private $Tags;

    /**
     * @ORM\Column(type="text")
     */
    private $PublicComments;

    /**
     * @ORM\Column(type="text")
     */
    private $PrivateComments;

    public function __construct()
    {
        $this->Series = new ArrayCollection();
        $this->Speaker = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getApm(): ?Apm
    {
        return $this->Apm;
    }

    public function setApm(?Apm $Apm): self
    {
        $this->Apm = $Apm;

        return $this;
    }

    /**
     * @return Collection|Series[]
     */
    public function getSeries(): Collection
    {
        return $this->Series;
    }

    public function addSeries(Series $series): self
    {
        if (!$this->Series->contains($series)) {
            $this->Series[] = $series;
        }

        return $this;
    }

    public function removeSeries(Series $series): self
    {
        if ($this->Series->contains($series)) {
            $this->Series->removeElement($series);
        }

        return $this;
    }

    public function getReading(): ?string
    {
        return $this->Reading;
    }

    public function setReading(string $Reading): self
    {
        $this->Reading = $Reading;

        return $this;
    }

    public function getSecondReading(): ?string
    {
        return $this->SecondReading;
    }

    public function setSecondReading(string $SecondReading): self
    {
        $this->SecondReading = $SecondReading;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    /**
     * @return Collection|Speaker[]
     */
    public function getSpeaker(): Collection
    {
        return $this->Speaker;
    }

    public function addSpeaker(Speaker $speaker): self
    {
        if (!$this->Speaker->contains($speaker)) {
            $this->Speaker[] = $speaker;
        }

        return $this;
    }

    public function removeSpeaker(Speaker $speaker): self
    {
        if ($this->Speaker->contains($speaker)) {
            $this->Speaker->removeElement($speaker);
        }

        return $this;
    }

    public function getCorrupt(): ?bool
    {
        return $this->corrupt;
    }

    public function setCorrupt(bool $corrupt): self
    {
        $this->corrupt = $corrupt;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->IsPublic;
    }

    public function setIsPublic(bool $IsPublic): self
    {
        $this->IsPublic = $IsPublic;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->Tags;
    }

    public function setTags(string $Tags): self
    {
        $this->Tags = $Tags;

        return $this;
    }

    public function getPublicComments(): ?string
    {
        return $this->PublicComments;
    }

    public function setPublicComments(string $PublicComments): self
    {
        $this->PublicComments = $PublicComments;

        return $this;
    }

    public function getPrivateComments(): ?string
    {
        return $this->PrivateComments;
    }

    public function setPrivateComments(string $PrivateComments): self
    {
        $this->PrivateComments = $PrivateComments;

        return $this;
    }
}
