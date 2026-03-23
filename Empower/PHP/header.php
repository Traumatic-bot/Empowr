<?php
$userInfo = function_exists('getUserInfo') ? getUserInfo() : ['first_name' => ''];

$isLoggedIn = function_exists('isLoggedIn') && isLoggedIn();

if ($isLoggedIn && isset($_SESSION['user_id'])) {
    $cartCount = function_exists('getCartCount') ? getCartCount($_SESSION['user_id']) : 0;
    $cartTotal = function_exists('calculateCartTotal') ? calculateCartTotal($_SESSION['user_id']) : '0.00';
} else {
    $cartCount = 0;
    $cartTotal = '0.00';
}

$bodyClasses = [];
if (isLoggedIn() && isDarkModeEnabled($_SESSION['user_id'])) {
    $bodyClasses[] = 'dark-mode';
}
if (isset($_SESSION['text_size']) && $_SESSION['text_size'] === 'large') {
    $bodyClasses[] = 'large-text';
}
$bodyClassString = !empty($bodyClasses) ? 'class="' . implode(' ', $bodyClasses) . '"' : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Empowr">
    <meta name="keywords" content="Empowr, ecommerce, tech, accessories">
<<<<<<< HEAD
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
=======
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | Empowr' : 'Empowr'; ?></title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="icon" type="image/x-icon" href="Images/favicon.ico">
    <?php if (isLoggedIn() && isDarkModeEnabled($_SESSION['user_id'])): ?>
    <link rel="stylesheet" href="CSS/darkmode.css" id="darkmode-stylesheet">
    <?php endif; ?>

    <style>
    body.dark-mode {
        transition: background-color 0.3s ease, color 0.3s ease;
    }
<<<<<<< HEAD
    
    /* Mobile Navigation Styles */
    .nav-toggle {
        display: none;
        background: #333;
        color: white;
        padding: 12px 20px;
        width: 100%;
        text-align: left;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        font-weight: bold;
        margin: 0;
    }
    
    .nav-toggle:hover {
        background: #444;
    }
    
    @media (max-width: 768px) {
        .nav-toggle {
            display: block;
        }
        
        ul.topnav {
            display: none;
            flex-direction: column;
            width: 100%;
        }
        
        ul.topnav.show {
            display: flex;
        }
        
        ul.topnav li {
            width: 100%;
        }
        
        ul.topnav li a {
            padding: 12px 20px;
            text-align: left;
        }
    }
    
    /* Mobile basket popup adjustments */
    @media (max-width: 768px) {
        #popup {
            width: 90%;
            right: 5%;
            left: 5%;
            top: 20%;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .productList {
            max-height: 200px;
        }
        
        .subTotal ul li {
            font-size: 14px;
        }
        
        .Total a {
            display: block;
            text-align: center;
            background: #ffee32;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            color: #111;
            font-weight: bold;
        }
    }
    
    /* Mobile header adjustments */
    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            align-items: stretch;
            padding: 10px;
        }
        
        .logo-link {
            text-align: center;
        }
        
        .logo-image {
            margin-left: 0 !important;
            scale: 0.7 !important;
        }
        
        .search {
            width: 100%;
            margin: 10px 0;
        }
        
        .search form {
            width: 100%;
        }
        
        .search input[type="text"] {
            width: calc(100% - 50px);
        }
        
        .header-links {
            width: 100%;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .account-links {
            align-items: center;
        }
        
        .account-links ul {
            font-size: 0.7rem;
        }
        
        .basket-links a {
            padding: 5px 8px;
        }
        
        .darkmode-toggle {
            margin-left: 0 !important;
        }
    }
    
    /* Small mobile adjustments */
    @media (max-width: 480px) {
        .header-links {
            flex-wrap: wrap;
            gap: 5px;
            justify-content: center;
        }
        
        .account-links ul {
            font-size: 0.65rem;
        }
        
        .basket-links a {
            padding: 4px 6px;
        }
        
        .darkmode-toggle a {
            padding: 4px 8px;
        }
        
        .darkmode-toggle img {
            width: 14px;
            height: 14px;
        }
        
        .items {
            width: 20px;
            height: 20px;
            font-size: 12px;
        }
        
        .price {
            font-size: 12px;
        }
    }
    
    /* Touch-friendly improvements */
    button, 
    a, 
    input, 
    select,
    .basket-links a,
    .darkmode-toggle a,
    .nav-toggle,
    .Total a {
        min-height: 44px;
        min-width: 44px;
    }
    
    @media (max-width: 768px) {
        button, 
        a, 
        input, 
        select,
        .basket-links a,
        .darkmode-toggle a {
            min-height: 40px;
        }
    }
    </style>
    
