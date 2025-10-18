<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM events ORDER BY event_date ASC";
    $result = $conn->query($sql);
    $events = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    echo json_encode(['success' => true, 'events' => $events]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Please login first']);
        exit;
    }

    $action = $_POST['action'];
    $event_id = intval($_POST['event_id']);
    $user_id = $_SESSION['user_id'];

    if ($action == 'register') {
        $check_sql = "SELECT * FROM event_registrations WHERE event_id = ? AND user_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $event_id, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Already registered']);
        } else {
            $sql = "INSERT INTO event_registrations (event_id, user_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $event_id, $user_id);

            if ($stmt->execute()) {
                $points_sql = "INSERT INTO leaderboard (user_id, activity_type, points) VALUES (?, 'Event RSVP', 15)";
                $points_stmt = $conn->prepare($points_sql);
                $points_stmt->bind_param("i", $user_id);
                $points_stmt->execute();

                $user_points_sql = "UPDATE users SET total_points = total_points + 15 WHERE user_id = ?";
                $user_points_stmt = $conn->prepare($user_points_sql);
                $user_points_stmt->bind_param("i", $user_id);
                $user_points_stmt->execute();

                echo json_encode(['success' => true, 'message' => 'Registered successfully! Earned 15 points']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Registration failed']);
            }
        }
    }
}
$conn->close();
?>