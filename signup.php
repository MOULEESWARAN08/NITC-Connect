<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $roll_number = mysqli_real_escape_string($conn, $_POST['roll_number']);
    $branch = mysqli_real_escape_string($conn, $_POST['branch']);
    $year = intval($_POST['year']);

    $check_sql = "SELECT * FROM users WHERE email = ? OR roll_number = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $email, $roll_number);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'User already exists']);
    } else {
        $sql = "INSERT INTO users (name, email, password, roll_number, branch, year) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $email, $password, $roll_number, $branch, $year);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            echo json_encode(['success' => true, 'message' => 'Registration successful', 'user' => ['name' => $name, 'email' => $email]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Registration failed']);
        }
        $stmt->close();
    }
    $check_stmt->close();
    $conn->close();
}
?>