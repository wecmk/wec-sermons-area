<?php

namespace App\Services\Books;

use App\Entity\BibleBooks;
use App\Repository\BibleBooksRepository;

class BooksService
{
    private BibleBooksRepository $bibleBooksRepository;

    public function __construct(BibleBooksRepository $bibleBooksRepository)
    {
        $this->bibleBooksRepository = $bibleBooksRepository;
    }

    public function asBibleNameArray()
    {
        $asdf = $this->bibleBooksRepository->findBy([], ['sort' => 'ASC']);
        return array_map(function (BibleBooks $object) {
            return $object->getBook();
        }, $this->bibleBooksRepository->findBy([], ['sort' => 'ASC']));
    }
}
