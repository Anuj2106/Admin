<?php
include "../Components/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_phone = $_POST['user_phone'];
    $user_pass = $_POST['user_pass'];
    $role_id = 4; // Automatically set role_id to 4 for students
    $status = $_POST['status'];
    //  Student Data Begin
    $student_batch = $_POST['batch_id'];
    $student_course = $_POST['course_id'];
    $student_fees = $_POST['student_fees'];
    $student_gender = $_POST['student_gender'];
    $student_join_date = $_POST['student_joining_date'];  
    $student_address = $_POST['student_address'];

    // Student Data Begin
    $adduser = "INSERT INTO users (user_name, user_email, user_pass, user_phone, role_id, status)
                VALUES ('$user_name', '$user_email', '$user_pass', '$user_phone', '$role_id', '$status')";
    $sql = "INSERT INTO student (student_name, student_phone,student_email, course_id, batch_id, student_status, student_fees, student_address, student_gender, student_joining_date)
            VALUES ('$user_name', '$user_phone', '$user_email', '$student_course', '$student_batch', '$status', '$student_fees', '$student_address', '$student_gender', '$student_join_date')";
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