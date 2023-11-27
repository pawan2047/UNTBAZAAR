<?php
include 'conn.php';

$sql = "SELECT image, name FROM productrecords ORDER BY RAND() LIMIT 6";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4'>";
    while ($row = $result->fetch_assoc()) {
        echo "<div class='border rounded p-2'>";
        echo "<a href='login.php'><img src='" . $row['image'] . "' alt='Product Image' class='max-w-xs h-auto mx-auto mb-2' /></a>";
        echo "<p class='text-sm text-green-500 hover:text-red-500  text-center'><a href='#'>" . $row['name'] . "</a></p>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "No images found";
}

$conn->close();
?>




