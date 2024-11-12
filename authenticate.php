<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tech_store";

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for a connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve username and password from the POST request
if (isset($_POST['username']) && isset($_POST['password'])) {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Prepare and execute a query to find the user in the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found and verify the password
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($inputPassword, $user['password'])) {
            // Store user info in the session and redirect to the homepage
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit();
        } else {
            // Password incorrect
            echo "<p>Incorrect password. <a href='login.php'>Try again</a></p>";
        }
    } else {
        // Username not found
        echo "<p>Username not found. <a href='login.php'>Try again</a></p>";
    }
    $stmt->close();
} else {
    // Redirect to login page if accessed directly
    header("Location: login.php");
    exit();
}

// Close the database connection
$conn->close();
?>
