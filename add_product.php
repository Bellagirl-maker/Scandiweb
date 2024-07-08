<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root"; // default XAMPP MySQL username
    $password = ""; // default XAMPP MySQL password
    $dbname = "product_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve product details from the form
    $sku = $_POST['sku'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $type = $_POST['productType'];
    $additional_info = '';

    if ($type === "DVD") {
        $additional_info = $_POST['size'];
    } elseif ($type === "Book") {
        $additional_info = $_POST['weight'];
    } elseif ($type === "Furniture") {
        $additional_info = $_POST['height'] . "x" . $_POST['width'] . "x" . $_POST['length'];
    }

    // Check if SKU already exists
    $check_sql = "SELECT COUNT(*) AS count FROM products WHERE sku = ?";
    $check_stmt = $conn->prepare($check_sql);
    if ($check_stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $check_stmt->bind_param("s", $sku);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $count = $check_result->fetch_assoc()['count'];
    $check_stmt->close();

    if ($count > 0) {
        echo "Error: SKU already exists. Please choose a different SKU.";
        exit(); // Exit if SKU already exists
    }

    // Insert product into the database
    $insert_sql = "INSERT INTO products (sku, name, price, type, additional_info) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssdss", $sku, $name, $price, $type, $additional_info);

    if ($stmt->execute()) {
        // Redirect to the product listing page
        header("Location: index.php");
        exit(); // Make sure to exit after the header redirection
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="./dist/styles.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Product Add</h1>
            <div>
                <button type="submit" onclick="saveProduct()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Save
                </button>
                <button type="button" onclick="cancelAdd()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-4">
                    Cancel
                </button>
            </div>
        </div>
        <hr class="my-4 border-t-2 border-gray-600">
        <form id="product_form" action="add_product.php" method="POST" onsubmit="return validateForm()" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label for="sku" class="block text-gray-700 text-sm font-bold mb-2">SKU:</label>
                <input type="text" id="sku" name="sku" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price ($):</label>
                <input type="number" id="price" name="price" min="0.01" step="0.01" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="productType" class="block text-gray-700 text-sm font-bold mb-2">Product Type:</label>
                <select id="productType" name="productType" onchange="showProductAttributes()" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="DVD">DVD</option>
                    <option value="Book">Book</option>
                    <option value="Furniture">Furniture</option>
                </select>
            </div>

            <div id="dvdAttributes" class="mb-4" style="display: none;">
                <label for="size" class="block text-gray-700 text-sm font-bold mb-2">Size (MB):</label>
                <input type="number" id="size" name="size" min="1" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p id="sizeDescription" class="text-gray-600 text-xs italic">Please, provide size (in MB)</p>
            </div>

            <div id="bookAttributes" class="mb-4" style="display: none;">
                <label for="weight" class="block text-gray-700 text-sm font-bold mb-2">Weight (Kg):</label>
                <input type="number" id="weight" name="weight" min="0.1" step="0.1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p id="weightDescription" class="text-gray-600 text-xs italic">Please, provide weight (in Kg)</p>
            </div>

            <div id="furnitureAttributes" class="mb-4" style="display: none;">
                <label for="height" class="block text-gray-700 text-sm font-bold mb-2">Height (cm):</label>
                <input type="number" id="height" name="height" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <label for="width" class="block text-gray-700 text-sm font-bold mb-2">Width (cm):</label>
                <input type="number" id="width" name="width" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <label for="length" class="block text-gray-700 text-sm font-bold mb-2">Length (cm):</label>
                <input type="number" id="length" name="length" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p id="dimensionsDescription" class="text-gray-600 text-xs italic">Please, provide dimensions (HxWxL in cm)</p>
            </div>

            <div id="notification" style="display: none;" class="text-red-500 text-xs italic mb-4"></div>
        </form>
    </div>

    <script>
        function saveProduct() {
            if (validateForm()) {
                document.getElementById("product_form").submit();
            } else {
                displayNotification("Please, submit required data.");
            }
        }
        function showProductAttributes() {
            var productType = document.getElementById("productType").value;
            var dvdAttributes = document.getElementById("dvdAttributes");
            var bookAttributes = document.getElementById("bookAttributes");
            var furnitureAttributes = document.getElementById("furnitureAttributes");

            // Hide all attribute sections
            dvdAttributes.style.display = "none";
            bookAttributes.style.display = "none";
            furnitureAttributes.style.display = "none";

            // Show attribute section based on selected product type
            if (productType === "DVD") {
                dvdAttributes.style.display = "block";
            } else if (productType === "Book") {
                bookAttributes.style.display = "block";
            } else if (productType === "Furniture") {
                furnitureAttributes.style.display = "block";
            }
        }

        function validateForm() {
            var sku = document.getElementById("sku").value;
            var name = document.getElementById("name").value;
            var price = document.getElementById("price").value;
            var productType = document.getElementById("productType").value;

            if (sku === "" || name === "" || price === "") {
                displayNotification("Please, submit required data");
                return false;
            }

            if (isNaN(price) || price <= 0) {
                displayNotification("Please, provide the price in a valid format");
                return false;
            }

            if (productType === "DVD") {
                var size = document.getElementById("size").value;
                if (isNaN(size) || size <= 0) {
                    displayNotification("Please, provide the size in a valid format");
                    return false;
                }
            } else if (productType === "Book") {
                var weight = document.getElementById("weight").value;
                if (isNaN(weight) || weight <= 0) {
                    displayNotification("Please, provide the weight in a valid format");
                    return false;
                }
            } else if (productType === "Furniture") {
                var height = document.getElementById("height").value;
                var width = document.getElementById("width").value;
                var length = document.getElementById("length").value;
                if (isNaN(height) || height <= 0 || isNaN(width) || width <= 0 || isNaN(length) || length <= 0) {
                    displayNotification("Please, provide dimensions in a valid format");
                    return false;
                }
            }

            return true;
        }

        function displayNotification(message) {
            var notification = document.getElementById("notification");
            notification.innerHTML = message;
            notification.style.display = "block";
        }

        function cancelAdd() {
            window.location.href = "index.php";
        }
        window.onload = function() {
            showProductAttributes();
        }
    </script>
</body>
</html>
