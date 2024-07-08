<?php

// Include the Product class definition
include_once 'Product.php';

// Define the Furniture class, extending the Product class
class Furniture extends Product {
    protected $height;
    protected $width;
    protected $length;

    // Constructor
    public function __construct() {
        parent::__construct();
    }

    // Getter and setter methods for height
    public function getHeight() {
        return $this->height;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    // Getter and setter methods for width
    public function getWidth() {
        return $this->width;
    }

    public function setWidth($width) {
        $this->width = $width;
    }

    // Getter and setter methods for length
    public function getLength() {
        return $this->length;
    }

    public function setLength($length) {
        $this->length = $length;
    }

    // Save method to store furniture details in the database
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
