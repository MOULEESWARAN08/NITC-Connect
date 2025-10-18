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
    $user_sql = "SELECT * FROM users WHERE user_id = ?";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user = $user_result->fetch_assoc();

    $rank_sql = "SELECT COUNT(*) + 1 as rank FROM users WHERE total_points > ?";
    $rank_stmt = $conn->prepare($rank_sql);
    $rank_stmt->bind_param("i", $user['total_points']);
    $rank_stmt->execute();
    $rank_result = $rank_stmt->get_result();
    $rank_data = $rank_result->fetch_assoc();

    $events_sql = "SELECT COUNT(*) as events_count FROM event_registrations WHERE user_id = ?";
    $events_stmt = $conn->prepare($events_sql);
    $events_stmt->bind_param("i", $user_id);
    $events_stmt->execute();
    $events_result = $events_stmt->get_result();
    $events_data = $events_result->fetch_assoc();

    $tickets_sql = "SELECT COUNT(*) as tickets_count FROM hostel_tickets WHERE user_id = ? AND status != 'closed'";
    $tickets_stmt = $conn->prepare($tickets_sql);
    $tickets_stmt->bind_param("i", $user_id);
    $tickets_stmt->execute();
    $tickets_result = $tickets_stmt->get_result();
    $tickets_data = $tickets_result->fetch_assoc();

    $activity_sql = "SELECT * FROM leaderboard WHERE user_id = ? ORDER BY activity_date DESC LIMIT 5";
    $activity_stmt = $conn->prepare($activity_sql);
    $activity_stmt->bind_param("i", $user_id);
    $activity_stmt->execute();
    $activity_result = $activity_stmt->get_result();

    $activities = [];
    while($row = $activity_result->fetch_assoc()) {
        $activities[] = $row;
    }

    $dashboard_data = [
        'user' => $user,
        'rank' => $rank_data['rank'],
        'events_attended' => $events_data['events_count'],
        'active_tickets' => $tickets_data['tickets_count'],
        'recent_activity' => $activities
    ];

    echo json_encode(['success' => true, 'data' => $dashboard_data]);
}
$conn->close();
?>