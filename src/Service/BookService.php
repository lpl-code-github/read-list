<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Util\CustomException\ParamErrorException;
use App\Vo\PageVo;

class BookService
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $repository)
    {
        $this->bookRepository = $repository;
    }

    /**
     * 分页查询所有未删除、未被下架的图书
     */
    public function findBookList(string $searchContent = "", int $page = 1): PageVo
    {
        $paginator = $this->bookRepository->findBookList($searchContent, $page);
        $count = $paginator->count();
        return new PageVo($count, DEFAULT_SIZE, $page, $paginator);
    }

    /**
     * @throws ParamErrorException
     */
    public function saveBook(Book $book): bool
    {
        // 查询是否已经存在
        if ($this->bookRepository->findOneBy(["bookTitle" => $book->getBookTitle(), "bookAuthor" => $book->getBookAuthor()]) !== null) {
            throw new ParamErrorException("The book already exists");
        }
        return $this->bookRepository->save($book,true);
    }
}