=======
    </style>
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
    <style>
    <?php if (isLoggedIn() && isset($_SESSION['font_scale'])): ?>body {
        font-size: <?php echo $_SESSION['font_scale'] * 100;
        ?>% !important;
    }
<<<<<<< HEAD
    <?php endif; ?>
=======

    <?php endif;
    ?>
    </style>
    <style>
    <?php if (isLoggedIn() && isset($_SESSION['font_scale'])): ?>body {
        font-size: <?php echo $_SESSION['font_scale'] * 100;
        ?>% !important;
    }

    <?php endif;
    ?>
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
    </style>
</head>

<body <?php echo $bodyClassString; ?> style="margin:0;padding:0;">

    <header>
        <div class="header">
            <a href="index.php" class="logo-link" style="text-decoration: none; display: inline-block;">
<<<<<<< HEAD
                <img src="http://localhost/Empowr/Images/Empowr_Logo_C.svg" alt="Empowr Logo" class="logo-image"
                    style="padding-top: 20px; padding-bottom: 20px; scale: 0.8; margin-left: -20px;">
            </a>

            <div class="search">
                <form method="get" action="products.php" style="display:flex;">
                    <input type="text" placeholder="Search products..." name="q" style="width:480px">
                    <button type="submit" aria-label="Search">
                        <img src="http://localhost/Empowr/Images/search_logo.svg" alt="Search" style="width:30px; height:30px;">
=======
                <img src="Images/Empowr_Logo_C.svg" alt="Empowr Logo" class="logo-image"
                    style="padding-top: 20px; padding-bottom: 20px; scale: 0.8; margin-left: -20px;">
            </a>

            </form>
            <div class="search">
                <form method="get" action="products.php" style="display:flex;">
                    <input type="text" placeholder="Search.." name="q" style="width:480px">
                    <button type="submit">
                        <img src="Images/search_logo.svg" alt="Search" style="width:30px; height:30px;">
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                    </button>
                </form>
            </div>

            <div class="header-links">
                <div class="account-links" style="width: 200px;">
                    <?php if (isLoggedIn()): ?>
                    <div href="#" style="padding-right:25px;">
                        <span data-action="myAccountMenu" role="button" aria-label="open my account menu"
                            aria-pressed="false" style="display: flex;">
                            <i></i> Welcome, <?php echo htmlspecialchars($userInfo['first_name']); ?>
                        </span>
                    </div>
                    <div style="justify-content: center; font-size: x-small">
                        <ul style="list-style-type:none; display: flex; padding:0; margin:0">
                            <li>
                                <a href="<?php echo isStaff() ? 'admin_dashboard.php' : 'dashboard.php'; ?>"
                                    rel="nofollow" style="border-right: 1px solid #5d5c5c; padding-right: 5px;">
                                    My Account
                                </a>
                            </li>
                            <li>
                                <a href="logout.php" rel="nofollow" style="padding-left: 5px;">
                                    Log Out
                                </a>
                            </li>
                        </ul>
                    </div>
                    <?php else: ?>
                    <div href="#" style="padding-right:25px;">
                        <span data-action="myAccountMenu" role="button" aria-label="open my account menu"
                            aria-pressed="false" style="display: flex;">
                            <i></i> My Account
                        </span>
                    </div>
                    <div style="justify-content: center; font-size: x-small">
                        <ul style="list-style-type:none; display: flex; padding:0; margin:0">
                            <li>
                                <a href="login.php" rel="nofollow"
                                    style="border-right: 1px solid #5d5c5c; padding-right: 5px;">
                                    Log In
                                </a>
                            </li>
                            <li>
                                <a href="signup.php" rel="nofollow" style="padding-left: 5px;">
                                    Create Account
                                </a>
                            </li>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="basket-links">
                    <a id="basket-links" href="#"
