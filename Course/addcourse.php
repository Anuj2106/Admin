<?php
include "../Components/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course_name'];
    $course_fees = $_POST['course_fees'];
    $course_time = $_POST['course_time'];
    $course_gst = $_POST['course_gst'];

    // Calculate GST and total fees
    $course_total_gst = ($course_fees * $course_gst) / 100;
    $course_total_fee = $course_fees + $course_total_gst;

    // Prepare SQL query
    $sql = "INSERT INTO course (course_name, course_fees, course_fees_gst, course_total_fees, course_time) 
            VALUES ('$course_name', '$course_fees', '$course_gst', '$course_total_fee', '$course_time')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Course added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
