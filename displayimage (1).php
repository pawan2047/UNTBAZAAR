<?php
include 'conn.php';
// Start the session (this should be at the beginning of the file)
session_start();

// Access the email from the session
$email = $_SESSION['email'];


// SQL query to retrieve the BLOB data
$sql = "SELECT image FROM seller WHERE email = ?"; // Replace 'your_table' and 'id' with your table and 
if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        // Store the result
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Convert the BLOB data to base64 encoded image data
        $imageData = base64_encode($row['image']);
        
        // Embed the image in your HTML
        echo '<td class="py-2 px-4 border-b border-grey-light">
    <img src="data:image/jpeg;base64,' . $imageData . '" alt="Your Image"  class="rounded-full w-10 h-10">
</td>';
    } else {
        echo "No image found for this email.";
    }
    
    // Close the prepared statement
    $stmt->close();
} 


   
$conn->close();

?>