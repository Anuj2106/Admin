<?php
include "../Components/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_phone = $_POST['user_phone'];
    $user_pass = $_POST['user_pass'];
    $role_id = $_POST['role_id'];
    $status = $_POST['status'];
    //  Teacher Data Begin
    $teacher_salary = $_POST['teacher_salary'];
    $teacher_qualification = $_POST['teacher_qualification'];   
    $teacher_experience = $_POST['teacher_exprience'];
    $teacher_join_date = $_POST['teacher_join_date'];
    $teacher_address = $_POST['teacher_address'];
    //  Teacher Data End
    //  Student Data Begin
    $student_batch = $_POST['batch_id'];
    $student_course = $_POST['student_course'];
    $student_fees = $_POST['student_fees'];
    $student_gender = $_POST['student_gender'];
    $student_join_date = $_POST['student_joining_date'];  
    $student_address = $_POST['student_address'];

    // Admin Data Begin
    if ($role_id == 2) {
        $adduser = "INSERT INTO users (user_name, user_email, user_pass, user_phone, role_id, status)
                    VALUES ('$user_name', '$user_email', '$user_pass', '$user_phone', '$role_id', '$status')";
        $sql = "INSERT INTO teacher (teacher_name, teacher_email, teacher_phone, teacher_salary, teacher_qualification, teacher_status, teacher_exprience, teacher_join_date, teacher_address)
                VALUES ('$user_name', '$user_email', '$user_phone', '$teacher_salary', '$teacher_qualification', '$status', '$teacher_experience', '$teacher_join_date', '$teacher_address')";
    }
    // Admin Data End
    // Teacher Data Begin
    else if ($role_id == 3) {
        $adduser = "INSERT INTO users (user_name, user_email, user_pass, user_phone, role_id, status)
                    VALUES ('$user_name', '$user_email', '$user_pass', '$user_phone', '$role_id', '$status')";
        $sql = "INSERT INTO teacher (teacher_name, teacher_email, teacher_phone, teacher_salary, teacher_qualification, teacher_status, teacher_exprience, teacher_join_date, teacher_address)
                VALUES ('$user_name', '$user_email', '$user_phone', '$teacher_salary', '$teacher_qualification', '$status', '$teacher_experience', '$teacher_join_date', '$teacher_address')";
    }
    // Teacher Data End
    // Student Data Begin
    else if ($role_id == 4) {
        $adduser = "INSERT INTO users (user_name, user_email, user_pass, user_phone, role_id, status)
                    VALUES ('$user_name', '$user_email', '$user_pass', '$user_phone', '$role_id', '$status')";
        $sql = "INSERT INTO student (student_name, student_phone, student_email, student_course, batch_id, student_status, student_fees, student_address, student_gender, student_joining_date)
                VALUES ('$user_name', '$user_phone', '$user_email', '$student_course', '$student_batch', '$status', '$student_fees', '$student_address', '$student_gender', '$student_join_date')";
    }
    // Student Data End

    if ($conn->query($adduser) === TRUE && $conn->query($sql) === TRUE) {
        // Redirect to the user page after successful insertion
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
<?php

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'];
$status = $data['status'];

$sql = "UPDATE users SET status = ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $status, $user_id);

$response = [];
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>