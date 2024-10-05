<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
