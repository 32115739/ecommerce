<?php
// checkout.php
// Logic for checkout can be added here, like storing order details in the database

// Clear cart on successful checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<script>localStorage.removeItem('cart');</script>";
    echo "Thank you for your purchase!";
}
?>
