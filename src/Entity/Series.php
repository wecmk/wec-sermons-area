<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeriesRepository")
 * @Gedmo\Loggable
 */
class Series
{

    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned
     */
    private $Name;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $Complete;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Sermon", mappedBy="Series")
     */
    private $sermons;

    public function __construct()
    {
        $this->Complete = false;
        $this->sermons = new ArrayCollection();
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    public function getId(): ?\Ramsey\Uuid\Uuid
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
