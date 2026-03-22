<?php
require_once 'config.php';
$pageTitle = 'Easter Sale - Deals';
require_once 'header.php';

$sort     = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'popular';
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
$page     = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset   = ($page - 1) * $per_page;

$min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;

$base_where = "WHERE discounted_price IS NOT NULL AND discounted_price > 0 AND discounted_price < price";

if ($min_price !== null) $base_where .= " AND discounted_price >= $min_price";
if ($max_price !== null) $base_where .= " AND discounted_price <= $max_price";

$query       = "SELECT * FROM products $base_where";
$count_query = "SELECT COUNT(*) as total FROM products $base_where";

switch ($sort) {
    case 'price_high': $query .= " ORDER BY discounted_price DESC"; break;
    case 'price_low':  $query .= " ORDER BY discounted_price ASC";  break;
    default:           $query .= " ORDER BY product_name ASC";      break;
}

$query .= " LIMIT $per_page OFFSET $offset";

$result         = mysqli_query($conn, $query);
$count_result   = mysqli_query($conn, $count_query);
$total_row      = mysqli_fetch_assoc($count_result);
$total_products = $total_row['total'];
$total_pages    = ceil($total_products / $per_page);
?>

<main>
    <section class="deals-banner">
        <h1>🐣 Easter Sale</h1>
        <p>Grab these limited-time discounts while they last!</p>
    </section>

    <div class="filters-row">
        <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <label style="font-size: 15px;">Sort by
                <select style="padding: 5px 10px; border-radius: 6px;" onchange="window.location.href=updateParam('sort', this.value)">
                    <option value="popular"    <?php echo $sort == 'popular'    ? 'selected' : ''; ?>>Most Popular</option>
                    <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                    <option value="price_low"  <?php echo $sort == 'price_low'  ? 'selected' : ''; ?>>Price: Low to High</option>
                </select>
            </label>
        </div>
    </div>

    <section class="product-layout deals-layout">
        <aside class="filter-panel deals-filter-panel">
            <div class="deals-filter-title">FILTERS</div>

            <form method="get">
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">

                <div class="filter-group">
                    <div class="deals-filter-group-title">PRICE RANGE</div>
                    <div class="deals-price-inputs">
                        <label class="deals-price-label">
                            Min £
                            <input type="number" name="min_price" min="0" step="0.01"
                                value="<?php echo htmlspecialchars($min_price ?? ''); ?>"
                                placeholder="0.00"
                                class="deals-price-input">
                        </label>
                        <label class="deals-price-label">
                            Max £
                            <input type="number" name="max_price" min="0" step="0.01"
                                value="<?php echo htmlspecialchars($max_price ?? ''); ?>"
                                placeholder="Any"
                                class="deals-price-input">
                        </label>
                    </div>
                </div>

                <div class="deals-filter-actions">
                    <button type="submit" class="deals-apply-btn">APPLY FILTERS</button>
                    <a href="deals.php" class="deals-clear-btn">CLEAR FILTERS</a>
                </div>
            </form>
        </aside>

        <div class="product-results">
            <div class="result-meta">
                <span class="deals-count"><?php echo $total_products; ?> Deals Found</span>
            </div>

            <div class="deals-grid">
                <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($product = mysqli_fetch_assoc($result)):
                    $original = (float)$product['price'];
                    $sale     = (float)$product['discounted_price'];
                    $saving   = round((($original - $sale) / $original) * 100);
                ?>
                <div class="deals-card">
                    <div class="deals-badge">-<?php echo $saving; ?>%</div>

                    <div class="deals-img-wrap">
                        <?php if (!empty($product['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        <?php else: ?>
                            <div class="deals-img-placeholder">
                                <?php echo htmlspecialchars($product['product_name']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h3 class="deals-card-title"><?php echo htmlspecialchars($product['product_name']); ?></h3>

                    <div class="deals-price-line">
                        <span class="deals-price-original">£<?php echo number_format($original, 2); ?></span>
                        <span class="deals-price-sale">£<?php echo number_format($sale, 2); ?></span>
                        <span class="deals-price-saving">Save £<?php echo number_format($original - $sale, 2); ?></span>
                    </div>

                    <div class="deals-card-actions">
                        <a href="product_details.php?id=<?php echo $product['product_id']; ?>" class="deals-btn-view">View</a>
                        <?php if (isLoggedIn() && $product['stock_quantity'] > 0): ?>
                        <form method="post" action="add_to_cart.php" style="flex:1;">
                            <input type="hidden" name="product_id"      value="<?php echo $product['product_id']; ?>">
                            <input type="hidden" name="quantity"         value="1">
                            <input type="hidden" name="discounted_price" value="<?php echo $sale; ?>">
                            <button type="submit" class="deals-btn-cart">Add to Basket</button>
                        </form>
                        <?php elseif ($product['stock_quantity'] <= 0): ?>
                        <button disabled class="deals-btn-disabled">Out of Stock</button>
                        <?php else: ?>
                        <a href="login.php" class="deals-btn-login">Login to Buy</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <div class="deals-empty">
                    <h2>No deals found</h2>
                    <a href="deals.php">View all deals</a>
                </div>
                <?php endif; ?>
            </div>

            <?php if ($total_pages > 1): ?>
            <div class="deals-pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?sort=<?php echo urlencode($sort); ?>&page=<?php echo $i; ?><?php echo $min_price !== null ? '&min_price=' . $min_price : ''; ?><?php echo $max_price !== null ? '&max_price=' . $max_price : ''; ?>"
                    class="deals-page-btn <?php echo $i == $page ? 'is-active' : ''; ?>">
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
