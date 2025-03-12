<?php
include "../Components/connect.php"; // Include your database connection

if (isset($_POST['phone']) && isset($_POST['role']) && isset($_POST['status'])) {
    $phone = $_POST['phone'];
    $role = intval($_POST['role']); // Ensure role is an integer
    $currentStatus = intval($_POST['status']); // Get current status (0 or 1)
    
    // Toggle status (if 0 -> 1, if 1 -> 0)
    $newStatus = ($currentStatus == 0) ? 1 : 0;

    // Disable autocommit to start transaction
    $conn->autocommit(false);
    $success = true; // Flag to track success

    try {
        // Update users table
        $stmt = $conn->prepare("UPDATE users SET status = ? WHERE user_phone = ?");
        $stmt->bind_param("is", $newStatus, $phone);
        if (!$stmt->execute()) throw new Exception("Failed to update users table");
        $stmt->close();

        if ($role == 3) {
            // Update teacher table
            $stmt = $conn->prepare("UPDATE teacher SET teacher_status = ? WHERE teacher_phone = ?");
            $stmt->bind_param("is", $newStatus, $phone);
            if (!$stmt->execute()) throw new Exception("Failed to update teacher table");
            $stmt->close();
        } elseif ($role == 4) {
            // Update student table
            $stmt = $conn->prepare("UPDATE student SET student_status = ? WHERE student_phone = ?");
            $stmt->bind_param("is", $newStatus, $phone);
            if (!$stmt->execute()) throw new Exception("Failed to update student table");
            $stmt->close();
        }

        // Commit the transaction
        $conn->commit();
        echo "success";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Re-enable autocommit
    $conn->autocommit(true);
    $conn->close();
} elseif(isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = intval($_POST['status']); // Get current status (0 or 1)
    
    // Toggle status (if 0 -> 1, if 1 -> 0)
    $newStatus = ($status == 0) ? 1 : 0;

    // Disable autocommit to start transaction
    $conn->autocommit(false);
    $success = true; // Flag to track success

    try {
        // Update course table
        $stmt = $conn->prepare("UPDATE course SET course_status = ? WHERE course_id = ?");
        $stmt->bind_param("ii", $newStatus, $id);
        if (!$stmt->execute()) throw new Exception("Failed to update course table");
        $stmt->close();

        // Commit the transaction
        $conn->commit();
        echo "success";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Re-enable autocommit
    $conn->autocommit(true);
    $conn->close();

} 

else {
    echo "Invalid request!";
}

?>