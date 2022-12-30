<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Service\ChapterService;
use App\Util\CustomException\NotFoundException;
use App\Util\CustomException\ParamErrorException;
use App\Util\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChapterController extends AbstractController
{
    /**
     * @throws ParamErrorException
     * @throws NotFoundException
     */
    #[Route('/chapters', name: 'get_chapter_content', methods: 'GET')]
    public function getChapterContent(Request $request, ChapterService $chapterService): JsonResponse
    {
        // 获取参数
        try {
            $bookId = $request->query->getInt("bookId");
            $page = $request->query->getInt("page", 1);
        } catch (\Exception $e) {
            throw new ParamErrorException('Missing parameter bookId or parameter error');
        }

        // 合法性校验
        if ($page <= 0 or $bookId <= 0) {
            throw new ParamErrorException('parameter error');
        }

        $result = new Result();
        return $this->json($result->success($chapterService->getChapterContent($bookId, $page)));
    }

    /**
     * @throws ParamErrorException
     */
    #[Route('/chapters/{id}', name: 'get_chapter_by_id', requirements: ["id" => "\d+"], methods: 'GET')]
    public function getChapterById(int $id, Request $request, ChapterService $chapterService): JsonResponse
    {
        // 获取参数
        try {
            $bookId = $request->query->getInt("bookId");
        } catch (\Exception $e) {
            throw new ParamErrorException('Missing parameter bookId or parameter error');
        }

        // 入参校验
        if ($id == null || $id <= 0) {
            throw new ParamErrorException("parameter id error");
        }
        if ($bookId <= 0) {
            throw new ParamErrorException('parameter bookId error');
        }

        $result = new Result();
        return $this->json($result->success($chapterService->getChapterById($id,$bookId)));
    }
}