<?php
include "../Components/connect.php";

$response = []; // Initialize the response array

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
    // Capture form data
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_phone = $_POST['user_phone'];
    $user_pass = $_POST['user_pass'];
    $role_id = $_POST['role_id'];
    $status = $_POST['status'];

    // Insert user into database first to get user_id
    $adduser = "INSERT INTO users (user_name, user_email, user_pass, user_phone, role_id, status, image)
                VALUES ('$user_name', '$user_email', '$user_pass', '$user_phone', '$role_id', '$status', '')";

    if ($conn->query($adduser) === TRUE) {
        $user_id = $conn->insert_id; // Get the last inserted user ID

        // Handle image upload with new user_id
        $imagePath = uploadImage($_FILES["user_image"], $user_id);

        if (!$imagePath) {
            $response['status'] = 'error';
            $response['message'] = "Error uploading the image.";
            echo json_encode($response);
            exit();
        }

        // Update the user record with the new image path
        $updateImage = "UPDATE users SET image = '$imagePath' WHERE id = $user_id";
        $conn->query($updateImage);

        // Handle role-specific insertions
        if ($role_id == 3) { // Teacher
            $teacher_salary = !empty($_POST['teacher_salary']) ? $_POST['teacher_salary'] : null;
            $teacher_qualification = !empty($_POST['teacher_qualification']) ? $_POST['teacher_qualification'] : null;
            $teacher_experience = !empty($_POST['teacher_experience']) ? $_POST['teacher_experience'] : null;
            $teacher_join_date = !empty($_POST['teacher_join_date']) ? $_POST['teacher_join_date'] : null;
            $teacher_address = !empty($_POST['teacher_address']) ? $_POST['teacher_address'] : null;

            $sql = "INSERT INTO teacher (teacher_name, teacher_email, teacher_phone, teacher_salary, teacher_qualification, teacher_status, teacher_experience, teacher_join_date, teacher_address, teacher_image)
                    VALUES ('$user_name', '$user_email', '$user_phone', '$teacher_salary', '$teacher_qualification', '$status', '$teacher_experience', '$teacher_join_date', '$teacher_address', '$imagePath')";
        } elseif ($role_id == 4) { // Student
            $student_course = !empty($_POST['student_course']) ? $_POST['student_course'] : null;
            $student_batch = !empty($_POST['batch_id']) ? $_POST['batch_id'] : null;
            $student_fees = !empty($_POST['student_fees']) ? $_POST['student_fees'] : null;
            $student_address = !empty($_POST['student_address']) ? $_POST['student_address'] : null;
            $student_gender = !empty($_POST['student_gender']) ? $_POST['student_gender'] : null;
            $student_join_date = !empty($_POST['student_joining_date']) ? $_POST['student_joining_date'] : null;

            $sql = "INSERT INTO student (student_name, student_phone, student_email, student_course, batch_id, student_status, student_fees, student_address, student_gender, student_join_date, student_image)
                    VALUES ('$user_name', '$user_phone', '$user_email', '$student_course', '$student_batch', '$status', '$student_fees', '$student_address', '$student_gender', '$student_join_date', '$imagePath')";
        } elseif ($role_id == 1 || $role_id == 2) {
            // No extra insertions for Admin and Super Admin, only the users table entry is needed
            $response['status'] = 'success';
            $response['message'] = 'Admin/Super Admin added successfully';
            echo json_encode($response);
            exit();
        }

        // Execute role-specific query and handle errors
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
