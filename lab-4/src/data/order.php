<?php 

require_once __DIR__ . '/product.php';

class OrderStatus {
    const IN_PROGRESS = 'progress';
    const COMPLETED = 'completed';
    const CANCELED = 'canceled';
}

class Order {
    public int $id;
    public int $user_id;
    public string $status;
    public DateTime $created_at;
    public DateTime $updated_at;
}

class OrderRepository {
    private PDO $connection;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    public function getByUserID(int $user_id): array {
        $stmt = $this->connection->prepare("
            SELECT *
              FROM orders
             WHERE user_id = :user_id
        ");

        $stmt->bindValue(':user_id', $user_id,   PDO::PARAM_INT);
        
        $stmt->execute();

        $rawResult = $stmt->fetchAll();

        $orders = [];
        foreach ($rawResult as $row) {
            $order = new Order();
            $order->id = $row['id'];
            $order->user_id = $row['user_id'];
            $order->status = $row['status'];
            $order->created_at = new DateTime($row['created_at']);
            $order->updated_at = new DateTime($row['updated_at']);

            $orders[] = $order;
        }

        return $orders;
    }

    public function getByUserIDWithStatus(int $user_id, string $status): array {
        $stmt = $this->connection->prepare("
            SELECT *
              FROM orders
             WHERE user_id = :user_id
               AND status = :status
        ");

        $stmt->bindValue(':user_id', $user_id,   PDO::PARAM_INT);
        $stmt->bindValue(':status',  $status,    PDO::PARAM_STR);
        
        $stmt->execute();

        $rawResult = $stmt->fetchAll();

        $orders = [];
        foreach ($rawResult as $row) {
            $order = new Order();
            $order->id = $row['id'];
            $order->user_id = $row['user_id'];
            $order->status = $row['status'];
            $order->created_at = new DateTime($row['created_at']);
            $order->updated_at = new DateTime($row['updated_at']);

            $orders[] = $order;
        }

        return $orders;
    }
}