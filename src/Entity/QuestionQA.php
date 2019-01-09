<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionQARepository")
 */
class QuestionQA
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Answer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $PublishDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuestionSeries", inversedBy="QuestionQA")
     * @ORM\JoinColumn(nullable=false)
     */
    private $questionSeries;

    public function __construct()
    {
        $this->Series = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->Number;
    }

    public function setNumber(int $Number): self
    {
        $this->Number = $Number;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->Question;
    }

    public function setQuestion(string $Question): self
    {
        $this->Question = $Question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->Answer;
    }

    public function setAnswer(string $Answer): self
    {
        $this->Answer = $Answer;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->PublishDate;
    }

    public function setPublishDate(\DateTimeInterface $PublishDate): self
    {
        $this->PublishDate = $PublishDate;

        return $this;
    }

    public function getQuestionSeries(): ?QuestionSeries
    {
        return $this->questionSeries;
    }

    public function setQuestionSeries(?QuestionSeries $questionSeries): self
    {
        $this->questionSeries = $questionSeries;

        return $this;
    }
}
