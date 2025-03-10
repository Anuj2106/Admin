<?php
include "../Components/connect.php";

$response = []; // Initialize the response array

// Function to generate a unique alphanumeric UID based on name
function generateUID($conn, $role, $name) {
    $shortName = strtoupper(substr($name, 0, 4)); // Get first 4 characters of the name
    
    // Determine the table name based on role
    $table = ($role == 3) ? "teacher" : "student";
    $column = ($role == 3) ? "teacher_uid" : "student_uid";
    
    // Get the latest UID count
    $query = "SELECT $column FROM $table ORDER BY id DESC LIMIT 1";
    $result = $conn->query($query);
    
    $count = 1;
    if ($result && $row = $result->fetch_assoc()) {
        $lastUID = $row[$column];
        preg_match('/(\d+)$/', $lastUID, $matches);
        if (!empty($matches[1])) {
            $count = intval($matches[1]) + 1;
        }
    }
    
    return $shortName . str_pad($count, 3, "0", STR_PAD_LEFT); // Format: NAME001
}

// Helper function to handle image upload
function uploadImage($file, $user_id) {
    $targetDir = "../uploads/";
    $image_name = basename($file["name"]);
    $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];

    if (!empty($file["name"]) && in_array($image_ext, $allowedTypes)) {
        $new_image_name = "user_" . time() . "_" . $user_id . "." . $image_ext;
        $targetFilePath = $targetDir . $new_image_name;

        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            return $targetFilePath; // Return the full image path
        } else {
            return false;
        }
    }
    return "../uploads/default.png"; // Default image if no file uploaded
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_phone = $_POST['user_phone'];
    $user_pass = $_POST['user_pass'];
    $role_id = $_POST['role_id'];
    $status = $_POST['status'];

    $targetFilePath = isset($_FILES["user_image"]) ? uploadImage($_FILES["user_image"], time()) : "../uploads/default.png";

    $adduser = "INSERT INTO users (user_name, user_email, user_pass, user_phone, role_id, status, image)
                VALUES ('$user_name', '$user_email', '$user_pass', '$user_phone', '$role_id', '$status', '$targetFilePath')";

    if ($conn->query($adduser) === TRUE) {
        $user_id = $conn->insert_id;

        if ($role_id == 3) { // Teacher
            $uid = generateUID($conn, 3, $user_name);
            $teacher_salary = $_POST['teacher_salary'] ?? null;
            $teacher_qualification = $_POST['teacher_qualification'] ?? null;
            $teacher_experience = $_POST['teacher_experience'] ?? null;
            $teacher_join_date = $_POST['teacher_join_date'] ?? null;
            $teacher_address = $_POST['teacher_address'] ?? null;

            $sql = "INSERT INTO teacher (teacher_uid, teacher_name, teacher_email, teacher_phone, teacher_salary, teacher_qualification, teacher_status, teacher_experience, teacher_join_date, teacher_address, teacher_image)
                    VALUES ('$uid', '$user_name', '$user_email', '$user_phone', '$teacher_salary', '$teacher_qualification', '$status', '$teacher_experience', '$teacher_join_date', '$teacher_address', '$targetFilePath')";
        } elseif ($role_id == 4) { // Student
            $uid = generateUID($conn, 4, $user_name);
            $student_course = $_POST['course_id'] ?? null;
            $student_batch = $_POST['batch_id'] ?? null;
            $student_fees = $_POST['student_fees'] ?? null;
            $student_address = $_POST['student_address'] ?? null;
            $student_gender = $_POST['student_gender'] ?? null;
            $student_join_date = $_POST['student_joining_date'] ?? null;

            $sql = "INSERT INTO student (student_uid, student_name, student_phone, student_email, course_id, batch_id, student_status, student_fees, student_address, student_gender, student_joining_date, student_image)
                    VALUES ('$uid', '$user_name', '$user_phone', '$user_email', '$student_course', '$student_batch', '$status', '$student_fees', '$student_address', '$student_gender', '$student_join_date', '$targetFilePath')";
        } elseif ($role_id == 1 || $role_id == 2) {
            $response['status'] = 'success';
            $response['message'] = 'User added successfully';
            echo json_encode($response);
            exit();
        }

        if (isset($sql) && $conn->query($sql) === TRUE) {
            $response['status'] = 'success';
            $response['message'] = 'User added successfully';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error executing role-specific query: ' . $conn->error;
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . $conn->error;
    }

    $conn->close();
    echo json_encode($response);
}
?>
