<?php
require_once __DIR__ . "/../config/database.php";

class Product {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($name, $description, $price, $category) {
        $stmt = $this->conn->prepare(
            "INSERT INTO products (name, description, price, category) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$name, $description, $price, $category]);
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM products ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}