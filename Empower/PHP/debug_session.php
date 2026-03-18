<?php
require_once 'config.php';

echo '<pre>';
echo 'Logged in: ' . (isLoggedIn() ? 'Yes' : 'No') . "\n";
echo 'User ID: ' . ($_SESSION['user_id'] ?? 'NOT SET') . "\n";
echo 'User type from session: ' . ($_SESSION['user_type'] ?? 'NOT SET') . "\n";
echo 'isStaff() result: ' . (isStaff() ? 'TRUE' : 'FALSE') . "\n";
echo 'Session data: ';
print_r($_SESSION);
echo '</pre>';
?>