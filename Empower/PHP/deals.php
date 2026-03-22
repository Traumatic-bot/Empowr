<?php
require_once 'config.php';
$pageTitle = 'Easter Sale - Deals';
require_once 'header.php';

// new easter sale page instead of deals category

$sort   = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'popular';
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
$page   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;

$min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;

$base_where = "WHERE discounted_price IS NOT NULL AND discounted_price > 0 AND discounted_price < price";

if ($min_price !== null) {
    $base_where .= " AND discounted_price >= $min_price";
}
if ($max_price !== null) {
    $base_where .= " AND discounted_price <= $max_price";
}

$query       = "SELECT * FROM products $base_where";
$count_query = "SELECT COUNT(*) as total FROM products $base_where";

switch ($sort) {
    case 'price_high': $query .= " ORDER BY discounted_price DESC"; break;
    case 'price_low':  $query .= " ORDER BY discounted_price ASC";  break;
    default:           $query .= " ORDER BY product_name ASC";      break;
}

$query .= " LIMIT $per_page OFFSET $offset";

$result       = mysqli_query($conn, $query);
$count_result = mysqli_query($conn, $count_query);
$total_row    = mysqli_fetch_assoc($count_result);
$total_products = $total_row['total'];
$total_pages  = ceil($total_products / $per_page);
?>

