<?php

namespace App\Controller\Admin;


use App\Entity\Book;
use App\Service\BookService;
use App\Util\CustomException\ParamErrorException;
use App\Util\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookController extends AbstractController
{
    /**
     * @throws ParamErrorException
     */
    #[Route('/admin/books', name: 'admin_find_book_list',methods: 'GET')]
    public function adminFindBookList(Request $request, BookService $bookService): JsonResponse
    {
        // 获取参数
        $page = $request->query->getInt('page', 1);
        $searchContent = $request->query->get("searchContent","");

        // 入参校验
        if ($page<=0){
            throw new ParamErrorException("parameter page error");
        }
        if (strlen($searchContent)>30){
            throw new ParamErrorException("parameter searchContent error");
        }

        $result = new Result();
        return $this->json($result->success($bookService->findBookList($searchContent,$page)));
    }


    /**
     * @throws ParamErrorException
     */
    #[Route('/admin/books', name: 'admin_save_books', methods: 'POST')]
    public function saveChapter(Request $request, BookService $bookService): JsonResponse
    {
        // 获取参数
        try {
            $resource = $request->toArray();
            $book = new Book();
            $book->setImgPath($resource["imgPath"]);
            $book->setBookTitle($resource['bookTitle']);
            $book->setBookAuthor($resource['bookAuthor']);
            $book->setBookDesc($resource['bookDesc']);
            $book->setPubDate(new \DateTimeImmutable($resource['pubDate']));
            $book->setStatus($resource['status']);
        } catch (\Exception $e) {
            throw new ParamErrorException('Missing parameter or parameter format is not JSON');
        }

        // 合法性校验

        $result = new Result();
        return $this->json($result->success($bookService->saveBook($book)));
    }
}