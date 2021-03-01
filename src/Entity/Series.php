<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Id\UuidGenerator;

use Knp\DoctrineBehaviors\Contract\Entity\SoftDeletableInterface;
use Knp\DoctrineBehaviors\Model\SoftDeletable\SoftDeletableTrait;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Ramsey\Uuid\UuidInterface;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=SeriesRepository::class)
 */
class Series implements TimestampableInterface, SoftDeletableInterface
{
    public ArrayCollection $event;
    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeletableTrait;
    use TimestampableTrait;

    /**
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $Name = null;

    /**
     * @ORM\Column(type="text")
     */
    private string $Description = '';

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $Complete = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isPublic = false;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="series")
     */
    private Collection $events;

    public function __construct()
    {
        $this->Complete = false;
        $this->event = new ArrayCollection();
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->events = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->addSeries($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeSeries($this);
        }

        return $this;
    }
}
