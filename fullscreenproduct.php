<?php
session_start();
$buyer_id = $_SESSION["userid"];
include 'conn.php';

// Fetch product_id from URL
$product_id = $_GET['id'];



// Initialize error message
$errormessage = $_SESSION['errormessage'] ?? ''; // Use the session error message if it's set
unset($_SESSION['errormessage']); // Clear the session error message


// Fetch product details based on product_id
$sql = "SELECT image, name, details, price FROM productrecords WHERE id = $product_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <title>Product Details</title>
</head>
<body>
<div class='flex items-start justify-center min-h-screen p-4'> <!-- Start of container div -->
    <?php if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        ?>
        <!-- Left side with product image and border -->
        <div class='w-1/2 pr-8 border-r border-gray-300'>
            <img src='<?php echo $product['image']; ?>' alt='Product Image'
                 class='w-full max-w-2xl max-h-96 object-top mb-4'/>
        </div><!-- End of left side -->

        <!-- Right side with product details -->
        <div class='w-1/2 pl-8'>
            <h1 class='text-2xl text-green-500 font-bold mb-4'><?php echo $product['name']; ?></h1><!-- Product name -->
            <p class='text-green-500 text-sm mb-4'><?php echo $product['details']; ?></p><!-- Product details -->
            <p class='text-lg text-green-500 font-semibold mb-2'>Price: $<?php echo $product['price']; ?></p><!-- Product price with "Price" label -->

            <!-- Container for "Add to Cart" and "Buy Item" buttons side by side -->
            <form action='insertcart.php' method='post' onsubmit='return confirmAddToCart()'>
                <input type='hidden' name='product_id' value='<?php echo $product_id; ?>'> <!-- Include the product_id as a hidden input -->
                <input type='hidden' name='buyer_id' value='<?php echo $buyer_id; ?>'> <!-- Include the buyer_id as a hidden input -->
                <div class='flex items-center'>
                    <button type='submit'
                            class='px-4 py-2 bg-green-500 text-white rounded-lg mr-2 hover:bg-green-900'>Add to Cart
                    </button>
                </div>
            </form>

            <!-- Display the Buy Item button with JavaScript to open the overlay -->
            <div class='flex items-center '>
                <button id='buy-item-button'
                        class='px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-900 mt-0' type='button'>Buy
                    Item
                </button>
            </div>

            <!-- JavaScript function to display a confirmation message for "Add to Cart" -->
            <script>
                function confirmAddToCart() {
                    return confirm('Are you sure you want to add this item to your cart?');
                }
            </script>

            <!-- Text "More Items" above the 10 images -->
            <h2 class='text-xl text-green-500 font-semibold mt-4'>More Items:</h2>

            <!-- Container for the additional 10 images in the same row as much as possible -->
            <div class='flex flex-wrap justify-start'>

                <?php
                // Fetch and display 10 small images from your table with borders
                $sql_images = "SELECT image FROM productrecords LIMIT 10"; // Fetch the first 10 image URLs from your table
                $result_images = $conn->query($sql_images);

                if ($result_images->num_rows > 0) {
                    while ($row = $result_images->fetch_assoc()) {
                        $image_url = $row['image'];
                        echo "<div class='border border-gray-300 p-1 m-2'>";
                        echo "<img src='" . $image_url . "' alt='Additional Product Image' class='w-20 h-auto' />";
                        echo "</div>";
                    }
                }
                ?>

            </div><!-- End of container for the additional 10 images -->
        </div><!-- End of right side -->
        <?php
    } else {
        // Product not found message
        $error_message = 'Product not found';
    }
    ?>

</div><!-- End of container div -->

