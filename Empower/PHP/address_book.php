<?php
require_once 'config.php';

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

$pageTitle = 'Address Book';
require_once 'header.php';

$user_id = $_SESSION['user_id'];

// handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_address'])) {
        // add new address
        $address_type = sanitize($_POST['address_type']);
        $address_line1 = sanitize($_POST['address_line1']);
        $address_line2 = sanitize($_POST['address_line2']);
        $city = sanitize($_POST['city']);
        $postcode = sanitize($_POST['postcode']);
        $country = sanitize($_POST['country']);
        $is_default = isset($_POST['is_default']) ? 1 : 0;
        
        // if setting as default, update existing default addresses
        if ($is_default) {
            $updateQuery = "UPDATE user_addresses SET is_default = 0 WHERE user_id = $user_id";
            mysqli_query($conn, $updateQuery);
        }
        
        $insertQuery = "INSERT INTO user_addresses (user_id, address_type, address_line1, address_line2, city, postcode, country, is_default) 
                       VALUES ('$user_id', '$address_type', '$address_line1', '$address_line2', '$city', '$postcode', '$country', '$is_default')";
        
        if (mysqli_query($conn, $insertQuery)) {
            $message = "Address added successfully!";
            $messageType = "success";
        } else {
            $message = "Error adding address: " . mysqli_error($conn);
            $messageType = "error";
        }
        
    } elseif (isset($_POST['edit_address'])) {
        // edit existing address
        $address_id = sanitize($_POST['address_id']);
        $address_type = sanitize($_POST['address_type']);
        $address_line1 = sanitize($_POST['address_line1']);
        $address_line2 = sanitize($_POST['address_line2']);
        $city = sanitize($_POST['city']);
        $postcode = sanitize($_POST['postcode']);
        $country = sanitize($_POST['country']);
        $is_default = isset($_POST['is_default']) ? 1 : 0;
        
        // if setting as default, update existing default addresses
        if ($is_default) {
            $updateQuery = "UPDATE user_addresses SET is_default = 0 WHERE user_id = $user_id AND address_id != $address_id";
            mysqli_query($conn, $updateQuery);
        }
        
        $updateQuery = "UPDATE user_addresses SET 
                       address_type = '$address_type',
                       address_line1 = '$address_line1',
                       address_line2 = '$address_line2',
                       city = '$city',
                       postcode = '$postcode',
                       country = '$country',
                       is_default = '$is_default'
                       WHERE address_id = $address_id AND user_id = $user_id";
        
        if (mysqli_query($conn, $updateQuery)) {
            $message = "Address updated successfully!";
            $messageType = "success";
        } else {
            $message = "Error updating address: " . mysqli_error($conn);
            $messageType = "error";
        }
        
    } elseif (isset($_POST['delete_address'])) {
        // delete address
        $address_id = sanitize($_POST['address_id']);
        
        $deleteQuery = "DELETE FROM user_addresses WHERE address_id = $address_id AND user_id = $user_id";
        
        if (mysqli_query($conn, $deleteQuery)) {
            $message = "Address deleted successfully!";
            $messageType = "success";
        } else {
            $message = "Error deleting address: " . mysqli_error($conn);
            $messageType = "error";
        }
    }
}

// get user's addresses
$addressQuery = "SELECT * FROM user_addresses WHERE user_id = $user_id ORDER BY is_default DESC, address_type ASC";
$addressResult = mysqli_query($conn, $addressQuery);

// check if we're in edit mode
$editMode = false;
$editAddress = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = sanitize($_GET['edit']);
    $editQuery = "SELECT * FROM user_addresses WHERE address_id = $edit_id AND user_id = $user_id";
    $editResult = mysqli_query($conn, $editQuery);
    
    if (mysqli_num_rows($editResult) > 0) {
        $editMode = true;
        $editAddress = mysqli_fetch_assoc($editResult);
    }
}
?>

