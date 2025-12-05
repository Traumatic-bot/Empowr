<?php
require_once 'config.php';
$pageTitle = 'Product Details';
require_once 'header.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$product_id = (int)$_GET['id'];

$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    ?>
    <main style="max-width:900px;margin:40px auto;padding:20px;">
        <h1>Product not found</h1>
        <p>Sorry, we couldn't find that product</p>
        <p><a href="products.php"><-- Back to products</a></p>
    </main>
    <?php
    require_once 'footer.php';
    exit;
}
?>

<main style="max-width:1000px;margin:40px auto;padding:20px;">
    <section style="display:grid;grid-template-columns:minmax(0, 1.1fr) minmax(0, 1.3fr);gap:40px;">
        <div style="background:#f9f9f9;border-radius:12px;padding:20px;display:flex;align-items:center;justify-content:center;">
            <?php if(!empty($product['image_url'])): ?>
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                     alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                     style="max-width:100%;max-height:350px;object-fit:contain;">
            <?php else: ?>
                <div style="color:#777;">No image available</div>
            <?php endif; ?>
        </div>

        <div>
            <p style="margin:0 0 8px 0;font-size:13px;color:#777;">
                <a href="index.php" style="color:#777;text-decoration:none;">Home</a> /
                <a href="products.php<?php echo $product['category'] ? '?category=' . urlencode($product['category']) : ''; ?>"
                   style="color:#777;text-decoration:none;">
                    <?php echo htmlspecialchars($product['category'] ?? 'Products'); ?>
                </a>
            </p>

            <h1 style="margin:0 0 10px 0;font-size:26px;"><?php echo htmlspecialchars($product['product_name']); ?></h1>

            <div style="margin-bottom:10px;font-size:14px;color:#666;">
                SKU: <?php echo htmlspecialchars($product['product_id']); ?>
            </div>

            <div style="margin:15px 0;font-size:28px;font-weight:bold;color:#111;">
                £<?php echo number_format($product['price'], 2); ?>
            </div>

            <?php if(!empty($product['description'])): ?>
                <div style="margin:15px 0 20px 0;font-size:14px;line-height:1.5;color:#444;">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </div>
            <?php endif; ?>

            <div style="margin-bottom:20px;">
                <?php if($product['stock_quantity'] > 0): ?>
                    <span style="background:#2bb334;color:#fff;padding:4px 10px;border-radius:12px;font-size:12px;font-weight:bold;">
                        <?php if($product['stock_quantity'] <= 10): ?>
                            Only <?php echo (int)$product['stock_quantity']; ?> left!
                        <?php else: ?>
                            In Stock
                        <?php endif; ?>
                    </span>
                <?php else: ?>
                    <span style="background:#ff4444;color:#fff;padding:4px 10px;border-radius:12px;font-size:12px;font-weight:bold;">
                        Out of Stock
                    </span>
                <?php endif; ?>
            </div>

            <?php if(isLoggedIn() && $product['stock_quantity'] > 0): ?>
                <form method="post" action="add_to_cart.php" style="display:flex;gap:10px;align-items:center;margin-bottom:20px;">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

                    <label style="font-size:14px;">
                        Qty:
                        <input type="number" name="quantity" value="1" min="1"
                               max="<?php echo max(1, (int)$product['stock_quantity']); ?>"
                               style="width:60px;padding:6px;border-radius:4px;border:1px solid #ccc;margin-left:5px;">
                    </label>

                    <button type="submit"
                            style="padding:10px 20px;background:#ffee32;border:none;border-radius:6px;color:#111;font-weight:bold;font-size:14px;cursor:pointer;"
                            onmouseover="this.style.background='#e0d129'"
                            onmouseout="this.style.background='#ffee32'">
                        Add to Basket
                    </button>
                </form>
            <?php elseif(!isLoggedIn()): ?>
                <a href="login.php"
                   style="display:inline-block;padding:10px 20px;background:#ffee32;border-radius:6px;color:#111;font-weight:bold;font-size:14px;text-decoration:none;"
                   onmouseover="this.style.background='#e0d129'"
                   onmouseout="this.style.background='#ffee32'">
                    Login to Buy
                </a>
            <?php endif; ?>

            <p style="margin-top:10px;font-size:13px;color:#777;">
                <a href="products.php" style="color:#777;"><-- Back to all products</a>
            </p>
        </div>
    </section>
</main>

<?php require_once 'footer.php'; ?>