<main>
    <section class="page-banner" style="text-align:center; margin: 30px 0;">
        <h1 style="margin:0; font-size:32px; font-weight:bold; color:#000;">🐣 Easter Sale</h1>
        <p style="color:#555; margin-top:8px;">Grab these limited-time discounts while they last!</p>
    </section>

    <div class="filters-row">
        <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <label style="font-size:15px;"> Sort by
                <select onchange="window.location.href=updateParam('sort', this.value)"
                    style="padding:5px 10px; border-radius:6px;">
                    <option value="popular"    <?php echo $sort == 'popular'    ? 'selected' : ''; ?>>Most Popular</option>
                    <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                    <option value="price_low"  <?php echo $sort == 'price_low'  ? 'selected' : ''; ?>>Price: Low to High</option>
                </select>
            </label>
        </div>
    </div>

    <section class="product-layout" style="grid-template-columns: 260px 1fr; gap: 25px;">
        <aside class="filter-panel"
            style="background-color:#fff8e1; border:2px solid #ffd54f; border-radius:10px; padding:18px; height:fit-content;">
            <div style="font-size:17px; font-weight:bold; margin-bottom:15px; color:#333; text-align:center;">
                FILTERS
            </div>

            <form method="get">
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">

                <div class="filter-group" style="margin-bottom:20px;">
                    <div style="font-size:15px; font-weight:600; margin-bottom:10px; color:#444; padding-bottom:5px; border-bottom:1px solid #ffd54f;">
                        PRICE RANGE
                    </div>
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <label style="font-size:14px; display:flex; align-items:center; gap:8px;">
                            Min £
                            <input type="number" name="min_price" min="0" step="0.01"
                                value="<?php echo htmlspecialchars($min_price ?? ''); ?>"
                                placeholder="0.00"
                                style="width:80px; padding:5px; border-radius:4px; border:1px solid #ccc;">
                        </label>
                        <label style="font-size:14px; display:flex; align-items:center; gap:8px;">
                            Max £
                            <input type="number" name="max_price" min="0" step="0.01"
                                value="<?php echo htmlspecialchars($max_price ?? ''); ?>"
                                placeholder="Any"
                                style="width:80px; padding:5px; border-radius:4px; border:1px solid #ccc;">
                        </label>
                    </div>
                </div>

                <div style="margin-top:20px; padding-top:15px; border-top:1px solid #ffd54f; display:flex; flex-direction:column; gap:10px;">
                    <button type="submit"
                        style="display:block; text-align:center; padding:10px; background:#ffee32; border:none; border-radius:6px; color:#111; font-weight:bold; font-size:14px; cursor:pointer;">
                        APPLY FILTERS
                    </button>
                    <a href="deals.php"
                        style="display:block; text-align:center; padding:10px; background:#eee; border-radius:6px; color:#111; font-weight:bold; text-decoration:none; font-size:14px;">
                        CLEAR FILTERS
                    </a>
                </div>
            </form>
        </aside>

        <div class="product-results">
            <div class="result-meta">
                <span style="font-weight:bold; color:#333; font-size:16px;"><?php echo $total_products; ?> Deals Found</span>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:22px; margin-top:20px;">
                <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($product = mysqli_fetch_assoc($result)):
                    $original = (float)$product['price'];
                    $sale     = (float)$product['discounted_price'];
                    $saving   = round((($original - $sale) / $original) * 100);
                ?>
                <div class="product-card"
                    style="background:white; border-radius:12px; padding:18px; border:1px solid #eee; display:flex; flex-direction:column; position:relative;">

                    <div style="position:absolute; top:12px; right:12px; background:#e53935; color:#fff; font-size:12px; font-weight:bold; padding:3px 8px; border-radius:12px;">
                        -<?php echo $saving; ?>%
                    </div>

                    <div class="product-image"
                        style="background:#f9f9f9; border-radius:10px; padding:12px; text-align:center; min-height:180px; display:flex; align-items:center; justify-content:center;">
                        <?php if (!empty($product['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                            alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                            style="max-width:100%; max-height:160px; object-fit:contain;">
                        <?php else: ?>
                        <div style="width:100%; height:160px; display:flex; align-items:center; justify-content:center; color:#666;">
                            <?php echo htmlspecialchars($product['product_name']); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <h3 style="margin:8px 0; font-size:16px; font-weight:bold; color:#333;">
                        <?php echo htmlspecialchars($product['product_name']); ?>
                    </h3>

                    <div class="price-line" style="font-size:18px; font-weight:bold; color:#111; margin:8px 0;">
                        <span style="text-decoration:line-through; color:#888;">£<?php echo number_format($original, 2); ?></span>
                        <span style="color:#e53935; margin-left:6px;">£<?php echo number_format($sale, 2); ?></span>
                        <span style="font-size:13px; color:#2e7d32; margin-left:6px;">Save £<?php echo number_format($original - $sale, 2); ?></span>
                    </div>

                    <div class="actions" style="display:flex; gap:8px; margin-top:auto;">
                        <a href="product_details.php?id=<?php echo $product['product_id']; ?>"
                            style="flex:1; text-align:center; padding:10px; border:2px solid #111; border-radius:6px; color:#111; text-decoration:none;">
                            View
                        </a>
                        <?php if (isLoggedIn() && $product['stock_quantity'] > 0): ?>
                        <form method="post" action="add_to_cart.php" style="flex:1;">
                            <input type="hidden" name="product_id"      value="<?php echo $product['product_id']; ?>">
                            <input type="hidden" name="quantity"         value="1">
                            <input type="hidden" name="discounted_price" value="<?php echo $sale; ?>">
                            <button type="submit"
                                style="width:100%; padding:10px; background:#ffee32; border:none; border-radius:6px; color:#111; font-weight:bold;">
                                Add to Basket
                            </button>
                        </form>
                        <?php elseif ($product['stock_quantity'] <= 0): ?>
                        <button disabled style="flex:1; padding:10px; background:#ccc; border:none; border-radius:6px; color:#666;">Out of Stock</button>
                        <?php else: ?>
                        <a href="login.php"
                            style="flex:1; text-align:center; padding:10px; background:#ffee32; border-radius:6px; color:#111; font-weight:bold; text-decoration:none;">
                            Login to Buy
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <div style="grid-column:1/-1; text-align:center; padding:50px 20px; background:white; border-radius:12px;">
                    <h2 style="color:#666;">No deals found</h2>
                    <a href="deals.php"
                        style="display:inline-block; padding:12px 30px; background:#ffee32; border-radius:6px; color:#111; font-weight:bold;">
                        View all deals
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <?php if ($total_pages > 1): ?>
            <div style="display:flex; justify-content:center; gap:8px; margin-top:30px; flex-wrap:wrap;">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?sort=<?php echo urlencode($sort); ?>&page=<?php echo $i; ?><?php echo $min_price !== null ? '&min_price=' . $min_price : ''; ?><?php echo $max_price !== null ? '&max_price=' . $max_price : ''; ?>"
                    style="padding:8px 14px; border-radius:6px; border:1px solid #ccc; text-decoration:none; color:#111; background:<?php echo $i == $page ? '#ffee32' : '#fff'; ?>; font-weight:<?php echo $i == $page ? 'bold' : 'normal'; ?>;">
                    <?php echo $i; ?>
                </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<script>
function updateParam(key, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);
    return url.toString();
}
</script>

<?php require_once 'footer.php'; ?>
