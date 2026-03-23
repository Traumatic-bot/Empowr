<?php
require_once 'config.php';

<<<<<<< HEAD
// Only staff can access
=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
if (!isStaff()) {
    header('Location: index.php');
    exit();
}

$pageTitle = 'Manage Products';
require_once 'header.php';

<<<<<<< HEAD
// Handle actions
$message = '';
$messageType = '';

// Delete product
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Optional: check if product exists before deleting
=======
$message = '';
$messageType = '';

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
    $deleteQuery = "DELETE FROM products WHERE product_id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        $message = "Product deleted successfully.";
        $messageType = 'success';
    } else {
        $message = "Error deleting product: " . mysqli_error($conn);
        $messageType = 'error';
    }
}

<<<<<<< HEAD
// Add/Edit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $product_name = sanitize($_POST['product_name']);
=======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $product_name = sanitize($_POST['product_name']);
    $brand = sanitize($_POST['brand']);
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
    $price = floatval($_POST['price']);
    $stock_quantity = intval($_POST['stock_quantity']);
    $category = sanitize($_POST['category']);
    $description = sanitize($_POST['description']);
    $image_url = sanitize($_POST['image_url']);
<<<<<<< HEAD

    if ($product_id > 0) {
        // Update existing
        $updateQuery = "UPDATE products SET 
                        product_name = '$product_name',
                        price = $price,
                        stock_quantity = $stock_quantity,
                        category = '$category',
                        description = '$description',
                        image_url = '$image_url'
                        WHERE product_id = $product_id";
        if (mysqli_query($conn, $updateQuery)) {
            $message = "Product updated successfully.";
            $messageType = 'success';
        } else {
            $message = "Error updating product: " . mysqli_error($conn);
            $messageType = 'error';
        }
    } else {
        // Insert new
        $insertQuery = "INSERT INTO products (product_name, price, stock_quantity, category, description, image_url)
                        VALUES ('$product_name', $price, $stock_quantity, '$category', '$description', '$image_url')";
        if (mysqli_query($conn, $insertQuery)) {
            $message = "Product added successfully.";
            $messageType = 'success';
        } else {
            $message = "Error adding product: " . mysqli_error($conn);
            $messageType = 'error';
=======
    $discounted_price = isset($_POST['discounted_price']) && $_POST['discounted_price'] !== '' ? floatval($_POST['discounted_price']) : null;

    if (empty($product_name) || empty($brand) || $price <= 0) {
        $message = "Product name, brand, and price are required.";
        $messageType = 'error';
    } else {
        if ($product_id > 0) {
            if ($discounted_price !== null && $discounted_price >= $price) {
                $discounted_price = null; 
            }
            $discount_sql = $discounted_price !== null ? "'$discounted_price'" : "NULL";
            $updateQuery = "UPDATE products SET 
                            product_name = '$product_name',
                            brand = '$brand',
                            price = $price,
                            stock_quantity = $stock_quantity,
                            category = '$category',
                            description = '$description',
                            image_url = '$image_url',
                            discounted_price = $discount_sql
                            WHERE product_id = $product_id";
            if (mysqli_query($conn, $updateQuery)) {
                $message = "Product updated successfully.";
                $messageType = 'success';
                resetStockAlertFlags($product_id, $stock_quantity);
            } else {
                $message = "Error updating product: " . mysqli_error($conn);
                $messageType = 'error';
            }
        } else {
            $discount_sql = ($discounted_price !== null && $discounted_price > 0 && $discounted_price < $price) ? "'$discounted_price'" : "NULL";
            $insertQuery = "INSERT INTO products (product_name, brand, price, stock_quantity, category, description, image_url, discounted_price)
                            VALUES ('$product_name', '$brand', $price, $stock_quantity, '$category', '$description', '$image_url', $discount_sql)";
            if (mysqli_query($conn, $insertQuery)) {
                $message = "Product added successfully.";
                $messageType = 'success';
            } else {
                $message = "Error adding product: " . mysqli_error($conn);
                $messageType = 'error';
            }
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
        }
    }
}

<<<<<<< HEAD
// Fetch all products
=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
$productsQuery = "SELECT * FROM products ORDER BY product_id DESC";
$productsResult = mysqli_query($conn, $productsQuery);
?>

<main class="account-main-content">
    <div class="account-wrapper">
        <aside class="account-sidebar">
            <div class="account-user">
                <div class="account-user-name">
                    Admin: <?php echo htmlspecialchars($_SESSION['first_name']); ?>
                </div>
                <div class="account-user-email">
                    <?php echo htmlspecialchars($_SESSION['email']); ?>
                </div>
            </div>

            <nav class="account-nav">
                <a href="admin_dashboard.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Dashboard</span>
                </a>
                <a href="admin_products.php" class="account-nav-item is-active">
                    <span class="icon"></span>
                    <span>Manage Products</span>
                </a>
                <a href="admin_orders.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Manage Orders</span>
                </a>
                <a href="admin_users.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Manage Users</span>
                </a>
                <a href="dashboard.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Back to My Account</span>
                </a>
                <a href="logout.php" class="account-nav-item logout">
                    <span class="icon"></span>
                    <span>Sign Out</span>
                </a>
            </nav>
        </aside>

        <main class="account-main">
            <h1>Manage Products</h1>

            <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>" style="
                    padding: 10px;
                    margin-bottom: 20px;
                    border-radius: 4px;
                    background: <?php echo $messageType === 'success' ? '#d4edda' : '#f8d7da'; ?>;
                    color: <?php echo $messageType === 'success' ? '#155724' : '#721c24'; ?>;
                    border: 1px solid <?php echo $messageType === 'success' ? '#c3e6cb' : '#f5c6cb'; ?>;
                ">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>

<<<<<<< HEAD
            <!-- Add New Product Button -->
=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
            <button onclick="toggleAddForm()" style="margin-bottom: 20px; background: #ffee32; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                ➕ Add New Product
            </button>

<<<<<<< HEAD
            <!-- Add/Edit Form (hidden by default, shown when adding or editing) -->
=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
            <?php
            $editMode = false;
            $editProduct = null;
            if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
                $editId = (int)$_GET['edit'];
                $editQuery = "SELECT * FROM products WHERE product_id = $editId";
                $editResult = mysqli_query($conn, $editQuery);
                if ($editResult && mysqli_num_rows($editResult) > 0) {
                    $editMode = true;
                    $editProduct = mysqli_fetch_assoc($editResult);
                }
            }
            ?>
            <div id="productForm" style="<?php echo ($editMode || isset($_GET['add'])) ? 'display:block;' : 'display:none;'; ?> margin-bottom: 30px; padding: 20px; background: #f9f9f9; border-radius: 8px;">
                <h2><?php echo $editMode ? 'Edit Product' : 'Add New Product'; ?></h2>
                <form method="POST" action="admin_products.php">
                    <?php if ($editMode): ?>
                    <input type="hidden" name="product_id" value="<?php echo $editProduct['product_id']; ?>">
                    <?php endif; ?>

                    <div style="margin-bottom: 15px;">
                        <label>Product Name:*</label>
                        <input type="text" name="product_name" required value="<?php echo $editMode ? htmlspecialchars($editProduct['product_name']) : ''; ?>" style="width:100%; padding:8px;">
                    </div>
