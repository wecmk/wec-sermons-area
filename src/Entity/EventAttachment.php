<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventAttachmentRepository")
 */
class EventAttachment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Event", inversedBy="eventAttachments")
     */
    private $Event;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AttachmentMetadata", mappedBy="eventAttachment")
     */
    private $AttachmentMetadata;

    public function __construct()
    {
        $this->Event = new ArrayCollection();
        $this->AttachmentMetadata = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->Event->contains($event)) {
            $this->Event->removeElement($event);
        }

        return $this;
    }

    /**
     * @return Collection|AttachmentMetadata[]
     */
    public function getAttachmentMetadata(): Collection
    {
        return $this->AttachmentMetadata;
    }

    public function addAttachmentMetadata(AttachmentMetadata $attachmentMetadata): self
    {
        if (!$this->AttachmentMetadata->contains($attachmentMetadata)) {
            $this->AttachmentMetadata[] = $attachmentMetadata;
            $attachmentMetadata->setEventAttachment($this);
        }

        return $this;
    }

    public function removeAttachmentMetadata(AttachmentMetadata $attachmentMetadata): self
    {
        if ($this->AttachmentMetadata->contains($attachmentMetadata)) {
            $this->AttachmentMetadata->removeElement($attachmentMetadata);
            // set the owning side to null (unless already changed)
            if ($attachmentMetadata->getEventAttachment() === $this) {
                $attachmentMetadata->setEventAttachment(null);
            }
        }

        return $this;
    }
}
