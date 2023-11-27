<?php
include 'conn.php';

$buyer_id = $_COOKIE['userid'] ?? null;

if ($buyer_id !== null) {
    // Prepare SQL to select product IDs from the cart table
    $sqlCart = "SELECT productid FROM cart WHERE buyerid = ?";
    if ($stmtCart = $conn->prepare($sqlCart)) {
        // Bind the buyer_id to the prepared statement
        $stmtCart->bind_param("i", $buyer_id);
        $stmtCart->execute();
        $resultCart = $stmtCart->get_result();

        // Initialize an array to store product IDs
        $productIDs = [];
        while ($rowCart = $resultCart->fetch_assoc()) {
            array_push($productIDs, $rowCart['productid']);
        }
        $stmtCart->close();

        // Check if there are product IDs to display
        if (count($productIDs) > 0) {
            // Convert product IDs array to comma-separated string for SQL IN clause
            $productIDsString = implode(',', $productIDs);

            // SQL to select images from productrecords table
            $sqlProduct = "SELECT image, name, id FROM productrecords WHERE id IN ($productIDsString)";
            $resultProduct = $conn->query($sqlProduct);

            if ($resultProduct->num_rows > 0) {
                echo "<div class='flex flex-row flex-wrap gap-4 mt-8'>";
                while ($rowProduct = $resultProduct->fetch_assoc()) {
                    echo "<div class='relative border border-gray-300 p-2 w-64'>";
                    echo "<img src='" . $rowProduct['image'] . "' alt='Product Image' class='h-48 w-full object-cover'>";
                    echo "<div class='absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300'>";
                    echo "<a href='removeFromCart.php?productid=" . $rowProduct['id'] . "' class='text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded mb-2'>Remove from Cart</a>";
                    echo "<a href='checkout.php?productid=" . $rowProduct['id'] . "' class='text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded'>Proceed to Checkout</a>";
                    echo "</div>";
                    echo "<p class='text-center mt-2 break-words'>" . htmlspecialchars($rowProduct['name']) . "</p>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                // No products found in the cart
                echo "No products found in your cart.";
            }
        } else {
            // Cart is empty
            echo "Cart is empty";
        }
    } else {
        echo "Error preparing SQL statement: " . $conn->error;
    }
} else {
    echo "Buyer ID is not set.";
}

$conn->close();
?>
