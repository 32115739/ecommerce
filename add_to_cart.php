<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tech_store";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from POST request
$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];
$quantity = $_POST['quantity'];

// Insert into the database
$sql = "INSERT INTO cart_items (product_name, product_price, quantity) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sdi", $product_name, $product_price, $quantity);

if ($stmt->execute()) {
    echo "Item added to cart";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>
