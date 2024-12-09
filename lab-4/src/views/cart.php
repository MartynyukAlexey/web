<?php

require __DIR__ . "/../config.php";
require __DIR__ ."/../data/user.php";
require __DIR__ ."/../data/order.php";

if (!is_auth()) {
    header("Location: /signin.php");
    exit;
}

$user_repository = new UserRepository($pdo);
$user = $user_repository->getById($_SESSION["user_id"]);

$product_repository = new ProductRepository($pdo);

$order_repository = new OrderRepository($pdo);
$orders = $order_repository->getByUserID($_SESSION["user_id"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $operation = $_POST["operation"] ?? null;
    $product_id = $_POST["product"] ?? null;
    $count = $_POST["count"] ?? 1;

    if ($operation == "add") {
        if (isset($_SESSION["cart"][$product_id])) {
            $_SESSION["cart"][$product_id] += $count;
        } else {
            $_SESSION["cart"][$product_id] = $count;
        }

        http_response_code(200);
        exit;
    }

    if ($operation == "remove") {
        if (!isset($_SESSION["cart"][$product_id])) {
            exit;
        }

        if ($_SESSION["cart"][$product_id] <= $count) {
            unset($_SESSION["cart"][$product_id]);
        } else {
            $_SESSION["cart"][$product_id] -= $count;
        }

        http_response_code(200);
        exit;
    }
    
    http_response_code(400);
    exit;
}

$products = [];
foreach ($_SESSION["cart"] as $product_id => $count) {
    $products[] = $product_repository->getById($product_id);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <?php require __DIR__ . "/partials/navigation.php"; ?>

    <?php require __DIR__ . '/partials/products.php'; ?>
</body>
</html>