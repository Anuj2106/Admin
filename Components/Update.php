<?php
include "../Components/connect.php";
header("Content-Type: application/json");

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Debugging (Check received data)
file_put_contents("debug.log", json_encode($data) . PHP_EOL, FILE_APPEND);

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
                $studentQuery = $conn->query("SELECT * FROM student WHERE student_phone = '$user_phone'");
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

        // ✅ Update `users` Table
        $updateUser = "UPDATE users SET 
            user_name='$user_name', 
            user_email='$user_email', 
            user_phone='$user_phone', 
            user_pass='$user_pass', 
            status='$status' 
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
                    teacher_address='$teacher_address' 
                    WHERE teacher_email='$user_email'";

                $conn->query($updateTeacher);
            } elseif ($role_id == 4) { // Student Update
                $student_batch = (int)$data['batch_id'];
                $student_course = $conn->real_escape_string($data['student_course']);
                $student_fees = (float)$data['student_fees'];
                $student_gender = $conn->real_escape_string($data['student_gender']);
                $student_join_date = $conn->real_escape_string($data['student_joining_date']);
                $student_address = $conn->real_escape_string($data['student_address']);

                $updateStudent = "UPDATE student SET 
                    student_name='$user_name', 
                    student_email='$user_email', 
                    student_phone='$user_phone', 
                    student_course='$student_course', 
                    batch_id='$student_batch', 
                    student_status='$status', 
                    student_fees='$student_fees', 
                    student_address='$student_address', 
                    student_gender='$student_gender', 
                    student_joining_date='$student_join_date' 
                    WHERE student_email='$user_email'";

                $conn->query($updateStudent);
            }

            echo json_encode(["success" => "User updated successfully"]);
        } else {
            echo json_encode(["error" => "User update failed", "sql_error" => $conn->error]);
        }
        exit;
    }

    // ✅ Fetch Course Details by course_id
    elseif (isset($data['fetch']) && isset($data['course_id'])) {
        $course_id = (int)$data['course_id'];

        $query = $conn->query("SELECT * FROM course WHERE course_id = '$course_id'");

        if ($query->num_rows > 0) {
            $course = $query->fetch_assoc();
            echo json_encode($course);
        } else {
            echo json_encode(["error" => "Course not found"]);
        }
        exit;
    }

    // ✅ Update Course Details
    elseif (isset($data['update'], $data['course_id'])) {
        file_put_contents("debug.log", "course_id received: " . json_encode($data['course_id']) . PHP_EOL, FILE_APPEND);
        $course_id = (int)$data['course_id'];
        file_put_contents("debug.log", "course_id after cast: " . json_encode($course_id) . PHP_EOL, FILE_APPEND);
        $course_name = $conn->real_escape_string($data['course_name']);
        $course_fees = (float)$data['course_fees'];
        $course_time = $conn->real_escape_string($data['course_time']);
        $course_gst = (float)$data['course_gst'];

        // Calculate GST and total fees
        $course_total_gst = ($course_fees * $course_gst) / 100;
        $course_total_fee = $course_fees + $course_total_gst;

        // Update SQL query
        $updateCourse = "UPDATE course SET 
            course_name = '$course_name', 
            course_fees = '$course_fees', 
            course_fees_gst = '$course_gst', 
            course_total_fees = '$course_total_fee', 
            course_time = '$course_time' 
            WHERE course_id = '$course_id'";

        if ($conn->query($updateCourse)) {
            echo json_encode(["success" => "Course updated successfully"]);
        } else {
            echo json_encode(["error" => "Course update failed", "sql_error" => $conn->error, "sql"=>$updateCourse]);
        }
        exit;
    }

    // ❌ If no valid request is found
    else {
        echo json_encode(["error" => "Invalid request"]);
    }
}
?>