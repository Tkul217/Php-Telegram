<?php

namespace App\DTO;

class OrderDTO
{
    private $product_id;
    private $product_name;
    private $product_count;
    private $product_price;
    private $created_at;
    private $modified_at;
    private $status;
    private $phone;
    public function __construct(
        array $data
    )
    {
        $now = new \DateTime();

        $nowDate = $now->format('Y-m-d H:i:s');

        $this->product_id = $data['product_id'];
        $this->product_name = $data['product_name'];
        $this->product_count =  $data['product_count'];
        $this->product_price =  $data['product_price'];
        $this->created_at = $nowDate;
        $this->modified_at = $data['modified_at'] ?? null;
        $this->status = $data['status'] ?? 'false';
        $this->phone = $data['phone'];
    }

    public function toArray()
    {
        return [
            $this->product_id,
            $this->product_name,
            $this->product_count,
            $this->product_price,
            $this->created_at,
            $this->modified_at,
            $this->status,
            $this->phone
        ];
    }
}