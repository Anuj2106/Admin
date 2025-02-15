<?php
include "../Components/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $batch_name = $_POST['batch_name'];

    $sql = "INSERT INTO batch (batch_name) VALUES ('$batch_name')";
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