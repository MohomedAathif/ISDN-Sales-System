<?php
require_once __DIR__ . "/../config/database.php";

class Inventory {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function setStock($product_id, $rdc_location, $quantity) {

        // Check if inventory record already exists
        $stmt = $this->conn->prepare("
            SELECT id FROM inventory 
            WHERE product_id = ? AND rdc_location = ?
        ");

        $stmt->execute([$product_id, $rdc_location]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {

            // Update stock quantity
            $update = $this->conn->prepare("
                UPDATE inventory 
                SET quantity = ?
                WHERE id = ?
            ");

            return $update->execute([
                $quantity,
                $existing['id']
            ]);

        } else {

            // Insert new stock record
            $insert = $this->conn->prepare("
                INSERT INTO inventory (product_id, rdc_location, quantity)
                VALUES (?, ?, ?)
            ");

            return $insert->execute([
                $product_id,
                $rdc_location,
                $quantity
            ]);
        }
    }

    public function getAll() {

        $stmt = $this->conn->prepare("
            SELECT inventory.*, products.name 
            FROM inventory
            JOIN products ON inventory.product_id = products.id
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStockByRDC() {

    $stmt = $this->conn->prepare("
        SELECT products.name, inventory.rdc_location, inventory.quantity
        FROM inventory
        JOIN products ON inventory.product_id = products.id
        ORDER BY inventory.rdc_location
    ");

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}