<main class="account-main-content">
    <div class="account-wrapper">
        <aside class="account-sidebar">
            <div class="account-user">
                <div class="account-user-name">
                    Hi <?php echo htmlspecialchars($_SESSION['first_name']); ?>
                </div>
                <div class="account-user-email">
                    <?php echo htmlspecialchars($_SESSION['email']); ?>
                </div>
            </div>

            <nav class="account-nav">
                <a href="order_history.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Order History</span>
                </a>

                <a href="personal_details.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Personal Details</span>
                </a>

                <a href="address_book.php" class="account-nav-item">
                    <span class="icon"></span>
                    <span>Addresses</span>
                </a>

                <a href="logout.php" class="account-nav-item logout">
                    <span class="icon"></span>
                    <span>Sign Out</span>
                </a>
            </nav>
        </aside>

        <main class="account-main">
            <h1>Address Book</h1>

            <?php if (isset($message)): ?>
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

            <!-- address Form (for add/edit) -->
            <?php if (isset($_GET['add']) || $editMode): ?>
            <div class="address-form-container" style="margin-bottom: 30px;">
                <h2><?php echo $editMode ? 'Edit Address' : 'Add New Address'; ?></h2>
                <form method="POST" action="address_book.php">
                    <input type="hidden" name="address_id"
                        value="<?php echo $editMode ? $editAddress['address_id'] : ''; ?>">

                    <div style="margin-bottom: 15px;">
                        <label for="address_type" style="display: block; margin-bottom: 5px; font-weight: bold;">Address
                            Type:</label>
                        <select name="address_type" id="address_type" required
                            style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="shipping"
                                <?php echo ($editMode && $editAddress['address_type'] === 'shipping') ? 'selected' : ''; ?>>
                                Shipping</option>
                            <option value="billing"
                                <?php echo ($editMode && $editAddress['address_type'] === 'billing') ? 'selected' : ''; ?>>
                                Billing</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label for="address_line1"
                            style="display: block; margin-bottom: 5px; font-weight: bold;">Address Line 1:*</label>
                        <input type="text" name="address_line1" id="address_line1" required
                            value="<?php echo $editMode ? htmlspecialchars($editAddress['address_line1']) : ''; ?>"
                            style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label for="address_line2"
                            style="display: block; margin-bottom: 5px; font-weight: bold;">Address Line 2:</label>
                        <input type="text" name="address_line2" id="address_line2"
                            value="<?php echo $editMode ? htmlspecialchars($editAddress['address_line2']) : ''; ?>"
                            style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div>
                            <label for="city"
                                style="display: block; margin-bottom: 5px; font-weight: bold;">City:*</label>
                            <input type="text" name="city" id="city" required
                                value="<?php echo $editMode ? htmlspecialchars($editAddress['city']) : ''; ?>"
                                style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        </div>

                        <div>
                            <label for="postcode"
                                style="display: block; margin-bottom: 5px; font-weight: bold;">Postcode:*</label>
                            <input type="text" name="postcode" id="postcode" required
                                value="<?php echo $editMode ? htmlspecialchars($editAddress['postcode']) : ''; ?>"
                                style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label for="country"
                            style="display: block; margin-bottom: 5px; font-weight: bold;">Country:</label>
                        <input type="text" name="country" id="country"
                            value="<?php echo $editMode ? htmlspecialchars($editAddress['country']) : 'United Kingdom'; ?>"
                            style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: flex; align-items: center; gap: 10px;">
                            <input type="checkbox" name="is_default" value="1"
                                <?php echo ($editMode && $editAddress['is_default']) ? 'checked' : ''; ?>>
                            Set as default address
                        </label>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <?php if ($editMode): ?>
                        <button type="submit" name="edit_address" class="save-btn" style="
                                    background: #ffee32;
                                    border: none;
                                    padding: 10px 20px;
                                    border-radius: 5px;
                                    cursor: pointer;
                                    font-weight: bold;
                                ">Update Address</button>
                        <?php else: ?>
                        <button type="submit" name="add_address" class="save-btn" style="
                                    background: #ffee32;
                                    border: none;
                                    padding: 10px 20px;
                                    border-radius: 5px;
                                    cursor: pointer;
                                    font-weight: bold;
                                ">Add Address</button>
                        <?php endif; ?>

                        <a href="address_book.php" style="
                                background: #f5f5f5;
                                border: 1px solid #ddd;
                                padding: 10px 20px;
                                border-radius: 5px;
                                cursor: pointer;
                                text-decoration: none;
                                color: #333;
                                font-weight: bold;
                            ">Cancel</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- display existing addresses -->
            <?php if (mysqli_num_rows($addressResult) > 0): ?>
            <?php while ($address = mysqli_fetch_assoc($addressResult)): 
                    $addressType = ucfirst($address['address_type']);
                    $isDefault = $address['is_default'] ? ' (Default)' : '';
                ?>
            <div class="address-card" style="
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        padding: 20px;
                        margin-bottom: 20px;
                        background: #f9f9f9;
                    ">
                <h3><?php echo $addressType . $isDefault; ?></h3>
                <p>
                    <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?><br>
                    <?php echo htmlspecialchars($address['address_line1']); ?><br>
                    <?php if (!empty($address['address_line2'])): ?>
                    <?php echo htmlspecialchars($address['address_line2']); ?><br>
                    <?php endif; ?>
                    <?php echo htmlspecialchars($address['city']); ?><br>
                    <?php echo htmlspecialchars($address['postcode']); ?><br>
                    <?php echo htmlspecialchars($address['country']); ?>
                </p>
                <div class="address-actions">
                    <a href="address_book.php?edit=<?php echo $address['address_id']; ?>">Edit</a>
                    <a href="#" onclick="confirmDelete(<?php echo $address['address_id']; ?>)"
                        style="color: #ff4444;">Remove</a>
                </div>
            </div>
            <?php endwhile; ?>
            <?php else: ?>
            <div
                style="text-align: center; padding: 40px; background: #f9f9f9; border-radius: 8px; margin-bottom: 20px;">
                <p>You haven't saved any addresses yet.</p>
            </div>
            <?php endif; ?>

            <?php if (!isset($_GET['add']) && !$editMode): ?>
            <a href="address_book.php?add=1" class="add-address-btn" style="
                    background: #ffee32;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-weight: bold;
                    text-decoration: none;
                    color: #333;
                    display: inline-block;
                ">Add New Address</a>
            <?php endif; ?>
        </main>
    </div>
</main>

<script>
function confirmDelete(addressId) {
    if (confirm('Are you sure you want to delete this address?')) {
        // create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'address_book.php';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'address_id';
        input.value = addressId;

        const deleteInput = document.createElement('input');
        deleteInput.type = 'hidden';
        deleteInput.name = 'delete_address';
        deleteInput.value = '1';

        form.appendChild(input);
        form.appendChild(deleteInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php require_once 'footer.php'; ?>