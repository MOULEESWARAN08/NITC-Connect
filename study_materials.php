<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT sm.*, u.name as uploader_name FROM study_materials sm LEFT JOIN users u ON sm.uploaded_by = u.user_id ORDER BY sm.upload_date DESC";
    $result = $conn->query($sql);

    $materials = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $materials[] = $row;
        }
    }
    echo json_encode(['success' => true, 'materials' => $materials]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Please login first']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);

    $file_path = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $upload_dir = 'uploads/study_materials/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES['file']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $file_path = $target_file;
        }
    }

    $sql = "INSERT INTO study_materials (title, description, subject, file_path, uploaded_by) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $description, $subject, $file_path, $user_id);

    if ($stmt->execute()) {
        $points_sql = "INSERT INTO leaderboard (user_id, activity_type, points) VALUES (?, 'Study Material Upload', 10)";
        $points_stmt = $conn->prepare($points_sql);
        $points_stmt->bind_param("i", $user_id);
        $points_stmt->execute();

        $user_points_sql = "UPDATE users SET total_points = total_points + 10 WHERE user_id = ?";
        $user_points_stmt = $conn->prepare($user_points_sql);
        $user_points_stmt->bind_param("i", $user_id);
        $user_points_stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Material uploaded successfully! Earned 10 points']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Upload failed']);
    }
    $stmt->close();
}
$conn->close();
?>