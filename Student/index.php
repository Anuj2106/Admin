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

$sql = "SELECT * FROM student";
$result = $conn->query($sql);
?>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Student Management</h3>
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
                            <h5 class="card-title mb-0">Student List</h5>
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
                                        <th>Fees</th>
                                        <th>Address</th>
                                        <th>Gender</th>
                                        <th>Joining Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="TableBody">
                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $row['student_id']; ?></td>
                                            <td><?= htmlspecialchars($row['student_name']); ?></td>
                                            <td><?= htmlspecialchars($row['student_email']); ?></td>
                                            <td><?= htmlspecialchars($row['student_phone']); ?></td>
                                            <td><?= htmlspecialchars($row['student_course']); ?></td>
                                            <td><?= htmlspecialchars($row['student_fees']); ?></td>
                                            <td><?= htmlspecialchars($row['student_address']); ?></td>
                                            <td><?= htmlspecialchars($row['student_gender']); ?></td>
                                            <td><?= htmlspecialchars($row['student_joining_date']); ?></td>
                                            <td>
                                                <button class="btn btn-sm toggle-status <?= $row['student_status'] == 0 ? 'btn-success' : 'btn-danger'; ?>" data-phone="<?= $row['student_phone']; ?>"
                                                    data-role="4"
                                                    data-status="<?= $row['student_status']; ?>"
                                                >
                                                <p class="mb-0">

                                                    <?= $row['student_status'] == 0 ? 'Active' : 'Inactive'; ?>
                                                </p>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary btn-rounded">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger delete-btn" data-phone="<?= intval($row['student_phone']) ?>" data-role="4" >
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info view-btn" data-id="<?= intval($row['student_id']) ?>" data-role="4" data-source="student">
                                                    <i class="bi bi-eye-fill"></i>
                                                </button>
                                            </td>
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

<?php
include "../Components/PopUp.php"; 
include "../Components/footer.php";

?>
