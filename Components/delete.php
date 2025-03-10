<?php
include '../Components/connect.php';

// Enable detailed MySQL error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Function to delete user image
function deleteUserImage($conn, $phone) {
    // Fetch the image filename from the database
    $query = "SELECT image FROM users WHERE user_phone = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("SQL Prepare Error: " . $conn->error);
    }

    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($profileImage);
        $stmt->fetch();
        $stmt->close();

        if (!empty($profileImage)) {
            $fullPath = realpath($profileImage);
            if ($fullPath && file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    } else {
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['phone']) && isset($_POST['role'])) {
        $phone = trim($_POST['phone']);
        $role = intval($_POST['role']);

        try {
            $conn->begin_transaction(); // Start transaction

            // Delete the user's profile image
            deleteUserImage($conn, $phone);

            // Delete from the respective table based on role
            if ($role == 3) {
                $deleteTeacher = "DELETE FROM teacher WHERE teacher_phone = ?";
                $stmt = $conn->prepare($deleteTeacher);
                if (!$stmt) die("SQL Prepare Error: " . $conn->error);
                $stmt->bind_param("s", $phone);
                $stmt->execute();
                $stmt->close();
            } elseif ($role == 4) {
                $deleteStudent = "DELETE FROM student WHERE student_phone = ?";
                $stmt = $conn->prepare($deleteStudent);
                if (!$stmt) die("SQL Prepare Error: " . $conn->error);
                $stmt->bind_param("s", $phone);
                $stmt->execute();
                $stmt->close();
            }

            // Delete from users table
            $deleteUser = "DELETE FROM users WHERE user_phone = ?";
            $stmt = $conn->prepare($deleteUser);
            if (!$stmt) die("SQL Prepare Error: " . $conn->error);
            $stmt->bind_param("s", $phone);
            $stmt->execute();
            $stmt->close();

            // Reset Auto Increment (if necessary)
            $conn->query("ALTER TABLE users AUTO_INCREMENT = 1");
            if ($role == 3) {
                $conn->query("ALTER TABLE teacher AUTO_INCREMENT = 1");
            } elseif ($role == 4) {
                $conn->query("ALTER TABLE student AUTO_INCREMENT = 1");
            }

            $conn->commit(); // Commit transaction
            echo "success";
        } catch (Exception $e) {
            $conn->rollback(); // Rollback on error
            die("Transaction Failed: " . $e->getMessage());
        }
    }
    // Handle course deletion
    elseif (!empty($_POST['role']) && isset($_POST['id'])) {
        $role = intval($_POST['role']);
        $id = intval($_POST['id']);

        if ($role == 5) {
            try {
                $deleteCourse = "DELETE FROM course WHERE course_id = ?";
                $stmt = $conn->prepare($deleteCourse);
                if (!$stmt) die("SQL Prepare Error: " . $conn->error);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->close();
                echo "success";
            } catch (Exception $e) {
                die("Course Deletion Failed: " . $e->getMessage());
            }
        } else {
            echo "Invalid role for course deletion!";
        }
    } else {
        echo "Invalid or missing data!";
    }
} else {
    echo "Invalid request method!";
}

$conn->close();
