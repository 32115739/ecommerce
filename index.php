<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Store</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js" defer></script>
</head>
<body>
<header>
        <div class="header-content">
            <!-- Logo Section -->
            <img src="images/logo1.jpg" alt="Tech Store Logo" class="logo">
            <h1>Tech Store</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="#" class="cart-link" onclick="showCart()">
                    Cart <span class="cart-counter" id="cart-counter">0</span>
                </a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
        
    </header>

    <main id="product-list">
        <div class="product">
            <img src="images/earbuds.jpg" alt="JBL Earbuds">
            <h2>JBL Earbuds</h2>
            <p>$31.03</p>
            <button class="add-to-cart" onclick="addToCart('JBL Earbuds', 31.03)">Add to Cart</button>
        </div>

        <div class="product">
            <img src="images/extension.jpg" alt="Extension">
            <h2>Extension</h2>
            <p>$12.41</p>
            <button class="add-to-cart" onclick="addToCart('Extension', 12.41)">Add to Cart</button>
        </div>

        <div class="product">
            <img src="images/flash.webp" alt="Flash Disk">
            <h2>Flash Disk</h2>
            <p>$17.24</p>
            <button class="add-to-cart" onclick="addToCart('Flash Disk', 17.24)">Add to Cart</button>
        </div>

        <div class="product">
            <img src="images/iron.jpg" alt="Electric Iron">
            <h2>Electric Iron</h2>
            <p>$58.62</p>
            <button class="add-to-cart" onclick="addToCart('Electric Iron', 58.62)">Add to Cart</button>
        </div>

        <div class="product">
            <img src="images/laptop.webp" alt="HP Laptop">
            <h2>HP Laptop</h2>
            <p>$413.79</p>
            <button class="add-to-cart" onclick="addToCart('HP Laptop', 413.79)">Add to Cart</button>
        </div>

        <div class="product">
            <img src="images/lg.jpg" alt="LG Smart TV">
            <h2>LG Smart TV</h2>
            <p>$124.14</p>
            <button class="add-to-cart" onclick="addToCart('LG Smart TV', 124.14)">Add to Cart</button>
        </div>

        <div class="product">
            <img src="images/phone.webp" alt="iPhone 15 Pro">
            <h2>iPhone 15 Pro</h2>
            <p>$1241.38</p>
            <button class="add-to-cart" onclick="addToCart('iPhone 15 Pro', 1241.38)">Add to Cart</button>
        </div>

        <div class="product">
            <img src="images/router.jpg" alt="Tenda Router">
            <h2>Tenda Router</h2>
            <p>$13.79</p>
            <button class="add-to-cart" onclick="addToCart('Tenda Router', 13.79)">Add to Cart</button>
        </div>

        <div class="product">
            <img src="images/Sayona.webp" alt="SAYONA Woofer">
            <h2>SAYONA Woofer</h2>
            <p>$103.45</p>
            <button class="add-to-cart" onclick="addToCart('SAYONA Woofer', 103.45)">Add to Cart</button>
        </div>
    </main>

    <!-- Background image container with slideshow effect -->
    <div id="background-slideshow" class="background-slideshow"></div>

    <!-- Cart Modal -->
    <div id="cart-modal" class="cart-modal">
        <div class="cart-content">
            <span class="close-btn" onclick="closeCart()">&times;</span>
            <h2>Your Cart</h2>
            <ul id="cart-list"></ul>
            <p>Total: $<span id="cart-total">0.00</span></p>
        </div>
    </div>

    <footer>
        <p>&copy; <b>Thank you for shopping with us!!!<b></p>
    </footer>
    <script>
    let cartCount = 0;
        let cartItems = [];

        function addToCart(productName, productPrice) {
            // Increment cart count and update counter display
            cartCount++;
            document.getElementById("cart-counter").textContent = cartCount;

            // Check if item already exists in the cart
            const itemIndex = cartItems.findIndex(item => item.name === productName);

            if (itemIndex > -1) {
                // If item exists, increase the quantity
                cartItems[itemIndex].quantity++;
            } else {
                // If item does not exist, add new item with quantity 1
                cartItems.push({ name: productName, price: productPrice, quantity: 1 });
            }

            // Prepare data to send to PHP
            const quantity = 1; // Assuming adding 1 item at a time
            const data = new FormData();
            data.append('product_name', productName);
            data.append('product_price', productPrice);
            data.append('quantity', quantity);

            // Send data to add_to_cart.php using Fetch API
            fetch('add_to_cart.php', {
                method: 'POST',
                body: data
            })
            .then(response => response.text())
            .then(result => {
                console.log(result); // Handle the response if needed
                updateCart(); // Update cart display after adding item
            })
            .catch(error => console.error('Error:', error));
        }

        function updateCart() {
            // Update cart display
            const cartList = document.getElementById("cart-list");
            const cartTotal = document.getElementById("cart-total");

            // Clear current cart list
            cartList.innerHTML = "";

            // Calculate total price
            let total = 0;
            cartItems.forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;

                // Add item to cart list
                const listItem = document.createElement("li");
                listItem.textContent = `${item.name} - $${item.price} x ${item.quantity} = $${itemTotal.toFixed(2)}`;
                cartList.appendChild(listItem);
            });

            // Update total price display
            cartTotal.textContent = total.toFixed(2);
        }

        function showCart() {
            // Show cart modal
            document.getElementById("cart-modal").style.display = "block";
            updateCart();
        }

        function closeCart() {
            // Hide cart modal
            document.getElementById("cart-modal").style.display = "none";
        }
       
fetch('add_to_cart.php', {
    method: 'POST',
    body: data
})
.then(response => response.text())
.then(result => {
    console.log(result); // Check the server response here
    updateCart(); // Update the cart display
})
.catch(error => console.error('Error:', error));


        // Open login modal
function openLoginModal() {
    document.getElementById("login-modal").style.display = "block";
}

// Close login modal
function closeLoginModal() {
    document.getElementById("login-modal").style.display = "none";
}

// Close the modal if the user clicks outside the modal content
window.onclick = function(event) {
    const modal = document.getElementById("login-modal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
}
// Array of background image URLs
const backgroundImages = [
    "images/earbuds.jpg",
    "images/laptop.webp",
    "images/iron.jpg",
    "images/router.jpg"
];

let currentImageIndex = 0;
const slideshowElement = document.getElementById("background-slideshow");

// Function to change the background image
function changeBackgroundImage() {
    // Set the background image to the current image in the array
    slideshowElement.style.backgroundImage = `url(${backgroundImages[currentImageIndex]})`;
    
    // Update index to show the next image; loop back if at end of array
    currentImageIndex = (currentImageIndex + 1) % backgroundImages.length;
}

// Start the slideshow with an interval of 5 seconds (5000ms)
setInterval(changeBackgroundImage, 5000);

// Initialize the background image immediately
changeBackgroundImage();

</script>
    
</body>
</html>
