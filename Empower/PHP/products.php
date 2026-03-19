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

// Fetch distinct categories and brands
$category_query = "SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != '' ORDER BY category";
$category_result = mysqli_query($conn, $category_query);
$categories = [];
while ($cat = mysqli_fetch_assoc($category_result)) {
    $categories[] = $cat['category'];
}

$brand_query = "SELECT DISTINCT category as brand FROM products WHERE category IS NOT NULL AND category != '' ORDER BY category";
$brand_result = mysqli_query($conn, $brand_query);
$brands = [];
while ($b = mysqli_fetch_assoc($brand_result)) {
    $brands[] = $b['brand'];
}

// Base query
$query = "SELECT * FROM products WHERE 1=1";
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

<main>
    <section class="page-banner" style="text-align:center; margin: 30px 0;">
        <h1 style="margin:0; font-size:32px; font-weight:bold; color:#000;">
            <?php echo $category ? htmlspecialchars($category) : 'All Products'; ?>
        </h1>
    </section>

    <div class="filters-row">
        <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <label style="font-size: 15px;"> Sort by
                <select
                    onchange="window.location.href='?<?php echo buildQueryString(['sort' => '']); ?>&sort='+this.value"
                    style="padding: 5px 10px; border-radius: 6px;">
                    <option value="popular" <?php echo $sort == 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                    <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low
                    </option>
                    <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High
                    </option>
                    <option value="rating" <?php echo $sort == 'rating' ? 'selected' : ''; ?>>Customer rating</option>
                </select>
            </label>
        </div>
    </div>

    <section class="product-layout" style="grid-template-columns: 260px 1fr; gap: 25px;">
        <!-- FILTER PANEL -->
        <aside class="filter-panel"
            style="background-color: #fff8e1; border: 2px solid #ffd54f; border-radius: 10px; padding: 18px; height: fit-content;">
            <div style="font-size: 17px; font-weight: bold; margin-bottom: 15px; color: #333; text-align: center;">
                FILTERS</div>

            <div class="filter-group" style="margin-bottom: 20px;">
                <div
                    style="font-size: 15px; font-weight: 600; margin-bottom: 10px; color: #444; padding-bottom: 5px; border-bottom: 1px solid #ffd54f;">
                    CATEGORIES</div>
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

            <div class="filter-group" style="margin-bottom: 20px;">
                <div style="font-size: 15px; font-weight: 600; margin-bottom: 10px; color: #444;">BRANDS</div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <?php foreach($brands as $b): ?>
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px;">
                        <input type="checkbox" name="brand_check" value="<?php echo htmlspecialchars($b); ?>"
                            <?php echo $brand == $b ? 'checked' : ''; ?>
                            onchange="window.location='products.php?brand=<?php echo urlencode($b); ?>'"
                            style="width: 16px; height: 16px;">
                        <span><?php echo htmlspecialchars($b); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ffd54f;">
                <a href="products.php"
                    style="display: block; text-align: center; padding: 10px; background: #ffee32; border-radius: 6px; color: #111; font-weight: bold; text-decoration: none; font-size: 14px;">
                    CLEAR ALL FILTERS
                </a>
            </div>
        </aside>

        <!-- PRODUCTS LIST -->
        <div class="product-results">
            <div class="result-meta">
                <span style="font-weight: bold; color: #333; font-size: 16px;"><?php echo $total_products; ?> Items Found</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 22px; margin-top: 20px;">
                <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($product = mysqli_fetch_assoc($result)):
                    // Calculate discounted price
                    $discounted_price = $product['price'];
                    if(!empty($product['discount_percent']) && $product['discount_percent'] > 0){
                        $discounted_price = $product['price'] * (1 - $product['discount_percent']/100);
                    }
                ?>
                <div class="product-card" style="background: white; border-radius: 12px; padding: 18px; border: 1px solid #eee; display: flex; flex-direction: column;">
                    <div class="product-image" style="background: #f9f9f9; border-radius: 10px; padding: 12px; text-align: center; min-height: 180px; display: flex; align-items: center; justify-content: center;">
                        <?php if(!empty($product['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                            alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                            style="max-width: 100%; max-height: 160px; object-fit: contain;">
                        <?php else: ?>
                        <div style="width: 100%; height: 160px; display: flex; align-items: center; justify-content: center; color: #666;">
                            <?php echo htmlspecialchars($product['product_name']); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <h3 style="margin: 8px 0; font-size: 16px; font-weight: bold; color: #333;">
                        <?php echo htmlspecialchars($product['product_name']); ?>
                    </h3>

                    <div class="price-line" style="font-size: 18px; font-weight: bold; color: #111; margin: 8px 0;">
                        <?php if($discounted_price < $product['price']): ?>
                            <span style="text-decoration: line-through; color: #888;">£<?php echo number_format($product['price'], 2); ?></span>
                            <span style="color:#e53935; margin-left:6px;">£<?php echo number_format($discounted_price, 2); ?></span>
                            <span style="color:#fff; background:#e53935; padding:2px 6px; border-radius:4px; font-size:12px; margin-left:6px;"><?php echo (int)$product['discount_percent']; ?>% OFF</span>
                        <?php else: ?>
                            £<?php echo number_format($product['price'], 2); ?>
                        <?php endif; ?>
                    </div>

                    <div class="actions" style="display: flex; gap: 8px; margin-top: auto;">
                        <a href="product_details.php?id=<?php echo $product['product_id']; ?>"
                            style="flex: 1; text-align: center; padding: 10px; border: 2px solid #111; border-radius: 6px; color: #111; text-decoration: none;">
                            View
                        </a>
                        <?php if(isLoggedIn() && $product['stock_quantity'] > 0): ?>
                        <form method="post" action="add_to_cart.php" style="flex:1;">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="price" value="<?php echo $discounted_price; ?>">
                            <button type="submit" style="width:100%; padding:10px; background:#ffee32; border:none; border-radius:6px; color:#111; font-weight:bold;">
                                Add to Basket
                            </button>
                        </form>
                        <?php elseif($product['stock_quantity'] <= 0): ?>
                        <button disabled style="flex:1; padding:10px; background:#ccc; border:none; border-radius:6px; color:#666;">Out of Stock</button>
                        <?php else: ?>
                        <a href="login.php" style="flex:1; text-align:center; padding:10px; background:#ffee32; border-radius:6px; color:#111; font-weight:bold;">Login to Buy</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <div style="grid-column:1/-1; text-align:center; padding:50px 20px; background:white; border-radius:12px;">
                    <h2 style="color:#666;">No products found</h2>
                    <a href="products.php" style="display:inline-block;padding:12px 30px; background:#ffee32; border-radius:6px; color:#111; font-weight:bold;">View all products</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php require_once 'footer.php'; ?>