<!-- Overlay for paymentform.php -->
<div id="overlay" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
    <div class="absolute inset-0 flex items-center justify-center">

        <!-- Payment Form -->
        <div class="container mx-auto p-4 bg-white shadow-md rounded md:w-1/2">
            <!-- Close icon -->
            <div class='flex justify-between items-center mb-4'>
                <a href="paymentform.php" class="flex items-center">
                    <img src="https://flowbite.com/docs/images/logo.svg" class="h-8 mr-3" alt="Flowbite Logo"/>
                    <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">UNTBazaar</span>
                </a>
                <button id='close-overlay'
        data-product-id='<?php echo htmlspecialchars($product_id); ?>'
        class='self-start text-2xl font-bold text-white absolute top-2 right-2 hover:text-gray-300 focus:outline-none'>
    &times;
</button>

            </div>
            
            <!-- Error Message Display -->
           


            <form action="paymentvalidation.php" method='post' class="bg-white shadow-md rounded px-4 py-6 mt-4 md:w-1/2 mx-auto">
                
                <input type='hidden' name='product_id' value='<?php echo htmlspecialchars($product_id); ?>'> <!-- Include the product_id as a hidden input -->
    
                <!-- Error Message Display -->
                <div id="errorMessage" class="mb-4 <?php echo !empty($errormessage) ? 'block' : 'hidden'; ?>">
                <p class='text-red-500 text font-bold'><?php if (!empty($errormessage)) echo htmlspecialchars($errormessage); ?></p>
                </div>
                <label class="block text-green-500 text-sm font-bold mb-2" for="cardNumber">Card number</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="cardNumber" type="text" placeholder="Card Number">
                <div class="mb-4">
                    <label class="block text-green-500 text-sm font-bold mb-2" for="cardName">Name on card</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="cardName" type="text" placeholder="Name on Card">
                </div>
                <div class="flex -mx-4 mb-4">
                    <div class="w-1/2 px-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="cardExpiry">Expiration Date (MM/YY)</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               id="cardExpiry" type="text" placeholder="MM/YY">
                    </div>
                    <div class="w-1/2 px-4">
                        <label class="block text-green-500 text-sm font-bold mb-2" for="cardCVC">Security Code (CVC)</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               id="cardCVC" type="text" placeholder="CVC">
                    </div>
                </div>
                <div class="flex items-center justify-center">
                    <button class="bg-blue-500 hover:bg-green-500 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit">Add Card
                    </button>
                </div>
            </form>
            <p class="text-center text-gray-500 text-xs mt-4">
                &copy;2020 Acme Corp. All rights reserved.
            </p>
        </div>
    </div>
</div>

<script>
    // Function to display the overlay
    function displayOverlay() {
        document.getElementById('overlay').style.display = 'block';
    }

    // Function to hide the overlay and go back to the previous page
    function closeOverlayAndGoBack() {
        document.getElementById('overlay').style.display = 'none';
        window.history.back(); // This will take the user back to the previous page
    }

    document.addEventListener('DOMContentLoaded', function () {
    var errorMessage = document.getElementById('errorMessage');
    var closeButton = document.getElementById('close-overlay');

    // Display the overlay if there's an error message
    if (errorMessage && errorMessage.textContent.trim() !== '') {
        document.getElementById('overlay').style.display = 'block';
    }

    // Attach the event listener to the close button
    if (closeButton) {
        closeButton.addEventListener('click', closeOverlayAndRedirect);
    }

    // Event listener for the Buy Item button
    document.getElementById('buy-item-button').addEventListener('click', function() {
        document.getElementById('overlay').style.display = 'block';
    });
});

// Function to hide the overlay
function hideOverlay() {
    var overlay = document.getElementById('overlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

// Function to handle the closing of the overlay and redirect to product.php with product ID
// Function to handle the closing of the overlay and redirect to product.php with product ID
function closeOverlayAndRedirect() {
    hideOverlay(); // Hide the overlay

    // Fetch the product ID from the data attribute of the close button
    var closeButton = document.getElementById('close-overlay');
    var productId = closeButton.getAttribute('data-product-id');

    // Redirect to the product.php page with the product ID
    if (productId) {
        window.location.href = 'product.php?id=' + encodeURIComponent(productId);
    }
}
</script>


</body>
</html>