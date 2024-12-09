<?php

require __DIR__ . '/../config.php';
require __DIR__ .'/../data/user.php';
require __DIR__ .'/../data/order.php';

if (!is_auth()) {
    header('Location: /signin.php');
    exit;
}

$user_repository = new UserRepository($pdo);
$user = $user_repository->getById($_SESSION['user_id']);

$order_repository = new OrderRepository($pdo);
$orders = $order_repository->getByUserID($_SESSION['user_id']);

$product_repository = new ProductRepository($pdo);

$products = [];
foreach ($orders as $order) {
    $products = array_merge($products, $product_repository->getByOrderId($order->id));
}

$products = array_unique($products, SORT_REGULAR);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <?php require __DIR__ . '/partials/navigation.php'; ?>

    <div class="profile">
        <p>Name: <?= $user->first_name . ' ' . $user->last_name ?></p>
        <p>Email: <?= $user->email ?></p>
        <p>Previously ordered:</p>
    </div>

    <?php require __DIR__ . '/partials/products.php'; ?>
 
</body>
</html>
