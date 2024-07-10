<?php

ob_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "id22409566_root";
    $password = "AdMiN@24";
    $dbname = "id22409566_product_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve product IDs to delete
    $product_ids = $_POST['product_ids'];
    
    if (!empty($product_ids)) {
        // Prepare a delete statement
        $ids = implode(',', array_map('intval', $product_ids));
        $sql = "DELETE FROM products WHERE id IN ($ids)";
        
        if ($conn->query($sql) === TRUE) {
            echo "Products deleted successfully";
        } else {
            echo "Error deleting products: " . $conn->error;
        }
    } else {
        echo "No products selected for deletion.";
    }

    $conn->close();

    // Redirect back to the product listing page
    header("Location: index.php");
    exit();
}

ob_end_flush();
?>
