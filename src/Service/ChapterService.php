<?php

namespace App\Service;

use App\Entity\Chapter;
use App\Repository\BookRepository;
use App\Repository\ChapterRepository;
use App\Util\CustomException\NotFoundException;
use App\Util\CustomException\ParamErrorException;

class ChapterService
{
    private ChapterRepository $chapterRepository;
    private BookRepository $bookRepository;

    public function __construct(ChapterRepository $chapterRepository, BookRepository $bookRepository)
    {
        $this->chapterRepository = $chapterRepository;
        $this->bookRepository = $bookRepository;
    }


    /**
     * @throws NotFoundException
     * @throws ParamErrorException
     */
    function saveChapter(Chapter $chapter): bool
    {
        // 检查bookId是否存在，不存在则返回404
        if ($this->bookRepository->findOneBy(["id" => $chapter->getBookId(), "active" => IS_NOT_DELETED]) == null) {
            throw new NotFoundException("bookId = " . $chapter->getBookId() . " is not found");
        }
        // 检查当前bookId的序号是否存在,存在则返回参数错误
        if ($this->chapterRepository->findOneBy(["bookId" => $chapter->getBookId(), "serialNo" => $chapter->getSerialNo(), "active" => IS_NOT_DELETED]) !== null) {
            throw new ParamErrorException("The chapter serialNo of this book already exists");
        }

        // 计算页数，设置页数
        $content_split = $this->mb_str_split($chapter->getContent(), BOOK_SPLIT_LENGTH);
        $count = count($content_split);
        $chapter->setPageNum($count);

        // 落库
        return $this->chapterRepository->save($chapter, true);
    }

    public function getChapterContent(int $bookId, int $page): array
    {
        $chapters = $this->chapterRepository->findBy(["bookId" => $bookId, "active" => IS_NOT_DELETED], ["serialNo" => "ASC"]);

        $chapterInfo = array();
        for ($x = 0; $x < count($chapters); $x++) {
            $a = array("id" => $chapters[$x]->getId(), "chapterName" => $chapters[$x]->getChapterName(), "bookId" => $chapters[$x]->getBookId());
            $chapterInfo[$x] = $a;
        }

        $pageCache = $page; // 缓存入参page，最后再放回result中

        foreach ($chapters as $c) {
            // 如果获取的页数<=第一章，获取第一章
            if ($page <= $c->getPageNum()) {
                return array(
                    "chapterInfo" => $chapterInfo,
                    "chapterId" => $c->getId(),
                    "page" => $pageCache,
                    "content" => $this->mb_str_split($c->getContent(), BOOK_SPLIT_LENGTH)[$page - 1]
                );
            } else {
                $page = $page - $c->getPageNum();
            }
        }

        // 遍历结束还未返回则为没有此页
        return array(
            "page" => -1,
            "content" => "no page or no next page"
        );
    }


    public function getChapterById(int $id, int $bookId): array
    {
        $chapters = $this->chapterRepository->findBy(["bookId" => $bookId, "active" => IS_NOT_DELETED], ["serialNo" => "ASC"]);

        $chapterInfo = array();
        for ($x = 0; $x < count($chapters); $x++) {
            $a = array("id" => $chapters[$x]->getId(), "chapterName" => $chapters[$x]->getChapterName(), "bookId" => $chapters[$x]->getBookId());
            $chapterInfo[$x] = $a;
        }

        $historyPage = 1;

        foreach ($chapters as $c) {
            // 如果获取的页数<本章，获取本章第一页
            if ($c->getId() == $id) {
                return array(
                    "chapterInfo" => $chapterInfo,
                    "chapterId" => $c->getId(),
                    "page" => $historyPage,
                    "content" => $this->mb_str_split($c->getContent(), BOOK_SPLIT_LENGTH)[0]
                );
            } else {
                $historyPage += $c->getPageNum();
            }
        }

        // 遍历结束还未返回则为没有此章节
        return array(
            "page" => -1,
            "content" => "no chapter"
        );
    }

    /**
     * string to array
     * @param $str
     * @param int $split_length
     * @param string $charset
     * @return array
     */
    function mb_str_split($str, int $split_length = 1, string $charset = "UTF-8"): array
    {
        if (func_num_args() == 1) {
            return preg_split('/(?<!^)(?!$)/u', $str);
        }
        $len = mb_strlen($str, $charset);
        $arr = array();
        for ($i = 0; $i < $len; $i += $split_length) {
            $s = mb_substr($str, $i, $split_length, $charset);
            $arr[] = $s;
        }
        return $arr;
    }


}