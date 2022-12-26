<?php

namespace App\Vo;


class BookVo
{

    private ?int $id = null;

    private ?int $categoryId = null;

    private ?string $bootTitle = null;

    private ?string $bookAuthor = null;

    private ?\DateTimeInterface $pubDate = null;

    private ?int $pageNum = null;

    private ?int $status = null;

    private ?\DateTimeInterface $createTime = null;

    private ?\DateTimeInterface $updateTime = null;

    private ?int $active = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getBootTitle(): ?string
    {
        return $this->bootTitle;
    }

    public function setBootTitle(string $bootTitle): self
    {
        $this->bootTitle = $bootTitle;

        return $this;
    }

    public function getBookAuthor(): ?string
    {
        return $this->bookAuthor;
    }

    public function setBookAuthor(?string $bookAuthor): self
    {
        $this->bookAuthor = $bookAuthor;

        return $this;
    }

    public function getPubDate(): ?\DateTimeInterface
    {
        return $this->pubDate;
    }

    public function setPubDate(?\DateTimeInterface $pubDate): self
    {
        $this->pubDate = $pubDate;

        return $this;
    }

    public function getPageNum(): ?int
    {
        return $this->pageNum;
    }

    public function setPageNum(?int $pageNum): self
    {
        $this->pageNum = $pageNum;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

    public function setCreateTime(\DateTimeInterface $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }

    public function getUpdateTime(): ?\DateTimeInterface
    {
        return $this->updateTime;
    }

    public function setUpdateTime(?\DateTimeInterface $updateTime): self
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(int $active): self
    {
        $this->active = $active;

        return $this;
    }
}
