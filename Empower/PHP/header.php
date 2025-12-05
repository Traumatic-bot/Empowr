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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Empowr">
    <meta name="keywords" content="Empowr, ecommerce, tech, accessories">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>

<body <?php echo (isLoggedIn() && isDarkModeEnabled($_SESSION['user_id'])) ? 'class="dark-mode"' : ''; ?>
    style="margin: 0; padding: 0;">

    <header>
        <div class="header">
            <a href="index.php" class="logo-link" style="text-decoration: none; display: inline-block;">
                <img src="Images/Empowr_Logo_C.svg" alt="Empowr Logo" class="logo-image"
                    style="padding-top: 20px; padding-bottom: 20px; scale: 0.8; margin-left: -20px;">
            </a>

            </form>
            <div class="search" style="width:500px; padding-left: 50px; margin-right: 150px;">
                <form method="get" action="products.php" style="display:flex;">
                    <input type="text" placeholder="Search.." name="q" style="width:480px">
                    <button type="submit">
                        <img src="Images/search_logo.svg" alt="Search" style="width:30px; height:30px;">
                    </button>
                </form>
            </div>

            <div class="header-links" style="width: 400px; display: flex; align-items: center; height: 50px;">
                <div class="account-links" style="width: 180px;">
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
                                <a href="dashboard.php" rel="nofollow"
                                    style="border-right: 1px solid #5d5c5c; padding-right: 5px;">
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
                        style="margin-left:20px; padding-left: 5px; display: flex; justify-content: center; align-items: center;">
                        <span class="items"><strong><?php echo $cartCount; ?></strong></span>
                        <img src="Images/Basket_Logo.svg" alt="Basket Logo"
                            style="width: 35px; margin-left: 3px; margin-right: 3px;">
                        <span class="price"
                            style="margin-left: 3px; margin-right: 3px;">£<?php echo $cartTotal; ?></span>
                        <img src="Images/Dropdown_Arrow.svg" alt="Dropdown Arrow" class="btn"
                            style="width:20px; margin-left: 3px; margin-right: 3px;">
                    </a>
                </div>
                <div class="darkmode-toggle" style="margin-left: 20px;">
                    <a href="toggle_darkmode.php" id="darkmode-toggle-btn"
                        title="<?php echo isDarkModeEnabled($_SESSION['user_id'] ?? 0) ? 'Switch to Light Mode' : 'Switch to Dark Mode'; ?>"
                        style="display: flex; align-items: center; text-decoration: none; color: inherit; padding: 5px 10px; border-radius: 20px; background: #f0f0f0;">
                        <span id="darkmode-icon" style="margin-right: 5px;">
                            <?php if (isDarkModeEnabled($_SESSION['user_id'] ?? 0)): ?>
                            <img src="Images/sun.png" alt="Sun" style="width: 16px; height: 16px;">
                            <?php else: ?>
                            <img src="Images/moon.png" alt="moon" style="width: 16px; height: 16px;">
                            <?php endif; ?>
                        </span>
                        <span id="darkmode-text" style="font-size: 12px;">
                            <?php echo isDarkModeEnabled($_SESSION['user_id'] ?? 0) ? 'Light' : 'Dark'; ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Basket overlay -->
        <div id="popupBackground" style="cursor: pointer; height: 100%; left: 0; opacity: 0.7; position: fixed; top: 0;
                width: 100%; z-index: 10; background: #333; display:none;">
        </div>

        <div id="popup" style="display:none;">
            <div class="header"
                style="background: #ebecec; display: flex; height: 80px; align-items:center; padding:0 15px;">
                <div style="flex:1;">Basket</div>
                <span class="btn" id="closePopup">
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
                            You have no products in your basket
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
                <a class="" href="checkout.php">CHECKOUT</a>
            </div>
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

        <ul class="topnav">
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="products.php?category=Keyboards">Keyboards</a></li>
            <li><a href="products.php?category=Mice">Mice</a></li>
            <li><a href="products.php?category=Monitors">Monitors</a></li>
            <li><a href="about_us.php">About us</a></li>
        </ul>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isLoggedIn() && isDarkModeEnabled($_SESSION['user_id'])): ?>
            document.body.classList.add('dark-mode');

            const toggleBtn = document.getElementById('darkmode-toggle-btn');
            const toggleIcon = document.getElementById('darkmode-icon');
            const toggleText = document.getElementById('darkmode-text');

            if (toggleBtn) toggleBtn.title = 'Switch to Light Mode';
            toggleIcon.innerHTML = '<img src="/Images/sun.png" alt="Sun" style="width: 16px; height: 16px;">';
            if (toggleText) toggleText.textContent = 'Light';
            <?php endif; ?>
        });
        </script>
    </header>