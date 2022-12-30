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
     * 新增时 不需要下架图书
     * @throws NotFoundException
     * @throws ParamErrorException
     */
    function saveChapter(Chapter $chapter): bool
    {
        // 检查bookId是否存在，不存在则返回404
        $book = $this->bookRepository->findOneBy(["id" => $chapter->getBookId(), "active" => IS_NOT_DELETED]);
        if ($book == null) {
            throw new NotFoundException("bookId = " . $chapter->getBookId() . " is not found");
        }
        // 检查当前bookId的序号是否存在,存在则返回参数错误
        if ($this->chapterRepository->findOneBy(["bookId" => $chapter->getBookId(), "serialNo" => $chapter->getSerialNo(), "active" => IS_NOT_DELETED]) !== null) {
            throw new ParamErrorException("The chapter serialNo of this book already exists");
        }

        $content_replace = str_replace(' ', '', $chapter->getContent());
        // 去掉空格，防止前端显示问题以及最终字数问题
        $chapter->setContent($content_replace);

        // 计算页数，设置页数
        $content_split = $this->mb_str_split($content_replace, BOOK_SPLIT_LENGTH);
        $count = count($content_split);
        $chapter->setPageNum($count);


        // 更新book表的页数
        $totalPage = 0;
        $chapters = $this->chapterRepository->findBy(["bookId" => $chapter->getBookId(), "active" => IS_NOT_DELETED], ["serialNo" => "ASC"]);
        for ($x = 0; $x < count($chapters); $x++) {
            $totalPage += $chapters[$x]->getPageNum(); // 累加总页数
        }

        $book->setPageNum($totalPage+$count);
//        $this->bookRepository->updateBookPageNum($book);


        // 落库
        return $this->chapterRepository->save($chapter, true);
    }

    /**
     * @throws NotFoundException
     */
    public function getChapterContent(int $bookId, int $page): array
    {
        $chapters = $this->chapterRepository->findBy(["bookId" => $bookId, "active" => IS_NOT_DELETED], ["serialNo" => "ASC"]);

        // 如果第一页都没有 就抛出404
        if ($page==1 && count($chapters)==0){
            throw new NotFoundException("not found content");
        }

        // 总页数
        $totalPage = 0;
        // 嵌套简单的chapter信息
        $chapterInfo = array();
        for ($x = 0; $x < count($chapters); $x++) {
            $a = array("id" => $chapters[$x]->getId(), "chapterName" => $chapters[$x]->getChapterName(), "bookId" => $chapters[$x]->getBookId());
            $chapterInfo[$x] = $a;
            $totalPage += $chapters[$x]->getPageNum(); // 累加总页数
        }

        // 缓存入参page，最后再放回result中
        $pageCache = $page;

        // 取页数对应的内容
        foreach ($chapters as $c) {
            // 如果获取的页数<=第一章，获取第一章
            if ($page <= $c->getPageNum()) {
                return array(
                    "chapterInfo" => $chapterInfo,
                    "chapterId" => $c->getId(),
                    "page" => $pageCache,
                    "totalPage"=> $totalPage,
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

        // 总页数
        $totalPage = 0;
        // 嵌套简单的chapter信息
        $chapterInfo = array();
        for ($x = 0; $x < count($chapters); $x++) {
            $a = array("id" => $chapters[$x]->getId(), "chapterName" => $chapters[$x]->getChapterName(), "bookId" => $chapters[$x]->getBookId());
            $chapterInfo[$x] = $a;
            $totalPage += $chapters[$x]->getPageNum(); // 累加总页数
        }

        $historyPage = 1;

        foreach ($chapters as $c) {
            // 如果获取的页数<本章，获取本章第一页
            if ($c->getId() == $id) {
                return array(
                    "chapterInfo" => $chapterInfo,
                    "chapterId" => $c->getId(),
                    "page" => $historyPage,
                    "totalPage"=> $totalPage,
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