<<<<<<< HEAD
                        style="margin-left:20px; padding-left: 5px; display: flex; justify-content: center; align-items: center;"
                        aria-label="View basket">
                        <span class="items"><strong><?php echo $cartCount; ?></strong></span>
                        <img src="http://localhost/Empowr/Images/Basket_Logo.svg" alt="Basket Logo"
                            style="width: 35px; margin-left: 3px; margin-right: 3px;">
                        <span class="price"
                            style="margin-left: 3px; margin-right: 3px;">£<?php echo $cartTotal; ?></span>
                        <img src="http://localhost/Empowr/Images/Dropdown_Arrow.svg" alt="Dropdown Arrow" class="btn"
=======
                        style="margin-left:20px; padding-left: 5px; display: flex; justify-content: center; align-items: center;">
                        <span class="items"><strong><?php echo $cartCount; ?></strong></span>
                        <img src="Images/Basket_Logo.svg" alt="Basket Logo"
                            style="width: 35px; margin-left: 3px; margin-right: 3px;">
                        <span class="price"
                            style="margin-left: 3px; margin-right: 3px;">£<?php echo $cartTotal; ?></span>
                        <img src="Images/Dropdown_Arrow.svg" alt="Dropdown Arrow" class="btn"
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                            style="width:20px; margin-left: 3px; margin-right: 3px;">
                    </a>
                </div>
                <div class="darkmode-toggle" style="margin-left: 20px;">
                    <a href="toggle_darkmode.php" id="darkmode-toggle-btn"
                        title="<?php echo isDarkModeEnabled($_SESSION['user_id'] ?? 0) ? 'Switch to Light Mode' : 'Switch to Dark Mode'; ?>"
                        style="display: flex; align-items: center; text-decoration: none; color: inherit; padding: 5px 10px; border-radius: 20px; background: #f0f0f0;">
                        <span id="darkmode-icon" style="margin-right: 5px;">
                            <?php if (isDarkModeEnabled($_SESSION['user_id'] ?? 0)): ?>
<<<<<<< HEAD
                            <img src="http://localhost/Empowr/Images/sun.png" alt="Sun" style="width: 16px; height: 16px;">
                            <?php else: ?>
                            <img src="http://localhost/Empowr/Images/moon.png" alt="moon" style="width: 16px; height: 16px;">
=======
                            <img src="Images/sun.png" alt="Sun" style="width: 16px; height: 16px;">
                            <?php else: ?>
                            <img src="Images/moon.png" alt="moon" style="width: 16px; height: 16px;">
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                            <?php endif; ?>
                        </span>
                        <span id="darkmode-text" style="font-size: 12px;">
                            <?php echo isDarkModeEnabled($_SESSION['user_id'] ?? 0) ? 'Light' : 'Dark'; ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <!-- Mobile Navigation Toggle Button -->
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation menu" aria-expanded="false">☰ Menu</button>

        <!-- Navigation Menu -->
        <ul class="topnav" id="topnav">
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="deals.php">Easter Sale!</a></li>
            <li><a href="about_us.php">About Us</a></li>
            <li><a href="contact_us.php">Contact Us</a></li>
        </ul>

        <!-- Basket overlay -->
=======

