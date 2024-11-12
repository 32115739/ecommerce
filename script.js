// Load products from fetch_products.php
document.addEventListener("DOMContentLoaded", function () {
    fetch("fetch_products.php")
        .then(response => response.json())
        .then(products => {
            const productContainer = document.getElementById("product-list");
            products.forEach(product => {
                const productCard = document.createElement("div");
                productCard.classList.add("product");

                productCard.innerHTML = `
                    <img src="${product.image}" alt="${product.name}">
                    <h2>${product.name}</h2>
                    <p>${product.description}</p>
                    <p>Price: $${product.price}</p>
                    <button onclick="addToCart(${product.id})">Add to Cart</button>
                `;

                productContainer.appendChild(productCard);
            });
        })
        .catch(error => console.error("Error fetching products:", error));
});

// Add to cart function
function addToCart(productId) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    cart.push(productId);
    localStorage.setItem("cart", JSON.stringify(cart));
    alert("Product added to cart!");
}

// Display cart items in cart.html
function displayCart() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const cartList = document.getElementById("cart-list");

    cartList.innerHTML = "<h2>Your Cart</h2>";

    cart.forEach(productId => {
        fetch(`fetch_product.php?id=${productId}`)
            .then(response => response.json())
            .then(product => {
                const productCard = document.createElement("div");
                productCard.classList.add("product");

                productCard.innerHTML = `
                    <h2>${product.name}</h2>
                    <p>Price: $${product.price}</p>
                `;

                cartList.appendChild(productCard);
            });
    });
}

// Call displayCart if on cart page
if (document.getElementById("cart-list")) {
    displayCart();
}



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

function toggleLoginForm() {
    const loginForm = document.getElementById('login-form');
    loginForm.style.display = loginForm.style.display === 'none' ? 'block' : 'none';
}


    