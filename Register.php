<?php
include "./Components/connect.php";

// Handle AJAX request to fetch course fees
if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    $stmt = $conn->prepare("SELECT course_total_fees FROM course WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $stmt->bind_result($fee);
    $stmt->fetch();
    $stmt->close();

    echo $fee; // Return only the fee amount
    exit; // Prevent HTML from loading for AJAX requests
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edulear - Admission</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .modal-body i {
            font-size: 60px;
        }
        .success-icon {
            color: green;
            animation: zoomIn 0.5s ease-in-out;
        }
        .error-icon {
            color: red;
            animation: shake 0.5s ease-in-out;
        }
        @keyframes zoomIn {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            50% { transform: translateX(10px); }
            75% { transform: translateX(-5px); }
        }
    </style>
</head>
<body class="register-page bg-light">
    <div class="container mt-5">
        <div class="register-box mx-auto" style="max-width: 600px;">
            <div class="card shadow">
                <div class="card-header text-center bg-primary text-white">
                    <h2>Admission Form</h2>
                </div>
                <div class="card-body">
                    <form id="registerForm" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input id="firstName" name="firstName" type="text" class="form-control" placeholder="First Name" required />
                            </div>
                            <div class="col-md-6">
                                <input id="lastName" name="lastName" type="text" class="form-control" placeholder="Last Name" required />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input id="fatherName" name="fatherName" type="text" class="form-control" placeholder="Father's Name" required />
                            </div>
                            <div class="col-md-6">
                                <input id="motherName" name="motherName" type="text" class="form-control" placeholder="Mother's Name" required />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input id="occupation" name="occupation" type="text" class="form-control" placeholder="Occupation" required />
                            </div>
                            <div class="col-md-6">
                                <input id="qualification" name="qualification" type="text" class="form-control" placeholder="Qualification" required />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input id="email" name="email" type="email" class="form-control" placeholder="Email" required />
                            </div>
                            <div class="col-md-6">
                                <input id="password" name="password" type="password" class="form-control" placeholder="Password" required />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input id="phone" name="phone" type="tel" class="form-control" placeholder="Phone Number" required />
                            </div>
                            <div class="col-md-6">
                                <input id="photo" name="photo" type="file" class="form-control" required />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <select id="course" name="course" class="form-select" required>
                                    <option value="" disabled selected>Select Course</option>
                                    <?php
                                    $sql = "SELECT * FROM course";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($row['course_id']) . '">' . htmlspecialchars($row['course_name']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input id="fees" name="fees" type="text" class="form-control" readonly placeholder="Fees" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </div>
                    </form>

                    <p class="text-center">
                        <a href="login.php" class="text-decoration-none">Already registered? Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="responseModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <i id="responseIcon"></i>
                    <h4 id="responseMessage"></h4>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Fetch Course Fees
            $('#course').change(function () {
                var courseId = $(this).val();
                if (courseId) {
                    $.ajax({
                        type: 'POST',
                        url: '',
                        data: { course_id: courseId },
                        success: function (response) {
                            $('#fees').val(response.trim());
                        }
                    });
                } else {
                    $('#fees').val('');
                }
            });

            // Submit Form via AJAX
            $('#registerForm').submit(function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append("register", true);

                $.ajax({
                    type: "POST",
                    url: "register.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        var data = JSON.parse(response);
                        $("#responseIcon").attr("class", data.status === "success" ? "bi bi-check-circle-fill success-icon" : "bi bi-x-circle-fill error-icon");
                        $("#responseMessage").text(data.status === "success" ? "Registration Successful!" : "Registration Failed!");
                        $("#responseModal").modal("show");
                    }
                });
            });
        });
    </script>
</body>
</html>