>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
        <div id="popupBackground" style="cursor: pointer; height: 100%; left: 0; opacity: 0.7; position: fixed; top: 0;
                width: 100%; z-index: 10; background: #333; display:none;">
        </div>

        <div id="popup" style="display:none;">
            <div class="header"
                style="background: #ebecec; display: flex; height: 80px; align-items:center; padding:0 15px;">
<<<<<<< HEAD
                <div style="flex:1; font-weight: bold;">Your Basket</div>
                <span class="btn" id="closePopup" style="cursor: pointer;">
=======
                <div style="flex:1;">Basket</div>
                <span class="btn" id="closePopup">
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                    <i style="display: block;
                          border-left: 3px solid #000000;
                          border-top: 3px solid #000000;
                          height: 13px;
                          width: 13px;
                          transform: rotate(45deg);
                          background: #ebecec;">
                    </i>
                </span>
            </div>

            <div class="productList" style="min-height: 132px; max-height: 300px; overflow-y: auto;
                    padding: 0 10px 0 16px; color: #707070; margin-top: 8px; margin-right: 14px;">
                <ul style="margin:0; padding:0; list-style:none;" id="cartItemsList">
                    <?php if (isLoggedIn() && $cartCount > 0): ?>
                    <?php
                    $user_id = $_SESSION['user_id'];
                    $cartQuery = "SELECT c.*, p.product_name, p.price 
                                 FROM cart c 
                                 JOIN products p ON c.product_id = p.product_id 
                                 WHERE c.user_id = $user_id";
                    $cartResult = mysqli_query($conn, $cartQuery);
                    
                    while ($cartItem = mysqli_fetch_assoc($cartResult)):
                        $itemTotal = $cartItem['price'] * $cartItem['quantity'];
                    ?>
                    <li>
                        <div
                            style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                            <div>
                                <strong><?php echo htmlspecialchars($cartItem['product_name']); ?></strong><br>
                                <small>Qty: <?php echo $cartItem['quantity']; ?> ×
                                    £<?php echo number_format($cartItem['price'], 2); ?></small>
                            </div>
                            <div>£<?php echo number_format($itemTotal, 2); ?></div>
                        </div>
                    </li>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <li class="basketEmpty" style="text-align: center;">
                        <i style="font-weight: normal; padding-top: 60px; display: block; color: #cccccc;">
<<<<<<< HEAD
                            Your basket is empty
=======
                            You have no products in your basket
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                        </i>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="subTotal" style="margin: 18px 15px 0; border-top: 4px solid #e2e2e2;">
                <ul style="padding: 0; margin: 5px; list-style:none;">
                    <li
                        style="display: table; width: 100%; border-bottom: 1px solid #e2e2e2; height: 50px; text-align: left;">
                        <span class="title" style="width: 80%;  display: table-cell;">
                            <strong style="display: block;">Sub Total</strong>Inc UK VAT @ 20%
                        </span>
                        <span class="price"
                            style="width: 30%; text-align: right; vertical-align: top; padding-top: 15px; display: table-cell;">
                            <small>£</small><?php echo $cartTotal; ?>
                        </span>
                    </li>
                    <li class="delivery"
                        style="display: table; width: 100%; border-bottom: 1px solid #e2e2e2; height: 50px; text-align: left;">
                        <span class="title" style="width: 80%; display: table-cell;">
                            <strong>Delivery to UK Mainland</strong>
                        </span>
                        <span class="price"
                            style="width: 30%; text-align: right; vertical-align: top; padding-top: 15px; display: table-cell;">
                            <small>£</small>4.<small>99</small>
                        </span>
                    </li>
                    <li class="total"
                        style="display: table; width: 100%; border-bottom: 1px solid #e2e2e2; height: 50px; text-align: left;">
                        <span class="title" style="width: 80%; display: table-cell;">
                            <strong style="display: block;">Total</strong>Inc UK VAT @ 20%
                        </span>
                        <span class="price"
                            style="width: 30%; text-align: right; vertical-align: top; padding-top: 15px; display: table-cell;">
                            <small>£</small><?php echo number_format(floatval($cartTotal) + 4.99, 2); ?>
                        </span>
                    </li>
                </ul>
            </div>

            <div class="Total" style="height: 80px; overflow: hidden; margin: 0 15px;">
