<?php
include 'db_connection.php';
include 'Product.php';
include 'DVD.php';
include 'Book.php';
include 'Furniture.php';

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $price = $_POST['price'];
    $type = $_POST['productType']; 
    $additionalInfo = ''; 

    // Check if additionalInfo field exists in the POST data
    if (isset($_POST['additionalInfo'])) {
        $additionalInfo = $_POST['additionalInfo'];
    }

    // Create product object based on type
    if ($type === 'book') {
        $product = new Book();
    } elseif ($type === 'DVD') {
        $product = new DVD();
    } elseif ($type === 'furniture') {
        $product = new Furniture(); 
    } else {
        // Handle invalid product type
        echo "Invalid product type.";
        exit(); 
    }

    // Set product properties
    $product->setName($name);
    $product->setPrice($price);
    $product->setType($type);
    $product->setAdditionalInfo($additionalInfo);

    // Save product to database
    try {
        $product->save();
        // Redirect to product list page
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        // Handle database connection errors
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle case where form data is not received
    echo "Form data not received.";
    exit(); 
}
?>
