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
        $conn = new DatabaseConnection();

        $sql = "SELECT * FROM products";

        $posts = $conn->connection()?->query($sql)->fetchAll();

        $this->view->generate('orders/index.php', 'template_view.php', [
            'posts' => $posts
        ]);
    }
    public function create()
    {
        $conn = new DatabaseConnection();

        $sql = "SELECT * FROM products WHERE id = " . $_GET['product_id'];

        $product = $conn->connection()?->query($sql)->fetch();

        $this->view->generate('orders/create.php', 'template_view.php', [
            'product' => $product
        ]);
    }

    public function store()
    {
        try {
            $conn = new DatabaseConnection();

            $sql = "INSERT INTO orders (
                    product_id, product_name, product_count, product_price, created_at, modified_at, status, phone
) VALUES (
          ?, ?, ?, ?, ?, ?, ?, ?
)";

            $data = new OrderDTO(
                $_POST
            );

            $conn->connection()?->prepare($sql)->execute(
              $data->toArray()
            );

            $service = new TelegramService();

            file_get_contents($service->sendMessage(
                5530349508,
                'Заказ ' . $_POST['product_name'] . ' Был создан'
            ));
        }
        catch (\PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }

        header("Location: https://morcynkk.ru/");

        die();
    }
}