<?php
include '../Components/connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!empty($_POST['phone']) && isset($_POST['role'])) {
        $phone = trim($_POST['phone']);
        $role = intval($_POST['role']);

        echo "Received Data: Phone = $phone, Role = $role<br>";

        // Delete from the respective table based on role
        if ($role == 3) {
            $deleteTeacher = "DELETE FROM teacher WHERE teacher_phone = ?";
            $stmt = $conn->prepare($deleteTeacher);
        } elseif ($role == 4) {
            $deleteStudent = "DELETE FROM student WHERE student_phone = ?";
            $stmt = $conn->prepare($deleteStudent);
        }

        if ($stmt) {
            $stmt->bind_param("s", $phone);
            $stmt->execute();
        } else {
            die("SQL Error: " . $conn->error); // Debugging
        }

        // Delete from users table
        $deleteUser = "DELETE FROM users WHERE user_phone = ?";
        $stmt = $conn->prepare($deleteUser);

        if ($stmt) {
            $stmt->bind_param("s", $phone);
            if ($stmt->execute()) {
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
            die("SQL Error: " . $conn->error);
        }

    }
    // Handle source & ID deletion
    elseif (!empty($_POST['role']) && isset($_POST['id'])) {
        $role = trim($_POST['role']);
        $id = intval($_POST['id']);
        if ($role==5){
     
            $deleteLog = "DELETE FROM course WHERE course_id = ?";
         
        }


        // Delete from logs table
        $stmt = $conn->prepare($deleteLog);

        if ($stmt) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "Error deleting log: " . $conn->error;
            }
        } else {
            die("SQL Error: " . $conn->error); // Debugging
        }
    } else {
        echo "Invalid or missing data!";
    }

} else {
    echo "Invalid request method!";
}

$conn->close();
?>
