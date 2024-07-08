<?php
include 'db_connection.php';
include 'Product.php';
include 'DVD.php';
include 'Book.php';
include 'Furniture.php';

class ProductFactory {
    public static function createProduct($type) {
        $classMap = [
            'book' => 'Book',
            'DVD' => 'DVD',
            'furniture' => 'Furniture'
        ];

        if (array_key_exists($type, $classMap)) {
            $className = $classMap[$type];
            return new $className();
        } else {
            throw new Exception("Invalid product type.");
        }
    }
}

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

    try {
        // Create product object based on type using factory
        $product = ProductFactory::createProduct($type);

        // Set product properties
        $product->setName($name);
        $product->setPrice($price);
        $product->setType($type);
        $product->setAdditionalInfo($additionalInfo);

        // Save product to database
        $product->save();
        
        // Redirect to product list page
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        // Handle errors (invalid product type or database connection errors)
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle case where form data is not received
    echo "Form data not received.";
    exit(); 
}
?>
