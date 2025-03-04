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

// Set how many records per page
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $records_per_page;
$total_students_query = "SELECT COUNT(*) AS total FROM student_attendance";
$total_students_result = $conn->query($total_students_query);
$total_students_row = $total_students_result->fetch_assoc();
$total_students = $total_students_row['total'];
$total_pages = ceil($total_students / $records_per_page);

$sql = "SELECT * FROM student_attendance LIMIT $records_per_page OFFSET $offset";
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
                <div class="col-12 mb-3">
                    <input type="text" id="searchInput" class="form-control w-25 float-end" onkeyup="searchTable()" placeholder="Search Name...">
                </div>
                <div class="col-12">
                    <div class="card">
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
                                    <?php $counter = $offset + 1; while ($row = $result->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $counter++; ?></td>
                                            <td><?= htmlspecialchars($row['student_name']); ?></td>
                                            <td><?= htmlspecialchars($row['student_email']); ?></td>
                                            <td><?= htmlspecialchars($row['student_phone']); ?></td>
                                            <td><?= htmlspecialchars($row['student_course']); ?></td>
                                            <td><?= htmlspecialchars($row['student_fees']); ?></td>
                                            <td><?= htmlspecialchars($row['student_address']); ?></td>
                                            <td><?= htmlspecialchars($row['student_gender']); ?></td>
                                            <td><?= htmlspecialchars($row['student_join_date']); ?></td>
                                            <td>
                                                <button class="btn btn-sm toggle-status <?= $row['student_status'] == 0 ? 'btn-success' : 'btn-danger'; ?>" data-id="<?= $row['student_id']; ?>">
                                                    <?= $row['student_status'] == 0 ? 'Active' : 'Inactive'; ?>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary btn-rounded">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger delete-btn" data-id="<?= intval($row['student_id']) ?>" data-role="2" data-sourse="student">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info view-btn" data-id="<?= intval($row['student_id']) ?>" data-role="2" data-sourse="student">
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
                                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                    <a class="page-link" href="?page=<?= ($page - 1); ?>">&laquo;</a>
                                </li>
                                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                    <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                                        <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                                    <a class="page-link" href="?page=<?= ($page + 1); ?>">&raquo;</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include "../Components/footer.php"; ?>