<<<<<<< HEAD
=======

                    <div style="margin-bottom: 15px;">
                        <label>Brand:*</label>
                        <input type="text" name="brand" required value="<?php echo $editMode ? htmlspecialchars($editProduct['brand']) : ''; ?>" style="width:100%; padding:8px;">
                    </div>

>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                    <div style="margin-bottom: 15px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label>Price (£):*</label>
                            <input type="number" step="0.01" name="price" required value="<?php echo $editMode ? $editProduct['price'] : ''; ?>" style="width:100%; padding:8px;">
                        </div>
                        <div>
                            <label>Stock Quantity:*</label>
                            <input type="number" name="stock_quantity" required value="<?php echo $editMode ? $editProduct['stock_quantity'] : ''; ?>" style="width:100%; padding:8px;">
                        </div>
                    </div>
<<<<<<< HEAD
                    <div style="margin-bottom: 15px;">
                        <label>Category:</label>
                        <input type="text" name="category" value="<?php echo $editMode ? htmlspecialchars($editProduct['category']) : ''; ?>" style="width:100%; padding:8px;">
                    </div>
=======

                    <div style="margin-bottom: 15px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label>Discounted Price (£):</label>
                            <input type="number" step="0.01" name="discounted_price" value="<?php echo $editMode && $editProduct['discounted_price'] > 0 ? $editProduct['discounted_price'] : ''; ?>" placeholder="Leave empty for no discount" style="width:100%; padding:8px;">
                            <small style="color:#666;">Must be less than original price</small>
                        </div>
                        <div>
                            <label>Category:</label>
                            <input type="text" name="category" value="<?php echo $editMode ? htmlspecialchars($editProduct['category']) : ''; ?>" style="width:100%; padding:8px;">
                        </div>
                    </div>

