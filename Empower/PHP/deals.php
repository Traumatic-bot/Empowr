<?php
require_once 'config.php';
$pageTitle = 'Easter Sale - Deals';
require_once 'header.php';

// Easter sale page - displays all products with discounts
$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'discount';
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;

$min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';

// Base query - only products with active discounts
$base_where = "WHERE discount_percent IS NOT NULL AND discount_percent > 0";

if ($category) {
    $base_where .= " AND category = '$category'";
}
if ($min_price !== null) {
    $base_where .= " AND (price * (1 - discount_percent/100)) >= $min_price";
}
if ($max_price !== null) {
    $base_where .= " AND (price * (1 - discount_percent/100)) <= $max_price";
}

$query = "SELECT *, (price * (1 - discount_percent/100)) as discounted_price FROM products $base_where";
$count_query = "SELECT COUNT(*) as total FROM products $base_where";

// Sorting options
switch ($sort) {
    case 'price_high': 
        $query .= " ORDER BY discounted_price DESC"; 
        break;
    case 'price_low':  
        $query .= " ORDER BY discounted_price ASC";  
        break;
    case 'discount_high':
        $query .= " ORDER BY discount_percent DESC";
        break;
    default:           
        $query .= " ORDER BY discount_percent DESC, product_name ASC";      
        break;
}

$query .= " LIMIT $per_page OFFSET $offset";

$result = mysqli_query($conn, $query);
$count_result = mysqli_query($conn, $count_query);

if (!$result || !$count_result) {
    die("Query failed: " . mysqli_error($conn));
}

$total_row = mysqli_fetch_assoc($count_result);
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $per_page);

// Fetch categories for filter
$cat_query = "SELECT DISTINCT category FROM products WHERE discount_percent > 0 AND category IS NOT NULL ORDER BY category";
$cat_result = mysqli_query($conn, $cat_query);
$categories = [];
while ($cat = mysqli_fetch_assoc($cat_result)) {
    $categories[] = $cat['category'];
}

// Get highest discount for display
$max_discount_query = "SELECT MAX(discount_percent) as max_disc FROM products WHERE discount_percent > 0";
$max_disc_result = mysqli_query($conn, $max_discount_query);
$max_disc = mysqli_fetch_assoc($max_disc_result);
$highest_discount = round($max_disc['max_disc'] ?? 0);
?>

