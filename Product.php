<?php

class Product {
    protected $id;
    protected $sku;
    protected $name;
    protected $price;
    protected $type;
    protected $additionalInfo;

    // Getter and setter methods
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
        // Establish connection to the database
        $mysqli = new mysqli("localhost", "username", "password", "product_db");

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Prepare SQL statement
        $stmt = $mysqli->prepare("INSERT INTO products (sku, name, price, type, additional_info) VALUES (?, ?, ?, ?, ?)");

        // Bind parameters
        $stmt->bind_param("ssdss", $this->sku, $this->name, $this->price, $this->type, $this->additionalInfo);

        // Execute the statement
        if ($stmt->execute()) {
            // Product saved successfully
            $this->id = $mysqli->insert_id;
            echo "Product saved successfully with ID: " . $this->id;
        } else {
            // Error occurred while saving the product
            echo "Error: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
        $mysqli->close();
    }
}

?>
