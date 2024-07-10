<?php

class Product {
    protected $id;
    protected $sku;
    protected $name;
    protected $price;
    protected $type;
    protected $additionalInfo;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getSku() {
        return $this->sku;
    }

    public function setSku($sku) {
        $this->sku = $sku;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getAdditionalInfo() {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo($additionalInfo) {
        $this->additionalInfo = $additionalInfo;
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
