<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT user_id, name, total_points, roll_number, branch FROM users ORDER BY total_points DESC LIMIT 50";
    $result = $conn->query($sql);

    $leaderboard = [];
    $rank = 1;

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $row['rank'] = $rank++;
            $leaderboard[] = $row;
        }
    }
    echo json_encode(['success' => true, 'leaderboard' => $leaderboard]);
}
$conn->close();
?>