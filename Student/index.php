<?php
session_start();
if (!isset($_SESSION["Login_in"])) {
    header("Location:/education/Admin/Login.php");
    exit();
}

include "../Components/connect.php";    
include "../Components/header.php";
include "../Components/Topbar.php";
include "../Components/sidebar.php";

$sql = "SELECT student.*, course.course_name , batch.batch_name FROM student
 INNER JOIN course ON student.course_id = course.course_id
  INNER JOIN batch ON student.batch_id = batch.batch_id
";
$result = $conn->query($sql);

$role_id = $_SESSION["role_id"]; // Get role_id from session
?>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bolder">Student Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="/education/Admin/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Students</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bolder mb-0">Student List</h5>
                            <input type="text" id="searchInput" class="form-control w-25" onkeyup="searchTable()" placeholder="Search Name...">
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Course</th>
                                        <th>Batch</th>
                                        <th>Fees</th>
                                        <th>Address</th>
                                        <th>Gender</th>
                                        <th>Joining Date</th>
                                        <th>Status</th>
                                        <?php if ($role_id != 3) { ?> <th>Actions</th> <?php } ?>
                                    </tr>
                                </thead>
                                <tbody id="TableBody">
                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $row['student_id']; ?></td>
                                            <td><?= htmlspecialchars($row['student_name']); ?></td>
                                            <td><?= htmlspecialchars($row['student_email']); ?></td>
                                            <td><?= htmlspecialchars($row['student_phone']); ?></td>
                                            <td><?= htmlspecialchars($row['course_name']); ?></td>
                                            <td><?= htmlspecialchars($row['batch_name']); ?></td>
                                            <td><?= htmlspecialchars($row['student_fees']); ?></td>
                                            <td><?= htmlspecialchars($row['student_address']); ?></td>
                                            <td><?= htmlspecialchars($row['student_gender']); ?></td>
                                            <td><?= date("d-m-y", strtotime($row['student_joining_date'])); ?></td>

                                            <td>
                                                <?php 
                                                if ($role_id == 3) { 
                                                    echo $row['student_status'] == 0 ? 'Active' : 'Inactive'; 
                                                } else { ?>
                                                    <button class="btn btn-sm toggle-status <?= $row['student_status'] == 0 ? 'btn-success' : 'btn-danger'; ?>" 
                                                        data-phone="<?= $row['student_phone']; ?>"
                                                        data-role="4"
                                                        data-status="<?= $row['student_status']; ?>"
                                                    >
                                                        <p class="mb-0"><?= $row['student_status'] == 0 ? 'Active' : 'Inactive'; ?></p>
                                                    </button>
                                                <?php } ?>
                                            </td>
                                            <?php if ($role_id != 3) { ?>
                                            <td>
                                            <button class="btn btn-sm  btn-primary edit-btn" data-phone="<?php echo  intval($row['student_phone']) ?>" data-role="4" >
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger delete-btn" data-phone="<?= intval($row['student_phone']) ?>" data-role="4">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info view-btn" data-id="<?= intval($row['student_id']) ?>" data-role="4" data-source="student">
                                                    <i class="bi bi-eye-fill"></i>
                                                </button>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <span>Showing <?= $result->num_rows; ?> students</span>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include "../Components/footer.php";
include"../Components/modal.php"
?>
