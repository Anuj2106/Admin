<?php
include "../Components/connect.php";
header("Content-Type: application/json");

$data = $_POST ?: json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["error" => "Invalid JSON input"]);
    exit;
}

// Function to delete old profile image
function deleteOldProfileImage($oldImagePath) {
    if (!empty($oldImagePath) && file_exists($oldImagePath)) {
        unlink($oldImagePath);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ✅ Fetch User Details Based on Role
    if (isset($data['fetch'], $data['phone'], $data['role'])) {
        $user_phone = $conn->real_escape_string($data['phone']);
        $role_id = (int)$data['role'];

        $query = $conn->query("SELECT * FROM users WHERE user_phone = '$user_phone'");

        if ($query->num_rows > 0) {
            $user = $query->fetch_assoc();

            if ($role_id == 3) { // Teacher
                $teacherQuery = $conn->query("SELECT * FROM teacher WHERE teacher_phone = '$user_phone'");
                if ($teacherQuery->num_rows > 0) {
                    $user = array_merge($user, $teacherQuery->fetch_assoc());
                }
            } elseif ($role_id == 4) { // Student
                $studentQuery = $conn->query("SELECT student.*, course.course_name 
                    FROM student 
                    LEFT JOIN course ON student.course_id = course.course_id 
                    WHERE student_phone = '$user_phone'");
                if ($studentQuery->num_rows > 0) {
                    $user = array_merge($user, $studentQuery->fetch_assoc());
                }
            }

            echo json_encode($user);
        } else {
            echo json_encode(["error" => "User not found"]);
        }
        exit;
    }

    // ✅ Update User Details Based on Role
    elseif (isset($data['update'], $data['user_id'], $data['role_id'])) {
        $user_id = (int)$data['user_id'];
        $role_id = (int)$data['role_id'];

        $user_name = $conn->real_escape_string($data['user_name']);
        $user_email = $conn->real_escape_string($data['user_email']);
        $user_phone = $conn->real_escape_string($data['user_phone']);
        $user_pass = $conn->real_escape_string($data['user_pass']);
        $status = (int)$data['status'];

        // ✅ Fetch Old Image Before Updating
        $oldImagePath = "";
        $getImageQuery = "SELECT image FROM users WHERE user_id = '$user_id'";
        $getImageResult = $conn->query($getImageQuery);

        if ($getImageResult->num_rows > 0) {
            $row = $getImageResult->fetch_assoc();
            $oldImagePath = $row['image'] ?? "";
        }

        // ✅ Image Upload Handling
        $upload_dir = "../uploads/";
        $imagePath = $oldImagePath; // Default to old image if no new file is uploaded

        if (isset($_FILES['user_image']) && !empty($_FILES['user_image']['name'])) {
            $image_name = basename($_FILES["user_image"]["name"]);
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $new_image_name = "user_" . time() . "_" . $user_id . "." . $image_ext;
            $target_file = $upload_dir . $new_image_name;

            if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $target_file)) {
                // ✅ Delete old image if it exists
                deleteOldProfileImage($oldImagePath);
                $imagePath = $target_file; // Store new image path
            }
        }

        // ✅ Update `users` Table
        $updateUser = "UPDATE users SET 
            user_name='$user_name', 
            user_email='$user_email', 
            user_phone='$user_phone', 
            user_pass='$user_pass', 
            status='$status',
            image='$imagePath'
            WHERE user_id='$user_id'";

        if ($conn->query($updateUser)) {
            if ($role_id == 3) { // Teacher Update
                $teacher_salary = (float)$data['teacher_salary'];
                $teacher_qualification = $conn->real_escape_string($data['teacher_qualification']);
                $teacher_experience = $conn->real_escape_string($data['teacher_experience']);
                $teacher_join_date = $conn->real_escape_string($data['teacher_join_date']);
                $teacher_address = $conn->real_escape_string($data['teacher_address']);

                $updateTeacher = "UPDATE teacher SET 
                teacher_name='$user_name', 
                teacher_email='$user_email', 
                teacher_phone='$user_phone', 
                teacher_salary='$teacher_salary', 
                teacher_qualification='$teacher_qualification', 
                teacher_status='$status', 
                teacher_experience='$teacher_experience', 
                teacher_join_date='$teacher_join_date', 
                teacher_address='$teacher_address', 
                teacher_image='$imagePath' 
                WHERE teacher_email='$user_email'";
            

                $conn->query($updateTeacher);
            } elseif ($role_id == 4) { // Student Update
                $student_batch = (int)$data['batch_id'];
                $course_id = $conn->real_escape_string($data['course_id']);
                $student_fees = (float)$data['student_fees'];
                $student_gender = $conn->real_escape_string($data['student_gender']);
                $student_join_date = $conn->real_escape_string($data['student_joining_date']);
                $student_address = $conn->real_escape_string($data['student_address']);

                $updateStudent = "UPDATE student SET 
                student_name='$user_name', 
                student_email='$user_email', 
                student_phone='$user_phone', 
                course_id='$course_id', 
                batch_id='$student_batch', 
                student_status='$status', 
                student_fees='$student_fees', 
                student_address='$student_address', 
                student_gender='$student_gender', 
                student_joining_date='$student_join_date', 
                student_image='$imagePath' 
                WHERE student_email='$user_email'";
            

                $conn->query($updateStudent);
            }

            echo json_encode(["success" => "User updated successfully", "image" => $imagePath]);
        } else {
            echo json_encode(["error" => "User update failed", "sql_error" => $conn->error]);
        }
        exit;
    }
}
