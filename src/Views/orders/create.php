<form action="/orderStore" method="post">
    <input hidden type="number" name="product_id" value="<?php print $product['id'] ?>">
    <input hidden type="text" name="product_name" value="<?php print $product['name'] ?>">
    <input hidden type="number" name="product_price" value="<?php print $product['price'] ?>">
    <input hidden type="text" name="status" value="true">

    <p>Наименование товара: <?php echo $product['name']?></p>
    <p>Цена: <?php echo $product['price']?></p>
    <p>Укажите количество товара: </p>
    <input type="number" name="product_count" value="1" required>
    <p>Укажите ваш номер телефона:</p>
    <input type="text" name="phone" required>

    <button type="submit">Оформить заказ</button>
</form>