>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                    <div style="margin-bottom: 15px;">
                        <label>Description:</label>
                        <textarea name="description" rows="4" style="width:100%; padding:8px;"><?php echo $editMode ? htmlspecialchars($editProduct['description']) : ''; ?></textarea>
                    </div>
<<<<<<< HEAD
=======

>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                    <div style="margin-bottom: 15px;">
                        <label>Image URL:</label>
                        <input type="text" name="image_url" value="<?php echo $editMode ? htmlspecialchars($editProduct['image_url']) : ''; ?>" style="width:100%; padding:8px;">
                    </div>
<<<<<<< HEAD
=======

>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                    <div style="display: flex; gap: 10px;">
                        <button type="submit" style="background: #ffee32; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                            <?php echo $editMode ? 'Update Product' : 'Add Product'; ?>
                        </button>
                        <a href="admin_products.php" style="background: #ccc; padding: 10px 20px; border-radius: 5px; text-decoration: none; color: #333;">Cancel</a>
                    </div>
                </form>
            </div>

<<<<<<< HEAD
            <!-- Products Table -->
=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
            <?php if (mysqli_num_rows($productsResult) > 0): ?>
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f2f2f2;">
                        <th style="padding: 10px; text-align: left;">ID</th>
                        <th style="padding: 10px; text-align: left;">Name</th>
<<<<<<< HEAD
                        <th style="padding: 10px; text-align: left;">Price</th>
                        <th style="padding: 10px; text-align: left;">Stock</th>
                        <th style="padding: 10px; text-align: left;">Category</th>
                        <th style="padding: 10px; text-align: left;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = mysqli_fetch_assoc($productsResult)): ?>
                    <tr>
                        <td style="padding: 10px;"><?php echo $product['product_id']; ?></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td style="padding: 10px;">£<?php echo number_format($product['price'], 2); ?></td>
=======
                        <th style="padding: 10px; text-align: left;">Brand</th>
                        <th style="padding: 10px; text-align: left;">Price</th>
                        <th style="padding: 10px; text-align: left;">Discounted</th>
                        <th style="padding: 10px; text-align: left;">Stock</th>
                        <th style="padding: 10px; text-align: left;">Category</th>
                        <th style="padding: 10px; text-align: left;">Actions</th>
                     </tr>
                </thead>
                <tbody>
                    <?php while ($product = mysqli_fetch_assoc($productsResult)): ?>
                     <tr>
                        <td style="padding: 10px;"><?php echo $product['product_id']; ?></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($product['brand']); ?></td>
                        <td style="padding: 10px;">£<?php echo number_format($product['price'], 2); ?></td>
                        <td style="padding: 10px;">
                            <?php if (!empty($product['discounted_price']) && $product['discounted_price'] < $product['price']): ?>
                                £<?php echo number_format($product['discounted_price'], 2); ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                        <td style="padding: 10px;"><?php echo $product['stock_quantity']; ?></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($product['category']); ?></td>
                        <td style="padding: 10px;">
                            <a href="admin_products.php?edit=<?php echo $product['product_id']; ?>" style="margin-right: 5px; color: #007bff; text-decoration: none;">Edit</a>
                            <a href="admin_products.php?delete=<?php echo $product['product_id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');" style="color: #dc3545; text-decoration: none;">Delete</a>
                        </td>
<<<<<<< HEAD
                    </tr>
=======
                     </tr>
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>No products found.</p>
            <?php endif; ?>
        </main>
    </div>
</main>

<script>
function toggleAddForm() {
    var form = document.getElementById('productForm');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
<<<<<<< HEAD
        // Clear any edit mode by redirecting to add mode
        window.location.href = 'admin_products.php?add=1';
    } else {
        form.style.display = 'none';
        // Redirect to clear parameters
=======
        window.location.href = 'admin_products.php?add=1';
    } else {
        form.style.display = 'none';
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
        window.location.href = 'admin_products.php';
    }
}

<<<<<<< HEAD
// If URL has ?add=1, ensure form is visible
=======
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
<?php if (isset($_GET['add'])): ?>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('productForm').style.display = 'block';
});
<?php endif; ?>
</script>

<<<<<<< HEAD
<?php require_once 'footer.php'; ?>
=======
<?php require_once 'footer.php'; ?>
>>>>>>> e01683e1057fcc8626370d197f8ab0b125c61cec
