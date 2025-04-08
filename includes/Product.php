<?php
require_once 'Database.php';

class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllProducts() {
        $sql = "SELECT * FROM products ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addProduct($name, $description, $price, $image) {
        $sql = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
        return $this->db->query($sql, [$name, $description, $price, $image]);
    }

    public function updateProduct($id, $name, $description, $price, $image) {
        $sql = "UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?";
        return $this->db->query($sql, [$name, $description, $price, $image, $id]);
    }

    public function deleteProduct($id) {
        $sql = "DELETE FROM products WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
} 