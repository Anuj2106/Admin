<?php
include "../Components/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id']; // New
    $batch_id = $_POST['batch_id']; // New
    $date = $_POST['date'];
    $status = $_POST['status'];

    // Check if attendance exists
    $check_query = "SELECT * FROM student_attendance WHERE student_id = '$student_id' AND attendance_date = '$date'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        // Update existing record
        $update_query = "UPDATE student_attendance SET attendance_status = '$status', course_id = '$course_id', batch_id = '$batch_id' WHERE student_id = '$student_id' AND attendance_date = '$date'";
        if ($conn->query($update_query)) {
            echo "Attendance updated!";
        } else {
            echo "Error updating attendance!";
        }
    } else {
        // Insert new record
        $insert_query = "INSERT INTO student_attendance (student_id, course_id, batch_id, attendance_date, attendance_status) 
                         VALUES ('$student_id', '$course_id', '$batch_id', '$date', '$status')";
        if ($conn->query($insert_query)) {
            echo "Attendance recorded!";
        } else {
            echo "Error saving attendance!";
        }
    }
}

$conn->close();
?>
