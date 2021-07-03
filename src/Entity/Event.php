<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\SoftDeletableInterface;
use Knp\DoctrineBehaviors\Model\SoftDeletable\SoftDeletableTrait;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;

/**
 * @ApiResource(attributes={"order"={"updatedAt": "ASC"}})
 * @ApiFilter(DateFilter::class, properties={"updatedAt"})
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event implements TimestampableInterface, SoftDeletableInterface, CanBeDownloaded
{

    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeletableTrait;
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ApiProperty(identifier=false, readable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="uuid", unique=true)
     * @ApiProperty(identifier=true)
     * @SerializedName("id")
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Column(type="date")
     */
    private ?\DateTimeInterface $date;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private ?string $apm = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $reading = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $secondReading = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $title = '';

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $corrupt = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isPublic = false;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $tags = "";

    /**
     * @ORM\Column(type="text")
     */
    private ?string $publicComments = "";

    /**
     * @ORM\Column(type="text")
     */
    private ?string $privateComments = "";

    /**
     * @ORM\OneToMany(targetEntity=AttachmentMetadata::class, mappedBy="event", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $attachmentMetadata;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $legacyId = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $youTubeLink;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private ?string $speaker = "";

    /**
     * @ORM\ManyToMany(targetEntity=Series::class, inversedBy="events")
     */
    private Collection $series;

    public function __construct(UuidInterface $uuid = null)
    {
        $this->uuid = $uuid ?: Uuid::uuid4();
        $this->date = new \DateTime('NOW');
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->attachmentMetadata = new ArrayCollection();
        $this->series = new ArrayCollection();
        $this->youTubeLink = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Event
    {
        $this->id = $id;

        return $this;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getApm(): ?string
    {
        return $this->apm;
    }

    public function setApm($apm): self
    {
        $this->apm = $apm;

        return $this;
    }

    public function getReading(): ?string
    {
        return $this->reading;
    }

    public function setReading(?string $reading): self
    {
        $this->reading = $reading ?: "";

        return $this;
    }

    public function getSecondReading(): ?string
    {
        return $this->secondReading;
    }

    public function setSecondReading(?string $secondReading): self
    {
        $this->secondReading = $secondReading ?: "";

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title ?: "";

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
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags ?: "";

        return $this;
    }

    public function getPublicComments(): ?string
    {
        return $this->publicComments;
    }

    public function setPublicComments(?string $publicComments): self
    {
        $this->publicComments = $publicComments ?: "";

        return $this;
    }

    public function getPrivateComments(): ?string
    {
        return $this->privateComments;
    }

    public function setPrivateComments(?string $privateComments): self
    {
        $this->privateComments = $privateComments ?: "";

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

    public function filename($extension)
    {
        return $this->getDate()->format("Y-m-d")
                . $this->formatFileNamePart($this->getApm())
                . $this->formatFileNamePart($this->getReading())
                . $this->formatFileNamePart($this->getTitle())
                . $this->formatFileNamePart($this->getSpeaker())
                . $extension;
    }

    private function formatFileNamePart($stringPart)
    {
        return (empty($stringPart)) ? "" : " - " . $stringPart;
    }

    public function getSpeaker(): ?string
    {
        return $this->speaker;
    }

    public function setSpeaker(?string $Speaker): self
    {
        $this->speaker = $Speaker ?: "";

        return $this;
    }

    /**
     * @return Collection|Series[]
     */
    public function getSeries(): Collection
    {
        return $this->series;
    }

    public function addSeries(Series $series): self
    {
        if (!$this->series->contains($series)) {
            $this->series[] = $series;
        }

        return $this;
    }

    public function removeSeries(Series $series): self
    {
        $this->series->removeElement($series);

        return $this;
    }

    public function getYouTubeLink(): ?string
    {
        return $this->youTubeLink;
    }

    public function setYouTubeLink(?string $youTubeLink): self
    {
        $this->youTubeLink = $youTubeLink;

        return $this;
    }

    public function getFilename($extension)
    {
        return $this->getDate()->format('Y-m-d') . "-"
            . $this->getApm() . "-"
            . $this->getReading() . "-"
            . $this->getTitle() . "-"
            . $this->getSpeaker() . "-"
            . $this->getLegacyId();
    }
}
