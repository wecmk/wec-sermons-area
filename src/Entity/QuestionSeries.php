<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionSeriesRepository")
 */
class QuestionSeries
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Current;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuestionQA", mappedBy="questionSeries", orphanRemoval=true)
     */
    private $QuestionQA;

    public function __construct()
    {
        $this->QuestionQA = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getCurrent(): ?bool
    {
        return $this->Current;
    }

    public function setCurrent(bool $Current): self
    {
        $this->Current = $Current;

        return $this;
    }

    /**
     * @return Collection|QuestionQA[]
     */
    public function getQuestionQA(): Collection
    {
        return $this->QuestionQA;
    }

    public function addQuestionQA(QuestionQA $questionQA): self
    {
        if (!$this->QuestionQA->contains($questionQA)) {
            $this->QuestionQA[] = $questionQA;
            $questionQA->setQuestionSeries($this);
        }

        return $this;
    }

    public function removeQuestionQA(QuestionQA $questionQA): self
    {
        if ($this->QuestionQA->contains($questionQA)) {
            $this->QuestionQA->removeElement($questionQA);
            // set the owning side to null (unless already changed)
            if ($questionQA->getQuestionSeries() === $this) {
                $questionQA->setQuestionSeries(null);
            }
        }

        return $this;
    }
    
    /**
     * Get Full name
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->getId() .  ". " . $this->name;
    }
}
