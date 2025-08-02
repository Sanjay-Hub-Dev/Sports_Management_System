<?php
$typed = "admin123";
$stored = '$2y$10$WjQk0b2bYza4fpbCqPpFeuPjvF2zOXWXGJtV3DoAPvWpJqkjoTnq6';

if (password_verify($typed, $stored)) {
    echo "✅ MATCHED";
} else {
    echo "❌ NOT MATCHED";
}
?>
