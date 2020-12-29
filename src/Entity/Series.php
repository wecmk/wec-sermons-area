<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\Exclude;

use Gedmo\Mapping\Annotation as Gedmo;

use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\UuidInterface;

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
     * @var UuidInterface
     * @JMS\Type("uuid")
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    protected UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned
     */
    private $Name;

    /**
     * @ORM\Column(type="text")
     */
    private $Description;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $Complete;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Event", mappedBy="Series")
     * @OrderBy({"Date" = "ASC", "Apm" = "ASC"})
     * @Exclude
     */
    private $event;

    public function __construct()
    {
        $this->Complete = false;
        $this->event = new ArrayCollection();
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    public function getId(): ?UuidInterface
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
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->event;
    }

    public function addEvent(Event $sermon): self
    {
        if (!$this->event->contains($sermon)) {
            $this->event[] = $sermon;
            $sermon->addSeries($this);
        }

        return $this;
    }

    public function removeEvent(Event $sermon): self
    {
        if ($this->event->contains($sermon)) {
            $this->event->removeElement($sermon);
            $sermon->removeSeries($this);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }
}
