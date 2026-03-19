<?php
require_once 'config.php';

// User info
$userInfo = function_exists('getUserInfo') ? getUserInfo() : ['first_name' => ''];
$isLoggedIn = function_exists('isLoggedIn') && isLoggedIn();
$user_id = $_SESSION['user_id'] ?? 0;

$cartCount = $isLoggedIn ? getCartCount($user_id) : 0;
$cartTotal = $isLoggedIn ? calculateCartTotal($user_id) : '0.00';
$darkMode = $isLoggedIn ? isDarkModeEnabled($user_id) : false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Empowr">
    <meta name="keywords" content="Empowr, ecommerce, tech, accessories">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | Empowr' : 'Empowr'; ?></title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="icon" type="image/x-icon" href="Images/favicon.ico">
    <?php if ($darkMode): ?>
        <link rel="stylesheet" href="CSS/darkmode.css" id="darkmode-stylesheet">
    <?php endif; ?>
    <style>
        body.dark-mode {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>
</head>

<body class="<?php echo $darkMode ? 'dark-mode' : ''; ?>" style="margin:0; padding:0;">
<header>
    <div class="header">

        <!-- LOGO -->
        <a href="index.php" class="logo-link">
            <img src="Images/Empowr_Logo_C.svg" alt="Empowr Logo" class="logo-image">
        </a>

        <!-- SEARCH -->
        <div class="search">
            <form method="get" action="products.php" style="display:flex;">
                <input type="text" placeholder="Search.." name="q">
                <button type="submit">
                    <img src="Images/search_logo.svg" alt="Search">
                </button>
            </form>
        </div>

        <!-- ACCOUNT LINKS -->
        <div class="header-links">
            <div class="account-links">
                <?php if ($isLoggedIn): ?>
                    <div>
                        Welcome, <?php echo htmlspecialchars($userInfo['first_name']); ?>
                    </div>
                    <ul class="account-menu">
                        <li><a href="dashboard.php">My Account</a></li>
                        <li><a href="logout.php">Log Out</a></li>
                    </ul>
                <?php else: ?>
                    <div>My Account</div>
                    <ul class="account-menu">
                        <li><a href="login.php">Log In</a></li>
                        <li><a href="signup.php">Create Account</a></li>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- CART -->
            <div class="basket-links">
                <a href="#" id="basket-links">
                    <span class="items"><strong><?php echo $cartCount; ?></strong></span>
                    <img src="Images/Basket_Logo.svg" alt="Basket Logo">
                    <span class="price">£<?php echo $cartTotal; ?></span>
                    <img src="Images/Dropdown_Arrow.svg" alt="Dropdown Arrow">
                </a>
            </div>

            <!-- DARK MODE TOGGLE -->
            <?php if ($isLoggedIn): ?>
                <div class="darkmode-toggle">
                    <a href="toggle_darkmode.php" title="<?php echo $darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'; ?>">
                        <span id="darkmode-icon">
                            <img src="Images/<?php echo $darkMode ? 'sun' : 'moon'; ?>.png" alt="<?php echo $darkMode ? 'Sun' : 'Moon'; ?>">
                        </span>
                        <span id="darkmode-text"><?php echo $darkMode ? 'Light' : 'Dark'; ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- BASKET POPUP -->
        <div id="popupBackground" style="display:none;"></div>
        <div id="popup" style="display:none;">
            <div class="header">
                <div>Basket</div>
                <span id="closePopup">✕</span>
            </div>
            <div class="productList">
                <ul id="cartItemsList">
                    <?php if ($isLoggedIn && $cartCount > 0):
                        $cartQuery = "SELECT c.*, p.product_name, p.price, p.image_url
                                      FROM cart c 
                                      JOIN products p ON c.product_id = p.product_id 
                                      WHERE c.user_id = $user_id";
                        $cartResult = mysqli_query($conn, $cartQuery);
                        while ($cartItem = mysqli_fetch_assoc($cartResult)):
                            $itemTotal = $cartItem['price'] * $cartItem['quantity'];
                            ?>
                            <li>
                                <div class="cart-item">
                                    <div class="cart-item-image">
                                        <?php if (!empty($cartItem['image_url'])): ?>
                                            <img src="<?php echo htmlspecialchars($cartItem['image_url']); ?>" alt="<?php echo htmlspecialchars($cartItem['product_name']); ?>">
                                        <?php endif; ?>
                                    </div>
                                    <div class="cart-item-info">
                                        <strong><?php echo htmlspecialchars($cartItem['product_name']); ?></strong><br>
                                        <small>Qty: <?php echo $cartItem['quantity']; ?> × £<?php echo number_format($cartItem['price'], 2); ?></small>
                                    </div>
                                    <div class="cart-item-total">£<?php echo number_format($itemTotal, 2); ?></div>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="basketEmpty">You have no products in your basket</li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="subTotal">
                <ul>
                    <li>Sub Total: £<?php echo $cartTotal; ?></li>
                    <li>Delivery to UK Mainland: £4.99</li>
                    <li>Total: £<?php echo number_format(floatval($cartTotal) + 4.99, 2); ?></li>
                </ul>
                <a href="checkout.php">CHECKOUT</a>
            </div>
        </div>

        <!-- NAV -->
        <ul class="topnav">
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="products.php">Easter Sale!</a></li>
            <li><a href="about_us.php">About us</a></li>
            <li><a href="contact_us.php">Contact us</a></li>
        </ul>
    </div>

    <script>
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
</header>