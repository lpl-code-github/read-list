<?php

namespace App\Controller;


use App\Service\BookService;
use App\Util\CustomException\ParamErrorException;
use App\Util\Result;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @throws ParamErrorException
     */
    #[Route('/books', name: 'findBookList',methods: 'GET')]
    public function findBookList(Request $request, BookService $bookService): JsonResponse
    {
        // 获取参数
        $page = $request->query->getInt('page', 1);
        $searchContent = $request->query->get("searchContent","");

        // 入参校验
        if ($page<=0){
            throw new ParamErrorException("parameter id error");
        }
        if (strlen($searchContent)>30){
            throw new ParamErrorException("parameter id error");
        }

        $result = new Result();
        return $this->json($result->success($bookService->findBookList($searchContent,$page)));
    }
}