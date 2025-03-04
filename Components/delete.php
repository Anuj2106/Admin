<?php
include '../Components/connect.php';

// Debugging: Check if POST data is received
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $phone = trim($_POST['phone']); // Sanitize phone input
    $role = intval($_POST['role']); // Convert role to integer

    // Debugging: Print received data
    echo "Received Data: Phone = $phone, Role = $role<br>";

    // Delete from the respective table based on role
    if ($role == 3) {
        $deleteTeacher = "DELETE FROM teacher WHERE teacher_phone = ?";
        $stmt = $conn->prepare($deleteTeacher);
        $stmt->bind_param("s", $phone);
        $stmt->execute();
    } elseif ($role == 4) {
        $deleteStudent = "DELETE FROM student WHERE student_phone = ?";
        $stmt = $conn->prepare($deleteStudent);
        $stmt->bind_param("s", $phone);
        $stmt->execute();
    }

    // Delete from users table for all roles
    $deleteUser = "DELETE FROM users WHERE user_phone = ?";
    $stmt = $conn->prepare($deleteUser);
    $stmt->bind_param("s", $phone);

    if ($stmt->execute()) {
        // Reset AUTO_INCREMENT properly
        $conn->query("ALTER TABLE users AUTO_INCREMENT = 1");

        if ($role == 3) {
            $conn->query("ALTER TABLE teacher AUTO_INCREMENT = 1");
        } elseif ($role == 4) {
            $conn->query("ALTER TABLE student AUTO_INCREMENT = 1");
        }

        echo "success";
    } else {
        echo "Error deleting user: " . $conn->error;
    }

} else {
    echo "Invalid request method!";
}

$conn->close();
?>