<style>
/* Deals page specific styles */
.deals-hero {
    background: linear-gradient(135deg, #ffe100 0%, #ffef7a 100%);
    padding: 60px 24px;
    text-align: center;
    border-radius: 0 0 40px 40px;
    margin-bottom: 40px;
}

.deals-hero h1 {
    font-size: 48px;
    margin-bottom: 15px;
    color: #1a1a1a;
}

.deals-hero p {
    font-size: 18px;
    color: #4a4a4a;
    max-width: 600px;
    margin: 0 auto;
}

.deals-timer {
    display: inline-block;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 15px 30px;
    border-radius: 50px;
    margin-top: 25px;
}

.deals-timer span {
    font-size: 24px;
    font-weight: bold;
    margin: 0 5px;
}

.deals-timer .label {
    font-size: 14px;
    margin: 0 10px;
}

.discount-badge-large {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #e53935;
    color: white;
    font-size: 14px;
    font-weight: bold;
    padding: 6px 12px;
    border-radius: 25px;
    z-index: 1;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.filter-panel {
    background: #fff8e1;
    border: 2px solid #ffd54f;
    border-radius: 20px;
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

.price-input-group {
    display: flex;
    gap: 10px;
    align-items: center;
}

.price-input-group input {
    width: 100px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}

.filter-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #ffd54f;
}

.btn-filter {
    padding: 10px;
    background: #ffee32;
    border: none;
    border-radius: 25px;
    color: #1a1a1a;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-filter:hover {
    background: #e0d129;
    transform: translateY(-2px);
}

.btn-clear {
    padding: 10px;
    background: #f0f0f0;
    border: none;
    border-radius: 25px;
    color: #666;
    font-weight: bold;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s;
}

.btn-clear:hover {
    background: #e0e0e0;
}

.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.deal-countdown {
    font-size: 12px;
    color: #e53935;
    margin-top: 8px;
    font-weight: bold;
}

.product-layout {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px 60px;
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 30px;
}

.result-meta {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.filters-row {
    max-width: 1280px;
    margin: 0 auto 20px;
    padding: 0 24px;
}

/* Responsive */
@media (max-width: 768px) {
    .deals-hero h1 {
        font-size: 32px;
    }
    
    .deals-hero p {
        font-size: 16px;
    }
    
    .deals-timer span {
        font-size: 18px;
    }
    
    .product-layout {
        grid-template-columns: 1fr !important;
    }
    
    .filter-panel {
        position: static;
        margin-bottom: 20px;
    }
    
    .result-meta {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .deals-hero {
        padding: 40px 16px;
    }
    
    .deals-timer {
        padding: 10px 20px;
    }
    
    .deals-timer span {
        font-size: 14px;
    }
}
</style>

<main>
    <!-- Hero Section with Easter Theme -->
    <section class="deals-hero">
        <h1>🐣 Easter Egg-stravaganza Sale! 🐰</h1>
        <p>Hop into savings with our biggest discounts of the season! Limited time offers on accessibility devices.</p>
        <div class="deals-timer">
            <span id="days">00</span><span class="label">Days</span>
            <span id="hours">00</span><span class="label">Hours</span>
            <span id="minutes">00</span><span class="label">Mins</span>
            <span id="seconds">00</span><span class="label">Secs</span>
        </div>
    </section>

    <div class="filters-row">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <label style="font-size: 15px; font-weight: 500;">Sort by:
                    <select onchange="updateSort(this.value)"
                        style="padding: 8px 12px; border-radius: 25px; border: 1px solid #ddd; background: white; margin-left: 8px;">
                        <option value="discount" <?php echo $sort == 'discount' ? 'selected' : ''; ?>>Biggest Discount</option>
                        <option value="discount_high" <?php echo $sort == 'discount_high' ? 'selected' : ''; ?>>Highest % Off</option>
                        <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                    </select>
                </label>
                
                <?php if($category): ?>
                <span style="background: #ffee32; padding: 5px 12px; border-radius: 20px; font-size: 13px;">
                    Category: <?php echo htmlspecialchars($category); ?>
                    <a href="deals.php" style="margin-left: 8px; text-decoration: none; color: #e53935;">✕</a>
                </span>
                <?php endif; ?>
            </div>
            
            <div>
                <span style="font-size: 14px; color: #666;">🔥 Limited time offers - while stocks last!</span>
            </div>
        </div>
    </div>

    <section class="product-layout">
        <!-- FILTER PANEL -->
        <aside class="filter-panel">
            <div style="font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #1a1a1a; text-align: center;">
                🎯 Filter Deals
            </div>

            <form method="get" id="filterForm">
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">

                <!-- Category Filter -->
                <div class="filter-group">
                    <div class="filter-title">📁 Categories</div>
                    <div style="display: flex; flex-direction: column; gap: 8px; max-height: 200px; overflow-y: auto;">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 14px;">
                            <input type="radio" name="category" value="" <?php echo !$category ? 'checked' : ''; ?>
                                onchange="this.form.submit()" style="width: 16px; height: 16px;">
                            <span>All Deals</span>
                        </label>
                        <?php foreach($categories as $cat): ?>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 14px;">
                            <input type="radio" name="category" value="<?php echo htmlspecialchars($cat); ?>"
                                <?php echo $category == $cat ? 'checked' : ''; ?>
                                onchange="this.form.submit()" style="width: 16px; height: 16px;">
                            <span><?php echo htmlspecialchars($cat); ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Price Range Filter -->
                <div class="filter-group">
                    <div class="filter-title">💰 Price Range</div>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <div class="price-input-group">
                            <span>Min £</span>
                            <input type="number" name="min_price" min="0" step="0.01"
                                value="<?php echo htmlspecialchars($min_price ?? ''); ?>"
                                placeholder="0.00">
                        </div>
                        <div class="price-input-group">
                            <span>Max £</span>
                            <input type="number" name="max_price" min="0" step="0.01"
                                value="<?php echo htmlspecialchars($max_price ?? ''); ?>"
                                placeholder="Any">
                        </div>
                    </div>
                </div>

                <div class="filter-buttons">
                    <button type="submit" class="btn-filter">Apply Filters</button>
                    <a href="deals.php" class="btn-clear">Clear All Filters</a>
                </div>
            </form>
        </aside>

        <!-- PRODUCTS GRID -->
        <div class="product-results">
            <div class="result-meta">
                <span style="font-weight: bold; color: #333; font-size: 16px;">
                    🎉 <?php echo $total_products; ?> Amazing Deals Found
                </span>
                <?php if($total_products > 0): ?>
                <span style="font-size: 13px; color: #e53935;">⬇️ Up to <?php echo $highest_discount; ?>% off! ⬇️</span>
                <?php endif; ?>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">
                <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($product = mysqli_fetch_assoc($result)):
                    $original = (float)$product['price'];
                    $discount_percent = (float)$product['discount_percent'];
                    $sale = $original * (1 - $discount_percent / 100);
                    $saving = round($discount_percent);
                ?>
                <div class="product-card" style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #eee; display: flex; flex-direction: column;">
                    <div class="discount-badge-large">
                        -<?php echo $saving; ?>% OFF
                    </div>
                    
                    <?php if($saving >= 25): ?>
                    <div style="position: absolute; top: 12px; left: 12px; background: #ff9800; color: white; font-size: 10px; font-weight: bold; padding: 3px 8px; border-radius: 12px;">
                        🔥 HOT DEAL
                    </div>
                    <?php endif; ?>

                    <div class="product-image" style="background: #f9f9f9; border-radius: 12px; padding: 15px; text-align: center; min-height: 180px; display: flex; align-items: center; justify-content: center;">
                        <?php if (!empty($product['image_url'])): ?>
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
                        <span style="text-decoration: line-through; color: #999; font-size: 14px;">£<?php echo number_format($original, 2); ?></span>
                        <span style="color: #e53935; font-size: 20px; font-weight: bold; margin-left: 8px;">£<?php echo number_format($sale, 2); ?></span>
                    </div>
                    
                    <div style="font-size: 13px; color: #2e7d32; margin-bottom: 8px;">
                        💰 Save £<?php echo number_format($original - $sale, 2); ?>
                    </div>

                    <div class="deal-countdown">
                        ⏰ Easter sale ends soon!
                    </div>

                    <div class="actions" style="display: flex; gap: 10px; margin-top: 15px;">
                        <a href="product_details.php?id=<?php echo $product['product_id']; ?>"
                            style="flex: 1; text-align: center; padding: 10px; border: 2px solid #111; border-radius: 25px; color: #111; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                            View Deal
                        </a>
                        <?php if (isLoggedIn() && $product['stock_quantity'] > 0): ?>
                        <form method="post" action="add_to_cart.php" style="flex: 1;">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="price" value="<?php echo $sale; ?>">
                            <button type="submit"
                                style="width: 100%; padding: 10px; background: #ffee32; border: none; border-radius: 25px; color: #111; font-weight: bold; cursor: pointer; transition: all 0.3s;">
                                🛒 Grab Deal
                            </button>
                        </form>
                        <?php elseif ($product['stock_quantity'] <= 0): ?>
                        <button disabled style="flex: 1; padding: 10px; background: #ccc; border: none; border-radius: 25px; color: #666;">Out of Stock</button>
                        <?php else: ?>
                        <a href="login.php"
                            style="flex: 1; text-align: center; padding: 10px; background: #ffee32; border-radius: 25px; color: #111; font-weight: bold; text-decoration: none;">
                            Login to Buy
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; background: white; border-radius: 16px;">
                    <div style="font-size: 48px; margin-bottom: 20px;">🐣</div>
                    <h2 style="color: #666; margin-bottom: 10px;">No deals found</h2>
                    <p style="color: #999; margin-bottom: 20px;">Try adjusting your filters or check back later for new deals!</p>
                    <a href="deals.php"
                        style="display: inline-block; padding: 12px 30px; background: #ffee32; border-radius: 25px; color: #111; font-weight: bold; text-decoration: none;">
                        View all deals
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div style="display: flex; justify-content: center; gap: 8px; margin-top: 40px; flex-wrap: wrap;">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?sort=<?php echo urlencode($sort); ?>&page=<?php echo $i; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $min_price !== null ? '&min_price=' . $min_price : ''; ?><?php echo $max_price !== null ? '&max_price=' . $max_price : ''; ?>"
                    style="padding: 8px 14px; border-radius: 8px; border: 1px solid #ddd; text-decoration: none; color: #111; background: <?php echo $i == $page ? '#ffee32' : '#fff'; ?>; font-weight: <?php echo $i == $page ? 'bold' : 'normal'; ?>;">
                    <?php echo $i; ?>
                </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<script>
// Update URL parameters
function updateSort(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', value);
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

// Easter Sale Countdown Timer
function startCountdown() {
    // Set the date for Easter sale end (Easter Monday - 7 days from now)
    const endDate = new Date();
    endDate.setDate(endDate.getDate() + 7);
    endDate.setHours(23, 59, 59, 999);
    
    function updateTimer() {
        const now = new Date();
        const diff = endDate - now;
        
        if (diff <= 0) {
            document.getElementById('days').innerHTML = '00';
            document.getElementById('hours').innerHTML = '00';
            document.getElementById('minutes').innerHTML = '00';
            document.getElementById('seconds').innerHTML = '00';
            return;
        }
        
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        document.getElementById('days').innerHTML = days.toString().padStart(2, '0');
        document.getElementById('hours').innerHTML = hours.toString().padStart(2, '0');
        document.getElementById('minutes').innerHTML = minutes.toString().padStart(2, '0');
        document.getElementById('seconds').innerHTML = seconds.toString().padStart(2, '0');
    }
    
    updateTimer();
    setInterval(updateTimer, 1000);
}

// Start the countdown when page loads
document.addEventListener('DOMContentLoaded', startCountdown);
</script>

<?php require_once 'footer.php'; ?>