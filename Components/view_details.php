<?php
include '../Components/connect.php';

if (isset($_POST['id']) || isset($_POST['role']) && isset($_POST['source'])) {
    $id = intval($_POST['id']);
    $role = intval($_POST['role']);
    $source = strtolower(trim($_POST['source'])); // Convert to lowercase for consistency

    $query = "";
    $stmt = null;

    // Handling different sources
    if ($source === 'users') {
        if ($role == 1 || $role == 2) { // Students or General Users
            $query = "SELECT users.*, master_role.Role 
                      FROM users 
                      INNER JOIN master_role ON users.role_id = master_role.role_id 
                      WHERE users.user_id = ?";
        } elseif ($role == 3) { // Teachers
            $query = "SELECT users.*, teacher.teacher_id, teacher.teacher_name, teacher.teacher_email, 
                             teacher.teacher_salary, teacher.teacher_qualification, teacher.teacher_address,
                             teacher.teacher_exprience, teacher.teacher_join_date, master_role.Role 
                      FROM users
                      LEFT JOIN teacher ON users.user_phone = teacher.teacher_phone
                      INNER JOIN master_role ON users.role_id = master_role.role_id
                      WHERE users.user_id = ?";
        } elseif ($role == 4) { // Students
            $query = "SELECT users.*, student.student_id, student.student_name, student.student_email, 
                             student.student_gender, student.student_fees, student.batch_id, 
                             student.student_address, student.student_joining_date, student.student_course, 
                             master_role.Role 
                      FROM users
                      LEFT JOIN student ON users.user_phone = student.student_phone
                      INNER JOIN master_role ON users.role_id = master_role.role_id
                      WHERE users.user_id = ?";
        } else {
            echo "<p class='text-danger'>Invalid role ID!</p>";
            exit;
        }
    } elseif ($source === 'teacher') {
        $query = "SELECT * FROM teacher WHERE teacher_id = ?";
    } elseif ($source === 'student') {
        $query = "SELECT * FROM student WHERE student_id = ?";
    } else {
        echo "<p class='text-danger'>Invalid source type!</p>";
        exit;
    }

    // Prepare and execute statement
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "<p class='text-danger'>SQL Error: " . $conn->error . "</p>";
        exit;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($userData = $result->fetch_assoc()) {
        // Display common user details
        echo "<p><strong>Name:</strong> " . htmlspecialchars($userData['user_name'] ?? $userData['teacher_name'] ?? $userData['student_name'] ?? 'N/A') . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($userData['user_email'] ?? $userData['teacher_email'] ?? $userData['student_email'] ?? 'N/A') . "</p>";
        echo "<p><strong>Phone:</strong> " . htmlspecialchars($userData['user_phone'] ?? $userData['teacher_phone'] ?? $userData['student_phone'] ?? 'N/A') . "</p>";

        $status = ($userData['status'] ?? $userData['teacher_status'] ?? $userData['student_status'] ?? null) == 0 ? 'Active' : 'Inactive';
        echo "<p><strong>Status:</strong> " . htmlspecialchars($status) . "</p>";

        // Additional details for teachers
        if ($role == 3 || $source === 'teacher') {
            echo "<p><strong>Experience:</strong> " . htmlspecialchars($userData['teacher_exprience'] ?? 'N/A') . " years</p>";
            echo "<p><strong>Salary:</strong> " . htmlspecialchars($userData['teacher_salary'] ?? 'N/A') . "</p>";
            echo "<p><strong>Qualification:</strong> " . htmlspecialchars($userData['teacher_qualification'] ?? 'N/A') . "</p>";
            echo "<p><strong>Joining Date:</strong> " . htmlspecialchars($userData['teacher_join_date'] ?? 'N/A') . "</p>";
            echo "<p><strong>Address:</strong> " . htmlspecialchars($userData['teacher_address'] ?? 'N/A') . "</p>";
        }

        // Additional details for students
        if ($role == 4 || $source === 'student') {
            echo "<p><strong>Gender:</strong> " . htmlspecialchars($userData['student_gender'] ?? 'N/A') . "</p>";
            echo "<p><strong>Fees:</strong> " . htmlspecialchars($userData['student_fees'] ?? 'N/A') . "</p>";
            echo "<p><strong>Batch:</strong> " . htmlspecialchars($userData['batch_id'] ?? 'N/A') . "</p>";
            echo "<p><strong>Address:</strong> " . htmlspecialchars($userData['student_address'] ?? 'N/A') . "</p>";
            echo "<p><strong>Joining Date:</strong> " . htmlspecialchars($userData['student_joining_date'] ?? 'N/A') . "</p>";
            echo "<p><strong>Section:</strong> " . htmlspecialchars($userData['student_course'] ?? 'N/A') . "</p>";
        }
    } else {
        echo "<p class='text-danger'>No user details found for user_id = $id</p>";
    }
} else {
    echo "<p class='text-danger'>Invalid request!</p>";
}
?>
