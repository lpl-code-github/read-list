<?php

namespace App\Service;

use App\Repository\BookCategoryRepository;
use App\Util\CustomException\NotFoundException;

class BookCategoryService
{
    private BookCategoryRepository $repository;

    public function __construct(BookCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws NotFoundException
     */
    public function delBookCategoryById(int $id): bool
    {
        $bookCategory = $this->repository->find($id);
        // 如果查询到为null，删除失败
        if ($bookCategory == null) {
            throw new NotFoundException("not found id=".$id);
        }


        // 幂等性考虑 如果查询到的“分类”已经为被删除状态，直接返回true
        if ($bookCategory->getActive() == IS_DELETED) {
//            $a = 1/0;
            return true;
        }

        // 否则 更新此id的“分类”的is_delete字段为1
        return $this->repository->remove($bookCategory, true);
    }
}