<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="./dist/styles.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold">Product List</h1>
        <div>
            <a id="add-button" href="add_product.php" class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2">ADD</a>
            <button id="mass-delete-button" onclick="deleteProducts()" class="bg-red-500 text-white px-4 py-2 rounded-md">MASS DELETE</button>
        </div>
    </div>
    <hr class="mb-4 my-4 border-t-2 border-gray-600">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php
            //fetch and display products from the database
            include 'db_connection.php';

            // Fetch products from the database
            $sql = "SELECT * FROM products ORDER BY id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<div class='product bg-white p-4 rounded-lg shadow-md relative'>";
                    echo "<input type='checkbox' class='delete-checkbox top-2 left-2' value='" . $row["id"] . "'";
                    
                    // Check the checkbox if test mode is detected
                    if (isset($_GET['test']) && $_GET['test'] === 'true') {
                        echo " checked";
                    }
                    
                    echo ">";
                    echo "<div class='ml-6'>";
                    echo "<p class='text-sm font-semibold'> " . $row["sku"] . "</p>";
                    echo "<p class='text-sm font-semibold'> " . $row["name"] . "</p>";
                    echo "<p class='text-gray-700'> $" . $row["price"] . "</p>";

                    // Display additional information based on product type
                    if ($row["type"] === "DVD") {
                        echo "<p class='text-gray-700'>Size: " . $row["additional_info"] . " MB</p>";
                    } elseif ($row["type"] === "Book") {
                        echo "<p class='text-gray-700'>Weight: " . $row["additional_info"] . " Kg</p>";
                    } elseif ($row["type"] === "Furniture") {
                        echo "<p class='text-gray-700'>Dimensions: " . $row["additional_info"] . "</p>";
                    }

                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-gray-700'>No products found.</p>";
            }
            $conn->close();
        ?>
    </div>

   <script>
    function deleteProducts() {
        var checkboxes = document.getElementsByClassName('delete-checkbox');
        var selectedProductIds = [];

        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                selectedProductIds.push(checkboxes[i].value);
            }
        }

        if (selectedProductIds.length === 0) {
            alert('Please select at least one product to delete.');
            return;
        }

        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'delete_products.php';
        form.id = 'delete-form';

        for (var j = 0; j < selectedProductIds.length; j++) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'product_ids[]';
            input.value = selectedProductIds[j];
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit();
    }
</script>

</body>
</html>
