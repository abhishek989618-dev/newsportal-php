 
<?php
require '../config.php';
$id = (int)$_GET['id'];

$res = $conn->query("SELECT images FROM gallery WHERE id = $id");
$imgs = json_decode($res->fetch_assoc()['images'] ?? '[]', true);

foreach ($imgs as $img) {
    @unlink("../uploads/gallery/$img");
}

$conn->query("DELETE FROM gallery WHERE id = $id");
header("Location: index.php");
