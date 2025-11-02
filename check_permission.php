 
<?php
function has_permission($conn, $role_id, $table_name, $operation) {
    if ($role_id == 1) return true; // Super Admin bypass

    $stmt = $conn->prepare("
        SELECT p.operations 
        FROM role_permissions rp
        JOIN permissions p ON rp.permission_id = p.id
        WHERE rp.role_id = ? AND p.table_name = ?
    ");
    $stmt->bind_param("is", $role_id, $table_name);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $operations = explode(',', $row['operations']);
        if (in_array($operation, $operations)) {
            return true;
        }
    }

    return false;
}


$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

$stmt = $conn->prepare("
  SELECT COUNT(*) AS unread 
  FROM notifications 
  WHERE is_read = 0 
    AND (
      user_id = ? 
      OR role_id = ? 
      OR JSON_CONTAINS(target_users, JSON_QUOTE(?), '$')
    )
");
$uid_str = (string)$user_id;
$stmt->bind_param("iis", $user_id, $role_id, $uid_str);
$stmt->execute();
$res = $stmt->get_result();
$count = $res->fetch_assoc()['unread'];
?>
