<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\Exclude;

use Gedmo\Mapping\Annotation as Gedmo;

use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpeakerRepository")
 */
class Speaker
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
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Organisation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Website;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="Speaker")
     * @Exclude
     */
    private $Event;

    public function __construct()
    {
        $this->Event = new ArrayCollection();
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
     * @return Collection|Event[]
     */
    public function getEvent(): Collection
    {
        return $this->Event;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->Event->contains($event)) {
            $this->Event[] = $event;
            $event->setSpeaker($this);
        }

        return $this;
    }

    public function removeSermon(Event $sermon): self
    {
        if ($this->Event->contains($sermon)) {
            $this->Event->removeElement($sermon);
            // set the owning side to null (unless already changed)
            if ($sermon->getSpeaker() === $this) {
                $sermon->setSpeaker(null);
            }
        }

        return $this;
    }
}
