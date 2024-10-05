<?php

namespace App\Services\Books;

use App\Entity\BibleBooks;
use App\Repository\BibleBooksRepository;

class BooksService
{
    public function __construct(private readonly BibleBooksRepository $bibleBooksRepository)
    {
    }

    public function asBibleNameArray()
    {
        $asdf = $this->bibleBooksRepository->findBy([], ['sort' => 'ASC']);
        return array_map(fn(BibleBooks $object) => $object->getBook(), $this->bibleBooksRepository->findBy([], ['sort' => 'ASC']));
    }
}
