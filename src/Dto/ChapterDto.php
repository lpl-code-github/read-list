<?php

namespace App\Entity;



class ChapterDto
{
    private ?int $bookId = null;

    private ?string $chapterName = null;

    private ?string $content = null;

    private ?int $serialNo = null;

    /**
     * @return int|null
     */
    public function getBookId(): ?int
    {
        return $this->bookId;
    }

    /**
     * @param int|null $bookId
     */
    public function setBookId(?int $bookId): void
    {
        $this->bookId = $bookId;
    }

    /**
     * @return string|null
     */
    public function getChapterName(): ?string
    {
        return $this->chapterName;
    }

    /**
     * @param string|null $chapterName
     */
    public function setChapterName(?string $chapterName): void
    {
        $this->chapterName = $chapterName;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return int|null
     */
    public function getSerialNo(): ?int
    {
        return $this->serialNo;
    }

    /**
     * @param int|null $serialNo
     */
    public function setSerialNo(?int $serialNo): void
    {
        $this->serialNo = $serialNo;
    }


}
