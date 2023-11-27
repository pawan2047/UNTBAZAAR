<?php
    $base64Image = $_GET['base64Image'];

echo '<img src='.$base64Image.'>';
if (isset($_GET['base64Image'])) {
    // Get the base64 image data from the POST request
    $base64Image = $_GET['base64Image'];
    $base64image = substr(strstr($base64image, ','), 1);
    echo json_encode(['success' => true, 'imageLink' => $base64Image]);
    // Generate a unique filename for the image
    $filename = uniqid(); // You can adjust the file format as needed
    // Specify the directory where you want to save the images
    $uploadDirectory = 'uploads/'; // Create this directory in your project

$data = base64_decode($base64Image);

    if (file_put_contents('uploads/'. $filename . '.png', $data)) {
        
        $imageLink = 'https://pawanpeda.000webhostapp.com/' . $filePath; // Adjust the domain and path
        echo json_encode(['success' => true, 'imageLink' => $imageLink]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving the image']);
    }
}


?>