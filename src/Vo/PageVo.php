<?php

namespace App\Vo;

class PageVo
{
    // 总条数
    private int $total;
    // 每页大小
    private int $size;
    // 当前页
    private int  $current;
    // 数据
    private mixed $data;

    /**
     * @param int $total
     * @param int $size
     * @param int $current
     * @param mixed $data
     */
    public function __construct(int $total, int $size, int $current, mixed $data)
    {
        $this->total = $total;
        $this->size = $size;
        $this->current = $current;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    /**
     * @return int
     */
    public function getCurrent(): int
    {
        return $this->current;
    }

    /**
     * @param int $current
     */
    public function setCurrent(int $current): void
    {
        $this->current = $current;
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData(mixed $data): void
    {
        $this->data = $data;
    }



}