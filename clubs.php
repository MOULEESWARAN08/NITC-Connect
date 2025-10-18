<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM clubs ORDER BY members_count DESC";
    $result = $conn->query($sql);
    $clubs = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $clubs[] = $row;
        }
    }
    echo json_encode(['success' => true, 'clubs' => $clubs]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Please login first']);
        exit;
    }

    $action = $_POST['action'];
    $club_id = intval($_POST['club_id']);
    $user_id = $_SESSION['user_id'];

    if ($action == 'join') {
        $check_sql = "SELECT * FROM club_members WHERE club_id = ? AND user_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $club_id, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Already a member']);
        } else {
            $sql = "INSERT INTO club_members (club_id, user_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $club_id, $user_id);

            if ($stmt->execute()) {
                $update_sql = "UPDATE clubs SET members_count = members_count + 1 WHERE club_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("i", $club_id);
                $update_stmt->execute();

                $points_sql = "INSERT INTO leaderboard (user_id, activity_type, points) VALUES (?, 'Club Joined', 20)";
                $points_stmt = $conn->prepare($points_sql);
                $points_stmt->bind_param("i", $user_id);
                $points_stmt->execute();

                $user_points_sql = "UPDATE users SET total_points = total_points + 20 WHERE user_id = ?";
                $user_points_stmt = $conn->prepare($user_points_sql);
                $user_points_stmt->bind_param("i", $user_id);
                $user_points_stmt->execute();

                echo json_encode(['success' => true, 'message' => 'Joined successfully! Earned 20 points']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to join']);
            }
        }
    }
}
$conn->close();
?>