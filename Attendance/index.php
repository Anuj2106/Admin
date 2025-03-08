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

$currentMonth = date('m');
$currentYear = date('Y');
$totalDays = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);
$offset = ($page - 1) * $records_per_page;

$total_students_query = "SELECT COUNT(*) AS total FROM student";
$total_students_result = $conn->query($total_students_query);
$total_students = $total_students_result->fetch_assoc()['total'];
$total_pages = ceil($total_students / $records_per_page);

$sql = "SELECT student.student_id, student.student_name, batch.batch_name, course.course_name 
        FROM student
        INNER JOIN batch ON student.batch_id = batch.batch_id
        INNER JOIN course ON student.course_id = course.course_id
        LIMIT $records_per_page OFFSET $offset";

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<main class="app-main container-fluid">
    <div class="row my-3">
        <div class="col-sm-6">
            <h3 class="mb-0">Student Attendance - <?= date('F Y'); ?></h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="/education/Admin/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Students</li>
            </ol>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
                <div class="w-auto">
                    <label for="batchFilter" class="form-label fw-bold mb-1">Filter by Batch:</label>
                    <select id="batchFilter" class="form-select form-select-sm">
                        <option value="">All Batches</option>
                        <?php
                        $batch_query = "SELECT * FROM batch";
                        $batch_result = $conn->query($batch_query);
                        while ($batch = $batch_result->fetch_assoc()) {
                            echo "<option value='{$batch['batch_name']}'>{$batch['batch_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="w-auto">
                    <label for="courseFilter" class="form-label fw-bold mb-1">Filter by Course:</label>
                    <select id="courseFilter" class="form-select form-select-sm">
                        <option value="">All Courses</option>
                        <?php
                        $course_query = "SELECT * FROM course";
                        $course_result = $conn->query($course_query);
                        while ($course = $course_result->fetch_assoc()) {
                            echo "<option value='{$course['course_name']}'>{$course['course_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="text" id="searchInput" class="form-control w-25" onkeyup="searchTable()" placeholder="Search Name...">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="attendanceTable">
                    <thead class="table-dark">
                        <tr>
                            <th>S.no</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Batch</th>
                            <th>Month</th>
                            <?php for ($day = 1; $day <= $totalDays; $day++): ?>
                                <th><?= $day; ?><br><small><?= date('D', strtotime(date('Y-m-') . str_pad($day, 2, '0', STR_PAD_LEFT))); ?></small></th>
                            <?php endfor; ?>
                        </tr>
                    </thead>
                    <tbody id="TableBody">
                        <?php $counter = $offset + 1; while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $counter++; ?></td>
                                <td><?= htmlspecialchars($row['student_name']); ?></td>
                                <td><?= htmlspecialchars($row['course_name']); ?></td>
                                <td><?= htmlspecialchars($row['batch_name']); ?></td>
                                <td><?= date('F'); ?></td>
                                <?php for ($day = 1; $day <= $totalDays; $day++): ?>
                                    <?php 
                                    $currentDate = date('Y-m-') . str_pad($day, 2, '0', STR_PAD_LEFT);
                                    $attendance_query = "SELECT attendance_status FROM student_attendance WHERE student_id = {$row['student_id']} AND attendance_date = '$currentDate'";
                                    $attendance_result = $conn->query($attendance_query);
                                    $savedAttendance = $attendance_result->fetch_assoc()['attendance_status'] ?? '';
                                    ?>
                                    <td contenteditable="true" class="editable" data-student="<?= $row['student_id']; ?>" data-date="<?= $currentDate; ?>">
                                        <?= $savedAttendance; ?>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
    <!-- Left: Showing Students -->
    <span class="text-start">Showing <?= $result->num_rows; ?> students</span>
    
    <!-- Center: Pagination -->
    <ul class="pagination pagination-sm mb-0 d-flex justify-content-center flex-grow-1">
        <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?= ($page - 1); ?>">&laquo;</a>
        </li>
        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
            <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?= ($page + 1); ?>">&raquo;</a>
        </li>
    </ul>

    <!-- Right: Attendance Key -->
    <div class="d-flex justify-content-end">
        <span class="me-2">P-Present</span>
        <span class="me-2">A-Absent</span>
        <span>L-Leave</span>
    </div>
</div>

        </div>
    </div>
</main>




<?php include "../Components/footer.php"; ?>
