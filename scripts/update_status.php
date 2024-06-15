<?php
require_once('../require/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    // Validate the status
    if (in_array($status, ['approved', 'cancelled'])) {
        $query = "UPDATE `cheque` SET `status` = '$status' WHERE `id` = $id";
        if (mysqli_query($db, $query)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid status value.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>