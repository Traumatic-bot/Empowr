<footer>
    <div class="footer-links" style="color: black;">
        <a href="about_us.php" style="color: black; text-decoration: none;">About Us</a> ｜
        <?php if (isLoggedIn()): ?>
            <a href="dashboard.php" style="color: black; text-decoration: none;">My Account</a> ｜
        <?php else: ?>
            <a href="login.php" style="color: black; text-decoration: none;">My Account</a> ｜
        <?php endif; ?>
        <a href="contact_us.php" style="color: black; text-decoration: none;">Contact Us</a>
    </div>
</footer>

<script>
    // Cart functionality
    function updateCartQuantity(productId, change) {
        <?php if (isLoggedIn()): ?>
            fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity_change=${change}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        <?php else: ?>
            alert('Please log in to update your cart');
            window.location.href = 'login.php';
        <?php endif; ?>
    }
    
    function removeFromCart(productId) {
        <?php if (isLoggedIn()): ?>
            if (confirm('Remove this item from cart?')) {
                fetch('update_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&remove=1`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        <?php else: ?>
            alert('Please log in to manage your cart');
            window.location.href = 'login.php';
        <?php endif; ?>
    }
</script>

</body>
</html>