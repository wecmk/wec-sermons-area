<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QuestionsAndAnswersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=QuestionsAndAnswersRepository::class)
 */
class QuestionsAndAnswers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $answer;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $publishDate;

    /**
     * @ORM\ManyToOne(targetEntity=QuestionsAndAnswersSeries::class, inversedBy="questionsAndAnswers")
     */
    private QuestionsAndAnswersSeries $QuestionsAndAnswersSeries;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publishDate;
    }

    public function setPublishDate(\DateTimeInterface $publishDate): self
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    public function getQuestionsAndAnswersSeries(): QuestionsAndAnswersSeries
    {
        return $this->QuestionsAndAnswersSeries;
    }

    public function setQuestionsAndAnswersSeries(QuestionsAndAnswersSeries $QuestionsAndAnswersSeries): self
    {
        $this->QuestionsAndAnswersSeries = $QuestionsAndAnswersSeries;

        return $this;
    }
}
