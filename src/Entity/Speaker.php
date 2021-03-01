<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Id\UuidGenerator;
use Knp\DoctrineBehaviors\Contract\Entity\SoftDeletableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\SoftDeletable\SoftDeletableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use App\Repository\SpeakerRepository;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=SpeakerRepository::class)
 */
class Speaker implements TimestampableInterface, SoftDeletableInterface
{
    public \Doctrine\Common\Collections\ArrayCollection $event;
    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeletableTrait;
    use TimestampableTrait;
    
    /**
     *
     * @ORM\Id
     * @ORM\Column(type="guid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected string $id;

    /**
     * @ORM\Column(type="string", unique=true, length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $organisation = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $website = null;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="Speaker")
     */
    private \Doctrine\Common\Collections\Collection $events;

    public function __construct()
    {
        $this->event = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function getId(): string
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

    public function getOrganisation(): ?string
    {
        return $this->organisation;
    }

    public function setOrganisation(?string $organisation): self
    {
        $this->organisation = $organisation;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

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
            $event->setSpeaker($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        // set the owning side to null (unless already changed)
        if ($this->events->removeElement($event) && $event->getSpeaker() === $this) {
            $event->setSpeaker(null);
        }

        return $this;
    }
}
