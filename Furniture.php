<?php

include_once 'Product.php';

class Furniture extends Product {
    protected $height;
    protected $width;
    protected $length;

    public function __construct() {
        parent::__construct();
    }

    public function getHeight() {
        return $this->height;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function getWidth() {
        return $this->width;
    }

    public function setWidth($width) {
        $this->width = $width;
    }

    public function getLength() {
        return $this->length;
    }

    public function setLength($length) {
        $this->length = $length;
    }

    public function save() {
        $mysqli = new mysqli("localhost", "username", "password", "product_db");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        $stmt = $mysqli->prepare("INSERT INTO products (sku, name, price, type, additional_info) VALUES (?, ?, ?, ?, ?)");

        $stmt->bind_param("ssdss", $this->sku, $this->name, $this->price, $this->type, $this->additionalInfo);

        if ($stmt->execute()) {
            $this->id = $mysqli->insert_id;
            echo "Product saved successfully with ID: " . $this->id;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $mysqli->close();
    }
}

?>
