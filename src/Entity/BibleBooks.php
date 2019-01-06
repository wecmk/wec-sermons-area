<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BibleBooksRepository")
 */
class BibleBooks
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $book;

    /**
     * @ORM\Column(type="integer")
     */
    private $sort;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?string
    {
        return $this->book;
    }

    public function setBook(string $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }
}
