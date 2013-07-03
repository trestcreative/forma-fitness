<?php
try {
<<<<<<< HEAD
    $conn = new PDO('mysql:host=localhost;dbname=forma', 'root', 'root');
=======
    $conn = new PDO('mysql:host=formafitnessgb.com.mysql;dbname=formafitnessgb_', 'formafitnessgb_', 'jHn4mbJY');
>>>>>>> 463a88af8af2bdc259a22a4c1d10dc4135e90c3b
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
?>