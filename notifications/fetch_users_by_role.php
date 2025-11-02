<?php
require '../config.php';

if (!isset($_GET['role_id'])) {
  echo json_encode([]);
  exit;
}

$role_id = intval($_GET['role_id']);
$result = $conn->query("SELECT id, name FROM users WHERE role_id = $role_id");

$users = [];
while ($row = $result->fetch_assoc()) {
  $users[] = [
    'id' => $row['id'],
    'name' => $row['name']
  ];
}

header('Content-Type: application/json');
echo json_encode($users);
