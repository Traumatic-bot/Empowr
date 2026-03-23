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
<<<<<<< HEAD
    <main style="max-width:900px;margin:40px auto;padding:20px;">
        <h1>Product not found</h1>
        <p>Sorry, we couldn't find that product</p>
        <p><a href="products.php"><-- Back to products</a></p>
    </main>
    <?php
=======
<main style="max-width:900px;margin:40px auto;padding:20px;">
    <h1>Product not found</h1>
    <p>Sorry, we couldn't find that product</p>
    <p><a href="products.php">
            <-- Back to products</a>
    </p>
</main>
<?php
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
    require_once 'footer.php';
    exit;
}

<<<<<<< HEAD
// --- Discount calculation ---
$discount = isset($product['discount_percent']) ? (float)$product['discount_percent'] : 0;
$price = (float)$product['price'];
$discounted_price = $discount > 0 ? $price - ($price * $discount / 100) : $price;
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
=======
$ratingRow = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT ROUND(AVG(rating), 1) AS avg_rating, COUNT(*) AS review_count
     FROM reviews
     WHERE product_id = $product_id AND review_type = 'product'"));
$avg_rating   = $ratingRow ? (float)$ratingRow['avg_rating']  : 0;
$review_count = $ratingRow ? (int)$ratingRow['review_count']  : 0;

