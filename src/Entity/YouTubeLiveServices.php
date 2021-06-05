<?php

namespace App\Entity;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     itemOperations={
 *         "get"={
 *             "method"="GET",
 *             "controller"=NotFoundAction::class,
 *             "read"=false,
 *             "outputClass"=false,
 *         },
 *     },
 *     collectionOperations={"get"}
 * )
 */
class YouTubeLiveServices
{
    private $id;

    private $nextWeekAm;

    private $nextWeekPm;

    private $lastWeekAm;

    private $lastWeekPm;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @ApiProperty(identifier=true)
     */
    public function getId()
    {
        return $this->id;
    }

    public function getNextWeekAm(): ?string
    {
        return $this->nextWeekAm;
    }

    public function setNextWeekAm(string $nextWeekAm): self
    {
        $this->nextWeekAm = $nextWeekAm;

        return $this;
    }

    public function getNextWeekPm(): ?string
    {
        return $this->nextWeekPm;
    }

    public function setNextWeekPm(string $nextWeekPm): self
    {
        $this->nextWeekPm = $nextWeekPm;

        return $this;
    }

    public function getLastWeekAm(): ?string
    {
        return $this->lastWeekAm;
    }

    public function setLastWeekAm(string $lastWeekAm): self
    {
        $this->lastWeekAm = $lastWeekAm;

        return $this;
    }

    public function getLastWeekPm(): ?string
    {
        return $this->lastWeekPm;
    }

    public function setLastWeekPm(string $lastWeekPm): self
    {
        $this->lastWeekPm = $lastWeekPm;

        return $this;
    }
}
