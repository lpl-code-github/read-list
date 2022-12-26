<?php

namespace App\Service;

use App\Repository\BookRepository;
use App\Vo\PageVo;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BookService
{
    private BookRepository $repository;

    public function __construct(BookRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 分页查询所有未删除、未被下架的图书
     */
    public function findBookList(string $searchContent="",int $page=1): PageVo
    {
        $paginator = $this->repository->findBookList($searchContent, $page);
        $count = $paginator->count();
        return new PageVo($count,DEFAULT_SIZE,$page,$paginator);
    }
}