<<<<<<< HEAD
                <a href="checkout.php" style="display: block; text-align: center; background: #ffee32; padding: 14px; border-radius: 8px; text-decoration: none; color: #111; font-weight: bold;">CHECKOUT</a>
=======
                <a class="" href="checkout.php">CHECKOUT</a>
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
            </div>
        </div>

        <script>
<<<<<<< HEAD
        // Basket popup functionality
=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
        (function() {
            const bg = document.getElementById("popupBackground");
            const pop = document.getElementById("popup");
            const basketLink = document.getElementById("basket-links");
            const closePopup = document.getElementById("closePopup");

            function toggleBasket(event) {
                if (event) event.preventDefault();
                const visible = bg.style.display === "block";
                bg.style.display = visible ? "none" : "block";
                pop.style.display = visible ? "none" : "block";
            }

            if (basketLink) basketLink.addEventListener("click", toggleBasket);
            if (bg) bg.addEventListener("click", toggleBasket);
            if (closePopup) closePopup.addEventListener("click", toggleBasket);
        })();
        </script>
<<<<<<< HEAD

        <script>
        // Mobile navigation toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const navToggle = document.getElementById('navToggle');
            const topnav = document.getElementById('topnav');
            
            if (navToggle && topnav) {
                navToggle.addEventListener('click', function() {
                    const expanded = topnav.classList.toggle('show');
                    navToggle.setAttribute('aria-expanded', expanded);
                    navToggle.innerHTML = expanded ? '✕ Close' : '☰ Menu';
                });
                
                // Close menu when clicking a link on mobile
                const navLinks = topnav.querySelectorAll('a');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth <= 768) {
                            topnav.classList.remove('show');
                            navToggle.setAttribute('aria-expanded', 'false');
                            navToggle.innerHTML = '☰ Menu';
                        }
                    });
                });
            }
            
            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth > 768) {
                        if (topnav) topnav.classList.remove('show');
                        if (navToggle) {
                            navToggle.setAttribute('aria-expanded', 'false');
                            navToggle.innerHTML = '☰ Menu';
                        }
                    }
                }, 250);
            });
        });
        </script>

        <script>
        // Dark mode toggle functionality
=======
        <br>
        <br>
        <ul class="topnav" id="topnav">
            <li><a href="/index.php">Home</a></li>
            <li><a href="/products.php">Products</a></li>
            <li><a href="deals.php">Easter Sale!</a></li>
            <li><a href="/about_us.php">About Us</a></li>
            <li><a href="/contact_us.php">Contact Us</a></li>
        </ul>

        <script>
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isLoggedIn() && isDarkModeEnabled($_SESSION['user_id'])): ?>
            document.body.classList.add('dark-mode');

            const toggleBtn = document.getElementById('darkmode-toggle-btn');
            const toggleIcon = document.getElementById('darkmode-icon');
            const toggleText = document.getElementById('darkmode-text');

            if (toggleBtn) toggleBtn.title = 'Switch to Light Mode';
<<<<<<< HEAD
            if (toggleIcon) toggleIcon.innerHTML = '<img src="Images/sun.png" alt="Sun" style="width: 16px; height: 16px;">';
=======
            toggleIcon.innerHTML = '<img src="/Images/sun.png" alt="Sun" style="width: 16px; height: 16px;">';
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
            if (toggleText) toggleText.textContent = 'Light';
            <?php endif; ?>
        });
        </script>
<<<<<<< HEAD
=======
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.querySelector('.nav-toggle');
            const nav = document.getElementById('topnav');
            if (toggle && nav) {
                toggle.addEventListener('click', function() {
                    nav.classList.toggle('show');
                });
            }
        });
        </script>
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
    </header>