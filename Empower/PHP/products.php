<?php
require_once 'config.php';
$pageTitle = 'Products';
require_once 'header.php';

function buildQueryString($new_params = []) {
    $params = $_GET;
    foreach ($new_params as $key => $value) {
        if ($value === null) {
            unset($params[$key]);
        } else {
            $params[$key] = $value;
        }
    }
    return http_build_query($params);
}

// GET parameters
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$search = isset($_GET['q']) ? sanitize($_GET['q']) : '';
$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'popular';
$brand = isset($_GET['brand']) ? sanitize($_GET['brand']) : '';
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;

// Fetch distinct categories
$category_query = "SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != '' ORDER BY category";
$category_result = mysqli_query($conn, $category_query);
$categories = [];
while ($cat = mysqli_fetch_assoc($category_result)) {
    $categories[] = $cat['category'];
}

// Base query
$query = "SELECT *, (price * (1 - COALESCE(discount_percent, 0)/100)) as discounted_price FROM products WHERE 1=1";
$count_query = "SELECT COUNT(*) as total FROM products WHERE 1=1";

if ($category) {
    $query .= " AND category = '$category'";
    $count_query .= " AND category = '$category'";
}
if ($search) {
    $query .= " AND (product_name LIKE '%$search%' OR description LIKE '%$search%')";
    $count_query .= " AND (product_name LIKE '%$search%' OR description LIKE '%$search%')";
}
if ($brand) {
    $query .= " AND category = '$brand'";
    $count_query .= " AND category = '$brand'";
}

// Sorting
switch($sort) {
    case 'price_high': $query .= " ORDER BY price DESC"; break;
    case 'price_low': $query .= " ORDER BY price ASC"; break;
    case 'discount': $query .= " ORDER BY discount_percent DESC"; break;
    case 'rating': $query .= " ORDER BY product_name ASC"; break;
    default: $query .= " ORDER BY product_name ASC"; break;
}

$query .= " LIMIT $per_page OFFSET $offset";

$result = mysqli_query($conn, $query);
$count_result = mysqli_query($conn, $count_query);
$total_row = mysqli_fetch_assoc($count_result);
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $per_page);
?>

<style>
/* Product page styles */
.page-banner {
    background: linear-gradient(135deg, #f8f8f8 0%, #ffffff 100%);
    padding: 40px 20px;
    text-align: center;
    margin-bottom: 30px;
}

.page-banner h1 {
    font-size: 36px;
    margin-bottom: 10px;
    color: #1a1a1a;
}

.page-banner p {
    font-size: 16px;
    color: #666;
}

.filters-row {
    max-width: 1280px;
    margin: 0 auto 20px;
    padding: 0 20px;
}

.filter-panel {
    background: #fff8e1;
    border: 2px solid #ffd54f;
    border-radius: 16px;
    padding: 20px;
    height: fit-content;
    position: sticky;
    top: 20px;
}

.filter-group {
    margin-bottom: 25px;
}

.filter-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 12px;
    color: #1a1a1a;
    padding-bottom: 8px;
    border-bottom: 2px solid #ffd54f;
}

.product-layout {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 20px 60px;
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 30px;
}

.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.discount-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #e53935;
    color: white;
    font-size: 12px;
    font-weight: bold;
    padding: 4px 10px;
    border-radius: 20px;
    z-index: 1;
}

.price-original {
    text-decoration: line-through;
    color: #999;
    font-size: 14px;
}

.price-discount {
    color: #e53935;
    font-weight: bold;
    font-size: 18px;
}

.price-current {
    font-size: 18px;
    font-weight: bold;
    color: #111;
}

