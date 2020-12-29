<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\SerializedName;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Exclude;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @Gedmo\Loggable
 */
class Event implements CanBeDownloaded
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
     * @ORM\CustomIdGenerator(class="App\Doctrine\CustomUuidGenerator")
     */
    protected UuidInterface $id;

    /**
     * @ORM\Column(type="date")
     */
    private $Date;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $Apm;

    /**
     * @ORM\ManyToMany(targetEntity="Series", inversedBy="Event")
     * @var Collection
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
     * @ORM\Column(type="boolean")
     */
    private $corrupt = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsPublic = false;

    /**
     * @ORM\Column(type="text")
     */
    private $Tags = "";

    /**
     * @ORM\Column(type="text")
     */
    private $PublicComments = "";

    /**
     * @ORM\Column(type="text")
     * @JMS\Exclude
     */
    private $PrivateComments = "";

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Speaker", inversedBy="event")
     */
    private $Speaker;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AttachmentMetadata", mappedBy="event", orphanRemoval=true, cascade={"persist"})
     */
    private $attachmentMetadata;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $legacyId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventUrl", mappedBy="Event", orphanRemoval=true, cascade={"persist"})
     */
    private $eventUrls;

    public function __construct()
    {
        $this->Series = new ArrayCollection();
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->attachmentMetadata = new ArrayCollection();
        $this->eventUrls = new ArrayCollection();
    }

    public function getId(): ?UuidInterface
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

    public function getApm(): ?string
    {
        return $this->Apm;
    }

    public function setId(UuidInterface $id)
    {
        $this->id = $id;
    }

    public function setApm($Apm): self
    {
        $this->Apm = $Apm;

        return $this;
    }

    /**
     * @return Collection
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

    public function getSpeaker(): ?Speaker
    {
        return $this->Speaker;
    }

    public function setSpeaker(?Speaker $speaker): self
    {
        $this->Speaker = $speaker;

        return $this;
    }

    /**
     * @return Collection|AttachmentMetadata[]
     */
    public function getAttachmentMetadata(): Collection
    {
        return $this->attachmentMetadata;
    }

    public function addAttachmentMetadata(AttachmentMetadata $attachmentMetadata): self
    {
        if (!$this->attachmentMetadata->contains($attachmentMetadata)) {
            $this->attachmentMetadata[] = $attachmentMetadata;
            $attachmentMetadata->setEvent($this);
        }

        return $this;
    }

    public function removeAttachmentMetadata(AttachmentMetadata $attachmentMetadata): self
    {
        if ($this->attachmentMetadata->contains($attachmentMetadata)) {
            $this->attachmentMetadata->removeElement($attachmentMetadata);
            // set the owning side to null (unless already changed)
            if ($attachmentMetadata->getEvent() === $this) {
                $attachmentMetadata->setEvent(null);
            }
        }

        return $this;
    }

    public function getLegacyId(): ?string
    {
        return $this->legacyId;
    }

    public function setLegacyId(?string $legacyId): self
    {
        $this->legacyId = $legacyId;

        return $this;
    }

    public function getFilename($extension)
    {
        return $this->getDate()->format("Y-m-d")
                . $this->formatFileNamePart($this->getApm())
                . $this->formatFileNamePart($this->getReading())
                . $this->formatFileNamePart($this->getTitle())
                . $this->formatFileNamePart($this->getSpeaker()->getName())
                . $extension;
    }

    private function formatFileNamePart($stringPart)
    {
        return (!empty($stringPart)) ? " - " . $stringPart : "";
    }

    /**
     * @return Collection|EventUrl[]
     */
    public function getEventUrls(): Collection
    {
        return $this->eventUrls;
    }

    public function addEventUrl(EventUrl $eventUrl): self
    {
        if (!$this->eventUrls->contains($eventUrl)) {
            $this->eventUrls[] = $eventUrl;
            $eventUrl->setEvent($this);
        }

        return $this;
    }

    public function removeEventUrl(EventUrl $eventUrl): self
    {
        if ($this->eventUrls->contains($eventUrl)) {
            $this->eventUrls->removeElement($eventUrl);
            // set the owning side to null (unless already changed)
            if ($eventUrl->getEvent() === $this) {
                $eventUrl->setEvent(null);
            }
        }

        return $this;
    }
}
