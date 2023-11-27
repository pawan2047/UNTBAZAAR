<?php
include 'conn.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    // Retrieve the product ID and buyer ID from the form submission
    $product_id = $_POST['product_id'];
    $buyer_id = $_POST['buyer_id'];

    // Use prepared statements to insert data into the "cart" table
    $sql = "INSERT INTO cart (productid, buyerid) VALUES (?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $product_id, $buyer_id);

        // Attempt to execute the statement
        if ($stmt->execute()) {
            // Cart item added successfully
            echo "Product added to cart successfully.";
            echo "<br>Debug: Attempted to add buyer ID: $buyer_id and product ID: $product_id";
        } else {
            // Error occurred while adding to cart
            echo "Error: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // Handle the error if the statement couldn't be prepared
        echo "Error: " . $conn->error;
    }
} 


else {
    // Redirect to a different page or display an error message if the form was not submitted
    echo "Form not submitted.";
}

// Close the database connection
$conn->close();
?>



?>