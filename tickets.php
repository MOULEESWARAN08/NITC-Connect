<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM hostel_tickets WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $tickets = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $tickets[] = $row;
        }
    }
    echo json_encode(['success' => true, 'tickets' => $tickets]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);

    $sql = "INSERT INTO hostel_tickets (user_id, subject, description, priority) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $subject, $description, $priority);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Ticket created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create ticket']);
    }
    $stmt->close();
}
$conn->close();
?>