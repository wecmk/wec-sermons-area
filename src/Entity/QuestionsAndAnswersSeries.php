<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QuestionsAndAnswersSeriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=QuestionsAndAnswersSeriesRepository::class)
 */
class QuestionsAndAnswersSeries
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $current;

    /**
     * @ORM\Column(type="integer")
     */
    private int $number;

    /**
     * @ORM\OneToMany(targetEntity=QuestionsAndAnswers::class, mappedBy="QuestionsAndAnswersSeries")
     */
    private Collection $questionsAndAnswers;

    public function __construct()
    {
        $this->questionsAndAnswers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCurrent(): bool
    {
        return $this->current;
    }

    public function setCurrent(bool $current): self
    {
        $this->current = $current;

        return $this;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection|QuestionsAndAnswers[]
     */
    public function getQuestionsAndAnswers(): Collection
    {
        return $this->questionsAndAnswers;
    }

    public function addQuestionsAndAnswer(QuestionsAndAnswers $questionsAndAnswer): self
    {
        if (!$this->questionsAndAnswers->contains($questionsAndAnswer)) {
            $this->questionsAndAnswers[] = $questionsAndAnswer;
            $questionsAndAnswer->setQuestionsAndAnswersSeries($this);
        }

        return $this;
    }

    public function removeQuestionsAndAnswer(QuestionsAndAnswers $questionsAndAnswer): self
    {
        // set the owning side to null (unless already changed)
        if ($this->questionsAndAnswers->removeElement($questionsAndAnswer) && $questionsAndAnswer->getQuestionsAndAnswersSeries() === $this) {
            $questionsAndAnswer->setQuestionsAndAnswersSeries(null);
        }

        return $this;
    }
}
