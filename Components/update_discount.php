<?php
include '../Components/connect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $course_id = $_POST['course_id'];
    $action = $_POST['action'];

    if ($action == "apply") {
        $discount_percentage = $_POST['discount_percentage'];

        // Get current course total fees
        $query = "SELECT course_total_fees FROM course WHERE course_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $course = $result->fetch_assoc();

        if ($course) {
            $original_fees = $course['course_total_fees'];
            $discount_amount = ($discount_percentage / 100) * $original_fees;
            $new_fees = $original_fees - $discount_amount;

            // Update the database
            $updateQuery = "UPDATE course SET course_total_fees = ?, course_dis = 1, course_discount = ? WHERE course_id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("dii", $new_fees, $discount_percentage, $course_id);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "new_fees" => $new_fees, "discount" => $discount_percentage]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to apply discount"]);
            }
        }
    } elseif ($action == "remove") {
        // Get current discount
        $query = "SELECT course_total_fees, course_discount FROM course WHERE course_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $course = $result->fetch_assoc();

        if ($course) {
            $original_fees = $course['course_total_fees'];
            $discount_amount = $course['course_discount'];

            // Revert the discount
            $new_fees = $original_fees / (1- ($discount_amount / 100));

            // Update the database
            $updateQuery = "UPDATE course SET course_total_fees = ?, course_dis = 0, course_discount = 0 WHERE course_id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("di", $new_fees, $course_id);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "new_fees" => $new_fees, "discount" => 0]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to remove discount"]);
            }
        }
    }
}
?>
