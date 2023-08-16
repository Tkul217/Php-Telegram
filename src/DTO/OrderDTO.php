<?php

namespace App\DTO;

class OrderDTO
{
    public mixed $product_id;
    public mixed $product_name;
    public mixed $product_count;
    public mixed $product_price;
    public mixed $created_at;
    public mixed $modified_at;
    public mixed $status;
    public mixed $phone;
    public function __construct(
        array $data
    )
    {
        $now = new \DateTime();

        $nowDate = $now->format('Y-m-d H:i:s');

        $this->product_id = $data['product_id'] ?? null;
        $this->product_name = $data['product_name'] ?? null;
        $this->product_count =  $data['product_count'] ?? null;
        $this->product_price =  $data['product_price'] ?? null;
        $this->created_at = $nowDate ?? null;
        $this->modified_at = $data['modified_at'] ?? null;
        $this->status = $data['status'] ?? 'false';
        $this->phone = $data['phone'] ?? null;
    }

    public function toArray()
    {
        return (array) $this;
    }
}