$reviewsResult = mysqli_query($conn,
    "SELECT rv.rating, rv.review_text, rv.created_at,
            u.first_name
     FROM reviews rv
     JOIN users u ON rv.user_id = u.user_id
     WHERE rv.product_id = $product_id AND rv.review_type = 'product'
     ORDER BY rv.created_at DESC");
$reviews = [];
if ($reviewsResult) {
    while ($row = mysqli_fetch_assoc($reviewsResult)) {
        $reviews[] = $row;
    }
}

$has_discount  = false;
$display_price = $product['price'];
if (!empty($product['discounted_price']) && $product['discounted_price'] > 0 && $product['discounted_price'] < $product['price']) {
    $has_discount  = true;
    $display_price = $product['discounted_price'];
}
?>

<main style="max-width:1000px;margin:40px auto;padding:20px;">


    <section style="display:grid;grid-template-columns:minmax(0,1.1fr) minmax(0,1.3fr);gap:40px;">
        <div style="background:#f9f9f9;border-radius:12px;padding:20px;display:flex;align-items:center;justify-content:center;">
            <?php if (!empty($product['image_url'])): ?>
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                style="max-width:100%;max-height:350px;object-fit:contain;">
            <?php else: ?>
            <div style="color:#777;">No image available</div>
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
            <?php endif; ?>
        </div>

        <div>
            <p style="margin:0 0 8px 0;font-size:13px;color:#777;">
                <a href="index.php" style="color:#777;text-decoration:none;">Home</a> /
<<<<<<< HEAD
                <a href="products.php<?php echo $product['category'] ? '?category=' . urlencode($product['category']) : ''; ?>"
                   style="color:#777;text-decoration:none;">
=======
                <a href="products.php<?php echo $product['category'] ? '?category='.urlencode($product['category']) : ''; ?>"
                    style="color:#777;text-decoration:none;">
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                    <?php echo htmlspecialchars($product['category'] ?? 'Products'); ?>
                </a>
            </p>

            <h1 style="margin:0 0 10px 0;font-size:26px;"><?php echo htmlspecialchars($product['product_name']); ?></h1>

<<<<<<< HEAD
            <div style="margin-bottom:10px;font-size:14px;color:#666;">
                SKU: <?php echo htmlspecialchars($product['product_id']); ?>
            </div>

            <!-- Price with discount -->
            <div style="margin:15px 0;font-size:28px;font-weight:bold;color:#111;">
                <?php if($discount > 0): ?>
                    <span style="text-decoration:line-through;color:#777;font-size:20px;">
                        £<?php echo number_format($price, 2); ?>
                    </span>
                       
                    <span style="color:#e53935;">
                        £<?php echo number_format($discounted_price, 2); ?>
                    </span>
                    <span style="background:#e53935;color:#fff;padding:2px 6px;border-radius:6px;font-size:12px;margin-left:5px;">
                        -<?php echo $discount; ?>%
                    </span>
                <?php else: ?>
                    £<?php echo number_format($price, 2); ?>
                <?php endif; ?>
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
                    <input type="hidden" name="discounted_price" value="<?php echo $discounted_price; ?>">

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
=======

            <div class="pd-rating-row">
                <?php if ($review_count > 0): ?>
                    <div class="pd-stars">
                        <?php for ($s = 1; $s <= 5; $s++): ?>
                            <span class="pd-star <?php echo $s <= round($avg_rating) ? 'is-active' : ''; ?>">&#9733;</span>
                        <?php endfor; ?>
                    </div>
                    <span class="pd-avg"><?php echo number_format($avg_rating, 1); ?></span>
                    <span class="pd-review-count">(<?php echo $review_count; ?> <?php echo $review_count === 1 ? 'review' : 'reviews'; ?>)</span>
                <?php else: ?>
                    <div class="pd-stars">
                        <?php for ($s = 1; $s <= 5; $s++): ?>
                            <span class="pd-star">&#9733;</span>
                        <?php endfor; ?>
                    </div>
                    <span class="pd-review-count">No reviews yet</span>
                <?php endif; ?>
            </div>

            <div style="margin:15px 0;font-size:28px;font-weight:bold;color:#111;">
                <?php if ($has_discount): ?>
                <del style="font-size:20px;color:#777;">£<?php echo number_format($product['price'], 2); ?></del>
                <span style="color:#d00;"> £<?php echo number_format($display_price, 2); ?></span>
                <?php else: ?>
                £<?php echo number_format($product['price'], 2); ?>
                <?php endif; ?>
            </div>

            <?php if (!empty($product['description'])): ?>
            <div style="margin:15px 0 20px 0;line-height:1.5;color:#444;">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </div>
            <?php endif; ?>

            <div style="margin-bottom:20px;">
                <?php if ($product['stock_quantity'] > 0): ?>
                <span style="background:#2bb334;color:#fff;padding:4px 10px;border-radius:12px;font-size:12px;font-weight:bold;">
                    <?php if ($product['stock_quantity'] <= 10): ?>
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

            <?php if (isLoggedIn() && $product['stock_quantity'] > 0): ?>
            <form method="post" action="add_to_cart.php"
                style="display:flex;gap:10px;align-items:center;margin-bottom:20px;">
                <input type="hidden" name="product_id"        value="<?php echo $product['product_id']; ?>">
                <input type="hidden" name="discounted_price"  value="<?php echo $display_price; ?>">
                <label style="font-size:14px;">
                    Qty:
                    <input type="number" name="quantity" value="1" min="1"
                        max="<?php echo max(1, (int)$product['stock_quantity']); ?>"
                        style="width:60px;padding:6px;border-radius:4px;border:1px solid #ccc;margin-left:5px;">
                </label>
                <button type="submit"
                    style="padding:10px 20px;background:#ffee32;border:none;border-radius:6px;color:#111;font-weight:bold;font-size:14px;cursor:pointer;"
                    onmouseover="this.style.background='#e0d129'" onmouseout="this.style.background='#ffee32'">
                    Add to Basket
                </button>
            </form>
            <?php elseif (!isLoggedIn()): ?>
            <a href="login.php"
                style="display:inline-block;padding:10px 20px;background:#ffee32;border-radius:6px;color:#111;font-weight:bold;font-size:14px;text-decoration:none;"
                onmouseover="this.style.background='#e0d129'" onmouseout="this.style.background='#ffee32'">
                Login to Buy
            </a>
            <?php endif; ?>

            <p style="margin-top:10px;font-size:13px;color:#777;">
                <a href="products.php" style="color:#777;">
                    <-- Back to all products</a>
            </p>
        </div>
    </section>


    <section class="pd-reviews-section">
        <div class="pd-reviews-header">
            <h2 class="pd-reviews-title">Customer Reviews</h2>
            <?php if ($review_count > 0): ?>
                <div class="pd-reviews-summary">
                    <span class="pd-summary-score"><?php echo number_format($avg_rating, 1); ?></span>
                    <div>
                        <div class="pd-stars pd-stars--lg">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <span class="pd-star <?php echo $s <= round($avg_rating) ? 'is-active' : ''; ?>">&#9733;</span>
                            <?php endfor; ?>
                        </div>
                        <div style="font-size:13px;color:#888;margin-top:2px;">
                            Based on <?php echo $review_count; ?> <?php echo $review_count === 1 ? 'review' : 'reviews'; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if (empty($reviews)): ?>
            <div class="pd-no-reviews">
                <p>No reviews yet - be the first to leave one after your purchase.</p>
            </div>
        <?php else: ?>
            <div class="pd-reviews-list">
                <?php foreach ($reviews as $rev): ?>
                <div class="pd-review-card">
                    <div class="pd-review-top">
                        <div class="pd-reviewer-name"><?php echo htmlspecialchars($rev['first_name']); ?></div>
                        <div class="pd-stars">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <span class="pd-star <?php echo $s <= (int)$rev['rating'] ? 'is-active' : ''; ?>">&#9733;</span>
                            <?php endfor; ?>
                        </div>
                        <div class="pd-review-date"><?php echo date('d M Y', strtotime($rev['created_at'])); ?></div>
                    </div>
                    <?php if (!empty(trim($rev['review_text']))): ?>
                        <p class="pd-review-text"><?php echo nl2br(htmlspecialchars($rev['review_text'])); ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

</main>

<?php require_once 'footer.php'; ?>
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
