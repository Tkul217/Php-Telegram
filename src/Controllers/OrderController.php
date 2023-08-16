<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Database\DatabaseConnection;
use App\DTO\OrderDTO;
use App\Services\TelegramService;

class OrderController extends Controller
{
    public function index()
    {
        $sql = "SELECT * FROM products";

        $posts = $this->conn->connection()?->query($sql)->fetchAll();

        $this->view->generate('orders/index.php', 'template_view.php', [
            'posts' => $posts
        ]);
    }
    public function create()
    {
        $sql = "SELECT * FROM products WHERE id = " . $_GET['product_id'];

        $product = $this->conn->connection()?->query($sql)->fetch();

        $this->view->generate('orders/create.php', 'template_view.php', [
            'product' => $product
        ]);
    }

    public function store()
    {
        try {
            $conn = $this->conn->connection();

            $sql = "INSERT INTO orders (
                    product_id, product_name, product_count, product_price, created_at, modified_at, status, phone
) VALUES (
          :product_id, :product_name, :product_count, :product_price, :created_at, :modified_at, :status, :phone
)";

            $data = new OrderDTO(
                $_POST
            );

            $conn?->prepare($sql)->execute(
              $data->toArray()
            );

            $service = new TelegramService();

            file_get_contents($service->sendMessage(
                5530349508,
                'Заказ: ' . $data->product_name . ' был создан с идентификатором ' . $conn->lastInsertId() . ' на сумму ' . $data->product_price
            ));
        }
        catch (\PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }

        header("Location: https://morcynkk.ru/");

        die();
    }
}