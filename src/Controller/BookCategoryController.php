<?php

namespace App\Controller;


use App\Service\BookCategoryService;
use App\Util\CustomException\NotFoundException;
use App\Util\CustomException\ParamErrorException;
use App\Util\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BookCategoryController extends AbstractController
{

    /**
     * @throws NotFoundException
     * @throws ParamErrorException
     */
    #[Route('/categories/{id}', name: 'delete_category', requirements: ["id"=>"\d+"], methods: 'DELETE')]
    public function delBookCategoryById(int $id,BookCategoryService $bookCategoryService): JsonResponse
    {
        // 入参校验
        if ($id==null || $id<=0){
            throw new ParamErrorException("parameter id error");
        }

        $result = new Result();
        return $this->json($result->success($bookCategoryService->delBookCategoryById($id)));
    }
}
