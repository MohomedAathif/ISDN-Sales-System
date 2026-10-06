<?php

class Order {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createOrder($user_id, $items) {

        if (empty($items)) {
            return false;
        }

        try {

            $this->conn->beginTransaction();

            $originalTotal = 0;
            $validItems = [];

            // Validate stock & calculate total
            foreach ($items as $item) {

                // Skip empty or zero quantities
                if (!is_numeric($item['quantity']) || $item['quantity'] <= 0) {
                    continue;
                }

                $checkStock = $this->conn->prepare(
                    "SELECT quantity FROM inventory WHERE product_id = ? LIMIT 1"
                );

                $checkStock->execute([$item['product_id']]);
                $stock = $checkStock->fetch(PDO::FETCH_ASSOC);

                if (!$stock || $stock['quantity'] < $item['quantity']) {
                    throw new Exception("Insufficient stock");
                }

                $subtotal = $item['price'] * $item['quantity'];
                $originalTotal += $subtotal;

                $validItems[] = $item;
            }

            // Ensure at least one valid product was selected
            if ($originalTotal <= 0 || empty($validItems)) {
                throw new Exception("No valid items selected");
            }

            // 2️⃣ Apply promotion if active
            $discountAmount = 0;

            $promoStmt = $this->conn->prepare("
                SELECT discount_percent
                FROM promotions
                WHERE CURDATE() BETWEEN start_date AND end_date
                LIMIT 1
            ");
            $promoStmt->execute();
            $promo = $promoStmt->fetch(PDO::FETCH_ASSOC);

            if ($promo && $promo['discount_percent'] > 0) {
                $discountAmount = ($originalTotal * $promo['discount_percent']) / 100;
            }

            $finalTotal = $originalTotal - $discountAmount;

            // 3️⃣ Estimated delivery date
            $estimatedDate = date('Y-m-d', strtotime('+3 days'));

            // 4️⃣ Insert order
            $orderStmt = $this->conn->prepare("
                INSERT INTO orders 
                (user_id, original_amount, discount_amount, total_amount, status, payment_status, estimated_delivery, created_at)
                VALUES (?, ?, ?, ?, 'pending', 'unpaid', ?, NOW())
            ");

            $orderStmt->execute([
                $user_id,
                $originalTotal,
                $discountAmount,
                $finalTotal,
                $estimatedDate
            ]);

            $order_id = $this->conn->lastInsertId();

            // 5️⃣ Insert order items & update inventory
            foreach ($validItems as $item) {

                $itemStmt = $this->conn->prepare("
                    INSERT INTO order_items 
                    (order_id, product_id, quantity, price)
                    VALUES (?, ?, ?, ?)
                ");

                $itemStmt->execute([
                    $order_id,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                ]);

                $updateStock = $this->conn->prepare("
                    UPDATE inventory
                    SET quantity = quantity - ?
                    WHERE product_id = ?
                ");

                $updateStock->execute([
                    $item['quantity'],
                    $item['product_id']
                ]);
            }

            $this->conn->commit();

            return $order_id;

        } catch (Exception $e) {

            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            return false;
        }
    }

    public function getUserOrders($user_id) {

        $stmt = $this->conn->prepare("
            SELECT *
            FROM orders 
            WHERE user_id = ?
            ORDER BY created_at DESC
        ");

        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}