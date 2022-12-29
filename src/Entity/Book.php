<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $bookTitle = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $bookAuthor = null;

    #[ORM\Column(nullable: true)]
    private ?int $pageNum = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE,insertable:false)]
    private ?\DateTimeInterface $createTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true,insertable:false)]
    private ?\DateTimeInterface $updateTime = null;

    #[ORM\Column(type: Types::SMALLINT,insertable:false)]
    private ?int $active = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgPath = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $pubDate = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $bookDesc = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getBookTitle(): ?string
    {
        return $this->bookTitle;
    }

    /**
     * @param string|null $bookTitle
     */
    public function setBookTitle(?string $bookTitle): void
    {
        $this->bookTitle = $bookTitle;
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

    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    public function setImgPath(?string $imgPath): self
    {
        $this->imgPath = $imgPath;

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

    public function getBookDesc(): ?string
    {
        return $this->bookDesc;
    }

    public function setBookDesc(?string $bookDesc): self
    {
        $this->bookDesc = $bookDesc;

        return $this;
    }
}
