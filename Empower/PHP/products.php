<?php
require_once 'config.php';
$pageTitle = 'Products';
require_once 'header.php';

$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$search = isset($_GET['q']) ? sanitize($_GET['q']) : '';

// Build query
$query = "SELECT * FROM products WHERE 1=1";
if ($category) {
    $query .= " AND category = '$category'";
}
if ($search) {
    $query .= " AND (product_name LIKE '%$search%' OR description LIKE '%$search%')";
}
$query .= " ORDER BY product_name";

$result = mysqli_query($conn, $query);
?>

<main>
    <div style="max-width: 1400px; margin: 40px auto; padding: 0 20px;">
        <h1><?php echo $category ? htmlspecialchars($category) : 'All Products'; ?></h1>
        
        <?php if ($search): ?>
            <p>Search results for: "<strong><?php echo htmlspecialchars($search); ?></strong>"</p>
        <?php endif; ?>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-top: 30px;">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($product = mysqli_fetch_assoc($result)): ?>
                    <div style="border: 1px solid #eee; border-radius: 8px; padding: 20px; text-align: center;">
                        <div style="height: 200px; background: #f5f5f5; border-radius: 4px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center;">
                            <div style="font-size: 14px; color: #666;">Product Image</div>
                        </div>
                        <h3 style="margin: 10px 0;"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <p style="color: #666; font-size: 14px; min-height: 60px;"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div style="font-size: 20px; font-weight: bold; color: #333; margin: 15px 0;">
                            £<?php echo number_format($product['price'], 2); ?>
                        </div>
                        <div style="color: #666; margin-bottom: 15px;">
                            Category: <?php echo htmlspecialchars($product['category']); ?>
                        </div>
                        <?php if (isLoggedIn()): ?>
                            <form method="post" action="add_to_cart.php" style="margin-top: 15px;">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                <div style="margin-bottom: 10px;">
                                    <label for="quantity_<?php echo $product['product_id']; ?>" style="display: block; margin-bottom: 5px;">Quantity:</label>
                                    <input type="number" name="quantity" id="quantity_<?php echo $product['product_id']; ?>" 
                                           value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" 
                                           style="width: 80px; padding: 5px; border: none; border-radius: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);" >
                                </div>
                                <button type="submit" style="background: #ffee32; border: none; padding: 10px 20px; 
                                        border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%;">
                                    Add to Cart
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="/empower/PHP/login.php" style="display: inline-block; background: #ffee32; border: none; 
                               padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; 
                               text-decoration: none; color: #333; width: 100%; text-align: center;">
                                Login to Purchase
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                    <p>No products found.</p>
                    <a href="products.php" style="color: #007bff; text-decoration: none;">View all products</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once 'footer.php'; ?>