.result-meta {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

@media (max-width: 768px) {
    .product-layout {
        grid-template-columns: 1fr;
    }
    
    .filter-panel {
        position: static;
    }
    
    .page-banner h1 {
        font-size: 28px;
    }
}
</style>

<main>
    <section class="page-banner">
        <h1><?php echo $category ? htmlspecialchars($category) : 'All Products'; ?></h1>
        <p>Discover our range of accessible computer peripherals designed for everyone</p>
    </section>

    <div class="filters-row">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;">
            <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                <label style="font-size: 15px;"> Sort by
                    <select onchange="window.location.href='?<?php echo buildQueryString(['sort' => '']); ?>&sort='+this.value"
                        style="padding: 8px 12px; border-radius: 25px; border: 1px solid #ddd; background: white;">
                        <option value="popular" <?php echo $sort == 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                        <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="discount" <?php echo $sort == 'discount' ? 'selected' : ''; ?>>Biggest Discount</option>
                    </select>
                </label>
            </div>
            <div>
                <span style="font-size:14px;color:#666;"><?php echo $total_products; ?> products available</span>
            </div>
        </div>
    </div>

    <section class="product-layout">
        <!-- FILTER PANEL -->
        <aside class="filter-panel">
            <div style="font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #1a1a1a; text-align: center;">
                FILTERS
            </div>

            <div class="filter-group">
                <div class="filter-title">CATEGORIES</div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px;">
                        <input type="radio" name="db_category" value="" <?php echo !$category ? 'checked' : ''; ?>
                            onchange="window.location='products.php'" style="width: 16px; height: 16px;">
                        <span>All Products</span>
                    </label>
                    <?php foreach($categories as $cat): ?>
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px;">
                        <input type="radio" name="db_category" value="<?php echo htmlspecialchars($cat); ?>"
                            <?php echo $category == $cat ? 'checked' : ''; ?>
                            onchange="window.location='products.php?category=<?php echo urlencode($cat); ?>'"
                            style="width: 16px; height: 16px;">
                        <span><?php echo htmlspecialchars($cat); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ffd54f;">
                <a href="products.php"
                    style="display: block; text-align: center; padding: 10px; background: #ffee32; border-radius: 25px; color: #111; font-weight: bold; text-decoration: none; font-size: 14px;">
                    CLEAR ALL FILTERS
                </a>
            </div>
        </aside>

        <!-- PRODUCTS LIST -->
        <div class="product-results">
            <div class="result-meta">
                <span style="font-weight: bold; color: #333; font-size: 16px;"><?php echo $total_products; ?> Items Found</span>
                <?php if($total_products > 0): ?>
                <span style="font-size: 13px; color: #e53935;">🔥 Check out our deals!</span>
                <?php endif; ?>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">
                <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($product = mysqli_fetch_assoc($result)):
                    $has_discount = $product['discount_percent'] > 0;
                    $final_price = $has_discount ? $product['discounted_price'] : $product['price'];
                ?>
                <div class="product-card" style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #eee; display: flex; flex-direction: column;">
                    <?php if($has_discount): ?>
                    <div class="discount-badge">
                        -<?php echo round($product['discount_percent']); ?>% OFF
                    </div>
                    <?php endif; ?>
                    
                    <div class="product-image" style="background: #f9f9f9; border-radius: 12px; padding: 15px; text-align: center; min-height: 180px; display: flex; align-items: center; justify-content: center;">
                        <?php if(!empty($product['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                            alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                            style="max-width: 100%; max-height: 150px; object-fit: contain;">
                        <?php else: ?>
                        <div style="width: 100%; height: 150px; display: flex; align-items: center; justify-content: center; color: #666;">
                            <?php echo htmlspecialchars($product['product_name']); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <h3 style="margin: 12px 0 8px; font-size: 16px; font-weight: bold; color: #333; line-height: 1.3;">
                        <?php echo htmlspecialchars($product['product_name']); ?>
                    </h3>

                    <div class="price-line" style="margin: 8px 0;">
                        <?php if($has_discount): ?>
                            <span class="price-original">£<?php echo number_format($product['price'], 2); ?></span>
                            <span class="price-discount"> £<?php echo number_format($final_price, 2); ?></span>
                        <?php else: ?>
                            <span class="price-current">£<?php echo number_format($product['price'], 2); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="actions" style="display: flex; gap: 10px; margin-top: auto;">
                        <a href="product_details.php?id=<?php echo $product['product_id']; ?>"
                            style="flex: 1; text-align: center; padding: 10px; border: 2px solid #111; border-radius: 25px; color: #111; text-decoration: none; font-weight: 500;">
                            View
                        </a>
                        <?php if(isLoggedIn() && $product['stock_quantity'] > 0): ?>
                        <form method="post" action="add_to_cart.php" style="flex:1;">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="price" value="<?php echo $final_price; ?>">
                            <button type="submit" style="width:100%; padding:10px; background:#ffee32; border:none; border-radius:25px; color:#111; font-weight:bold; cursor:pointer;">
                                Add to Basket
                            </button>
                        </form>
                        <?php elseif($product['stock_quantity'] <= 0): ?>
                        <button disabled style="flex:1; padding:10px; background:#ccc; border:none; border-radius:25px; color:#666;">Out of Stock</button>
                        <?php else: ?>
                        <a href="login.php" style="flex:1; text-align:center; padding:10px; background:#ffee32; border-radius:25px; color:#111; font-weight:bold; text-decoration:none;">Login to Buy</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <div style="grid-column:1/-1; text-align:center; padding:60px 20px; background:white; border-radius:16px;">
                    <h2 style="color:#666;">No products found</h2>
                    <a href="products.php" style="display:inline-block;padding:12px 30px; background:#ffee32; border-radius:25px; color:#111; font-weight:bold;">View all products</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php require_once 'footer.php'; ?>