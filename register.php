<!-- register.php -->
<?php
session_start();
$register_error = '';
$register_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tech_store";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $new_username = $_POST['username'];
    $new_password = $_POST['password'];
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Check if username already exists
    $check_user = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_user->bind_param("s", $new_username);
    $check_user->execute();
    $check_user->store_result();

    if ($check_user->num_rows > 0) {
        $register_error = "Username already taken. Please choose another one.";
    } else {
        // Insert new user into the database
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $new_username, $hashed_password);

        if ($stmt->execute()) {
            $register_success = "Registration successful! You can now <a href='login.php'>log in</a>.";
        } else {
            $register_error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $check_user->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register for Tech Store</h2>
    <form action="register.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Register</button>
        <?php
            if ($register_error) echo "<p style='color:red;'>$register_error</p>";
            if ($register_success) echo "<p style='color:green;'>$register_success</p>";
        ?>
    </form>
</body>
</html>
