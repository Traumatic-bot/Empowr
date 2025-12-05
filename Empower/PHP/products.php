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


$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$search = isset($_GET['q']) ? sanitize($_GET['q']) : '';
$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'popular';
$brand = isset($_GET['brand']) ? sanitize($_GET['brand']) : '';
$delivery = isset($_GET['delivery']) ? sanitize($_GET['delivery']) : '';
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;


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
        <aside class="filter-panel"
            style="background-color: #fff8e1; border: 2px solid #ffd54f; border-radius: 10px; padding: 18px; height: fit-content;">
            <div style="font-size: 17px; font-weight: bold; margin-bottom: 15px; color: #333; text-align: center;">
                FILTERS</div>

            <div class="filter-group" style="margin-bottom: 20px;">
                <div
                    style="font-size: 15px; font-weight: 600; margin-bottom: 10px; color: #444; padding-bottom: 5px; border-bottom: 1px solid #ffd54f;">
                    CATEGORIES</div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label
                        style="display: flex; align-items: center; gap: 8px; font-size: 14px; padding: 6px 8px; border-radius: 5px; transition: background 0.2s;"
                        onmouseover="this.style.background='#ffedb3'" onmouseout="this.style.background='transparent'">
                        <input type="radio" name="db_category" value="" <?php echo !$category ? 'checked' : ''; ?>
                            onchange="window.location='products.php'" style="width: 16px; height: 16px;">
                        <span>All Products</span>
                    </label>
                    <?php foreach($categories as $cat): ?>
                    <label
                        style="display: flex; align-items: center; gap: 8px; font-size: 14px; padding: 6px 8px; border-radius: 5px; transition: background 0.2s;"
                        onmouseover="this.style.background='#ffedb3'" onmouseout="this.style.background='transparent'">
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
                <div
                    style="font-size: 15px; font-weight: 600; margin-bottom: 10px; color: #444; padding-bottom: 5px; border-bottom: 1px solid #ffd54f;">
                    BRANDS</div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <?php foreach($brands as $b): ?>
                    <label
                        style="display: flex; align-items: center; gap: 8px; font-size: 14px; padding: 6px 8px; border-radius: 5px; transition: background 0.2s;"
                        onmouseover="this.style.background='#ffedb3'" onmouseout="this.style.background='transparent'">
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
                    style="display: block; text-align: center; padding: 10px; background: #ffee32; border-radius: 6px; color: #111; font-weight: bold; text-decoration: none; font-size: 14px; transition: background 0.2s;"
                    onmouseover="this.style.background='#e0d129'" onmouseout="this.style.background='#ffee32'">
                    CLEAR ALL FILTERS
                </a>
            </div>
        </aside>

        <div class="product-results">
            <div class="result-meta">
                <span style="font-weight: bold; color: #333; font-size: 16px;"><?php echo $total_products; ?> Items
                    Found</span>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <div class="products-per-page" style="font-size: 14px; color: #666;">
                        <span>Show: </span>
                        <select
                            onchange="window.location.href='?<?php echo buildQueryString(['per_page' => '']); ?>&per_page='+this.value"
                            style="padding:5px 10px; border-radius:4px; font-size: 14px; border: 1px solid #ddd;">
                            <option value="20" <?php echo $per_page == 20 ? 'selected' : ''; ?>>20</option>
                            <option value="40" <?php echo $per_page == 40 ? 'selected' : ''; ?>>40</option>
                            <option value="60" <?php echo $per_page == 60 ? 'selected' : ''; ?>>60</option>
                        </select>
                        <span> per page</span>
                    </div>
                    <?php if($category || $search || $brand): ?>
                    <a href="products.php"
                        style="text-decoration:none; color: #111; font-weight: bold; font-size: 14px; padding: 5px 10px; border-radius: 4px; background: #f5f5f5;">Reset
                        filters</a>
                    <?php endif; ?>
                </div>
            </div>

            <div
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 22px; margin-top: 20px;">
                <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($product = mysqli_fetch_assoc($result)): ?>
                <div class="product-card"
                    style="background: white; border-radius: 12px; padding: 18px; border: 1px solid #eee; box-shadow: 0 3px 10px rgba(0,0,0,0.05); display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;"
                    onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 5px 15px rgba(0,0,0,0.1)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 3px 10px rgba(0,0,0,0.05)'">
                    <div class="product-image"
                        style="background: #f9f9f9; border-radius: 10px; padding: 12px; margin-bottom: 12px; text-align: center; min-height: 180px; display: flex; align-items: center; justify-content: center;">
                        <?php if(!empty($product['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                            alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                            style="max-width: 100%; max-height: 160px; object-fit: contain;">
                        <?php else: ?>
                        <div
                            style="width: 100%; height: 160px; display: flex; align-items: center; justify-content: center; color: #666; font-size: 13px; padding: 10px;">
                            <?php echo htmlspecialchars($product['product_name']); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <h3
                        style="margin: 8px 0; font-size: 16px; font-weight: bold; color: #333; min-height: 40px; line-height: 1.3;">
                        <?php echo htmlspecialchars($product['product_name']); ?></h3>

                    <div class="rating-line" style="display: flex; align-items: center; gap: 4px; margin-bottom: 8px;">
                        <span style="color: #ffcc00; font-size: 14px;">★★★★★</span>
                        <strong style="font-size: 13px;">5/5</strong>
                        <span style="color: #666; font-size: 12px;">reviews</span>
                    </div>

                    <?php 
                $description = $product['description'] ?? '';
                $points = explode('.', $description);
                $points = array_filter(array_slice($points, 0, 2));
                ?>

                    <div
                        style="margin: 10px 0; color: #666; font-size: 13px; line-height: 1.4; min-height: 40px; flex-grow: 1;">
                        <?php if(!empty($points)): ?>
                        <?php foreach($points as $point): ?>
                        <div style="margin-bottom: 4px; font-size: 12px;">•
                            <?php echo htmlspecialchars(trim($point)); ?></div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div style="font-size: 12px;">
                            <?php echo strlen($description) > 80 ? substr(htmlspecialchars($description), 0, 80) . '...' : htmlspecialchars($description); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="price-line" style="font-size: 20px; font-weight: bold; color: #111; margin: 12px 0;">
                        £<?php echo number_format($product['price'], 2); ?>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px;">
                        <?php if($product['stock_quantity'] > 0): ?>
                        <span
                            style="background: #2bb334; color: white; padding: 3px 8px; border-radius: 10px; font-size: 11px; font-weight: bold;">
                            <?php if($product['stock_quantity'] <= 10): ?>
                            Only <?php echo $product['stock_quantity']; ?> left!
                            <?php else: ?>
                            In Stock
                            <?php endif; ?>
                        </span>
                        <?php else: ?>
                        <span
                            style="background: #ff4444; color: white; padding: 3px 8px; border-radius: 10px; font-size: 11px; font-weight: bold;">
                            Out of Stock
                        </span>
                        <?php endif; ?>

                        <?php if($product['price'] > 50): ?>
                        <span
                            style="background: #111; color: white; padding: 3px 8px; border-radius: 10px; font-size: 11px; font-weight: bold;">
                            Free Delivery
                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="actions" style="display: flex; gap: 8px; margin-top: auto;">
                        <a href="product_details.php?id=<?php echo $product['product_id']; ?>"
                            style="flex: 1; text-decoration: none; text-align: center; padding: 10px; border: 2px solid #111; border-radius: 6px; color: #111; font-weight: bold; font-size: 14px; background: transparent; transition: background 0.2s;"
                            onmouseover="this.style.background='#f5f5f5'"
                            onmouseout="this.style.background='transparent'">
                            View
                        </a>
                        <?php if(isLoggedIn()): ?>
                        <?php if($product['stock_quantity'] > 0): ?>
                        <form method="post" action="add_to_cart.php" style="flex: 1;">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit"
                                style="width: 100%; padding: 10px; background: #ffee32; border: none; border-radius: 6px; color: #111; font-weight: bold; font-size: 14px; cursor: pointer; transition: background 0.2s;"
                                onmouseover="this.style.background='#e0d129'"
                                onmouseout="this.style.background='#ffee32'">
                                Add to Basket
                            </button>
                        </form>
                        <?php else: ?>
                        <button disabled
                            style="flex: 1; padding: 10px; background: #ccc; border: none; border-radius: 6px; color: #666; font-weight: bold; font-size: 14px; cursor: not-allowed;">
                            Out of Stock
                        </button>
                        <?php endif; ?>
                        <?php else: ?>
                        <a href="login.php"
                            style="flex: 1; text-decoration: none; text-align: center; padding: 10px; background: #ffee32; border-radius: 6px; color: #111; font-weight: bold; font-size: 14px; transition: background 0.2s;"
                            onmouseover="this.style.background='#e0d129'" onmouseout="this.style.background='#ffee32'">
                            Login to Buy
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <div
                    style="grid-column: 1 / -1; text-align: center; padding: 50px 20px; background: white; border-radius: 12px; border: 1px solid #eee; box-shadow: 0 3px 10px rgba(0,0,0,0.05);">
                    <h2 style="color: #666; margin-bottom: 15px; font-size: 20px;">No products found</h2>
                    <p
                        style="color: #888; margin-bottom: 25px; max-width: 500px; margin-left: auto; margin-right: auto; font-size: 14px;">
                        <?php echo $search ? 'No results for "' . htmlspecialchars($search) . '"' : 'Try changing your filters'; ?>
                    </p>
                    <a href="products.php"
                        style="text-decoration:none;display:inline-block;padding:12px 30px; background: #ffee32; border-radius: 6px; color: #111; font-weight: bold; font-size: 14px; transition: background 0.2s;"
                        onmouseover="this.style.background='#e0d129'" onmouseout="this.style.background='#ffee32'">
                        View all products
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <?php if($total_pages > 1): ?>
            <div
                style="display:flex;justify-content:center;gap:8px;margin-top:40px;padding-top:25px;border-top:1px solid #eee;">
                <?php if($page > 1): ?>
                <a href="?<?php echo buildQueryString(['page' => $page-1]); ?>"
                    style="text-decoration:none;padding:8px 16px; border: 2px solid #111; border-radius: 6px; color: #111; font-weight: bold; font-size: 13px; transition: background 0.2s;"
                    onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='transparent'">
                    ← Previous
                </a>
                <?php endif; ?>

                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <?php if($i == 1 || $i == $total_pages || abs($i - $page) <= 2): ?>
                <a href="?<?php echo buildQueryString(['page' => $i]); ?>"
                    style="padding:8px 12px;text-decoration:none;background:<?php echo $i == $page ? '#ffee32' : 'transparent'; ?>;color:<?php echo $i == $page ? '#111' : '#666'; ?>;border:2px solid <?php echo $i == $page ? '#ffee32' : '#ddd'; ?>;border-radius:6px;font-weight:bold;font-size:13px;transition:background 0.2s;"
                    onmouseover="this.style.background='<?php echo $i == $page ? '#e0d129' : '#f5f5f5'; ?>'"
                    onmouseout="this.style.background='<?php echo $i == $page ? '#ffee32' : 'transparent'; ?>'">
                    <?php echo $i; ?>
                </a>
                <?php elseif(abs($i - $page) == 3): ?>
                <span style="padding:8px 6px;color:#666;font-weight:bold;font-size:13px;">...</span>
                <?php endif; ?>
                <?php endfor; ?>

                <?php if($page < $total_pages): ?>
                <a href="?<?php echo buildQueryString(['page' => $page+1]); ?>"
                    style="text-decoration:none;padding:8px 16px; border: 2px solid #111; border-radius: 6px; color: #111; font-weight: bold; font-size: 13px; transition: background 0.2s;"
                    onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='transparent'">
                    Next →
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require_once 'footer.php'; ?>