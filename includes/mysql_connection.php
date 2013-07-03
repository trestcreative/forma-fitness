<?php
try {
    $conn = new PDO('mysql:host=formafitnessgb.com.mysql;dbname=formafitnessgb_', 'formafitnessgb_', 'jHn4mbJY');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
?>