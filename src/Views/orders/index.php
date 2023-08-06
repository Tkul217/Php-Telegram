<?php foreach ($posts as $post) {?>
<div>
    <p><?php echo $post['name'] ?></p>
    <p>Цена: <?php echo $post['price'] ?></p>
    <a href="/orderCreate?product_id=<?php print $post['id'] ?>">Оформить заказ</a>
</div>
<?php }