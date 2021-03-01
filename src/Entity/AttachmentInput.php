<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Repository\AttachmentInputRepository;
use Doctrine\ORM\Mapping as ORM;

class AttachmentInput
{
    /**
     * @ApiProperty(description="The AttachmentMetadataType")
     */
    private ?string $type;

    /**
     * @ApiProperty(description="The Uuid of the linked event")
     */
    private ?string $eventUuid;
    private bool $isPublic = false;
    /**
     * @ApiProperty(description="e.g. .mp3 or .docx or .doc etc")
     */
    private string $extension = "";

    /**
     * @ApiProperty(description="The hash of the final file, hashed according to hashAlgo")
     */
    private string $hash = "";

    /**
     * @ApiProperty(description="The algorithm of the hash. Must be one of php's hash_algos()")
     */
    private string $hashAlgo = "";

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getEventUuid(): string
    {
        return $this->eventUuid;
    }

    /**
     * @param string $eventUuid
     */
    public function setEventUuid(string $eventUuid): void
    {
        $this->eventUuid = $eventUuid;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    /**
     * @param bool $isPublic
     */
    public function setIsPublic(bool $isPublic): void
    {
        $this->isPublic = $isPublic;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     */
    public function setExtension(string $extension): void
    {
        $this->extension = $extension;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getHashAlgo(): string
    {
        return $this->hashAlgo;
    }

    /**
     * @param string $hashAlgo
     */
    public function setHashAlgo(string $hashAlgo): void
    {
        $this->hashAlgo = $hashAlgo;
    }
}
