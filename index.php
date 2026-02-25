<?php
session_start();

// Redirect if not logged in
if(!isset($_SESSION['user_id'])){
    header("Location: auth.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Poco Online Shopping Center</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, Helvetica, sans-serif;
            transition:0.3s;
        }
        body{
            background:#f2f2f2;
        }
        .dark-mode{
            background:#121212;
            color:white;
        }
        .dark-mode .product,
        .dark-mode .dropdown,
        .dark-mode .nav,
        .dark-mode .top-bar,
        .dark-mode .hero{
            background:#1e1e1e;
            color:white;
        }

        .top-bar{
            background:#f68b1e;
            padding:15px 40px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            color:white;
            flex-wrap:wrap;
        }

        .logo{
            font-size:22px;
            font-weight:bold;
        }

        .search-box{
            display:flex;
            max-width:400px;
            width:100%;
        }

        .search-box input{
            width:100%;
            padding:10px;
            border:none;
        }

        .search-box button{
            background:black;
            color:white;
            border:none;
            padding:10px 20px;
            cursor:pointer;
        }

        .header-links{
            display:flex;
            gap:20px;
            align-items:center;
        }

        .header-links a{
            text-decoration:none;
            color:white;
            font-weight:bold;
        }

        .cart-count{
            background:black;
            padding:3px 8px;
            border-radius:50%;
            font-size:12px;
        }

        .nav{
            background:white;
            position:sticky;
            top:0;
            box-shadow:0 2px 10px rgba(0,0,0,0.05);
        }

        .nav-container{
            max-width:1200px;
            margin:auto;
            display:flex;
            justify-content:center;
            gap:35px;
            padding:15px 20px;
        }

        .nav a{
            text-decoration:none;
            color:#333;
            font-weight:bold;
        }

        .hero{
            margin:20px 40px;
            background:linear-gradient(to right,#f68b1e,#ffb347);
            height:250px;
            display:flex;
            justify-content:center;
            align-items:center;
            font-size:32px;
            font-weight:bold;
            color:white;
            border-radius:10px;
            text-align:center;
        }

        .products{
            margin:20px 40px;
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
            gap:20px;
        }

        .product{
            background:white;
            padding:15px;
            border-radius:10px;
            box-shadow:0 5px 15px rgba(0,0,0,0.1);
            cursor:pointer;
        }

        .product img{
            width:100%;
            height:150px;
            object-fit:cover;
        }

        .price{
            color:#f68b1e;
            font-weight:bold;
            margin:10px 0;
        }

        .btn{
            background:#f68b1e;
            color:white;
            border:none;
            padding:10px;
            width:100%;
            cursor:pointer;
            border-radius:5px;
        }

        footer{
            background:#333;
            color:white;
            text-align:center;
            padding:20px;
            margin-top:40px;
        }

        @media(max-width:768px){
            .top-bar{
                flex-direction:column;
                gap:15px;
            }
        }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="top-bar">

    <div class="logo">Poco</div>

    <div class="search-box">
        <input type="text" placeholder="Search products">
        <button>Search</button>
    </div>

    <div class="header-links">
        <span>Welcome, <?php echo $_SESSION['user_name']; ?> 👋</span>
        <a href="logout.php">Logout</a>
        <a href="cart.php">Cart 🛒 <span class="cart-count" id="cartCount">0</span></a>
        <a href="orders.php">Orders 🧾</a>
        <button onclick="toggleDarkMode()">🌙</button>
    </div>

</div>

<!-- HERO -->
<div class="hero">
    MEGA SALE UP TO 70% OFF 🔥
</div>

<!-- PRODUCTS -->
<div class="products">
    <div class="product">
        <img src="smartphone.jpeg">
        <h4>Smartphone</h4>
        <p class="price">₦120,000</p>
        <button class="btn" onclick="addToCart('Smartphone',120000)">Add to Cart</button>
    </div>

    <div class="product">
        <img src="lap.png">
        <h4>Laptop</h4>
        <p class="price">₦350,000</p>
        <button class="btn" onclick="addToCart('Laptop',350000)">Add to Cart</button>
    </div>

    <div class="product">
        <img src="SNEAKER.jpg">
        <h4>Sneakers</h4>
        <p class="price">₦25,000</p>
        <button class="btn" onclick="addToCart('Sneakers',25000)">Add to Cart</button>
    </div>
</div>

<footer>
    © 2026 Poco Online Shopping Center | Developed by Yusuf Auwal
</footer>

<script>
let cart = JSON.parse(localStorage.getItem("cart")) || [];
updateCartCount();

function addToCart(productName, price){
    let item = cart.find(p => p.name === productName);
    if(item){
        item.quantity++;
    } else {
        cart.push({name:productName, price:price, quantity:1});
    }
    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartCount();
    alert(productName + " added to cart!");
}

function updateCartCount(){
    let total = 0;
    cart.forEach(item => total += item.quantity);
    document.getElementById("cartCount").innerText = total;
}

function toggleDarkMode(){
    document.body.classList.toggle("dark-mode");
}
</script>

</body>
</html>