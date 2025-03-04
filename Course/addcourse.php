<?php
include "../Components/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course_name'];
    $course_fees = $_POST['course_fees'];
    $course_time = $_POST['course_time'];
   
    
  

    $sql = "INSERT INTO course (course_name,course_fees,course_time) VALUES ('$course_name', '$course_fees', '$course_time')";
    if ($conn->query($sql) === TRUE) {
        // Redirect to the user page after successful insertion
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>