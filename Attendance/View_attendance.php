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

$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m');
$selectedMonth = date('m', strtotime($selectedDate));
$selectedYear = date('Y', strtotime($selectedDate));
$totalDays = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);

$sql = "SELECT 
    student.student_id, 
    student.student_name,
    course.course_name,
    batch.batch_name
FROM student_attendance
INNER JOIN batch ON student_attendance.batch_id = batch.batch_id
INNER JOIN course ON student_attendance.course_id = course.course_id
INNER JOIN student ON student_attendance.student_id = student.student_id
GROUP BY student.student_id, student.student_name, course.course_name, batch.batch_name";

$result = $conn->query($sql);

?>

<main class="app-main container-fluid">
    <div class="row my-3">
        <div class="col-sm-6">
            <h3 class="mb-0">View Student Attendance</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="/education/Admin/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Attendance</li>
            </ol>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-4">
                    <label for="batchFilter">Select Batch:</label>
                    <select id="batchFilter" class="form-control">
                        <option value="">All Batches</option>
                        <?php
                        $batch_query = "SELECT DISTINCT batch_name FROM batch";
                        $batch_result = $conn->query($batch_query);
                        while ($batch_row = $batch_result->fetch_assoc()) {
                            echo "<option value='{$batch_row['batch_name']}'>{$batch_row['batch_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="courseFilter">Select Course:</label>
                    <select id="courseFilter" class="form-control">
                        <option value="">All Courses</option>
                        <?php
                        $course_query = "SELECT DISTINCT course_name FROM course";
                        $course_result = $conn->query($course_query);
                        while ($course_row = $course_result->fetch_assoc()) {
                            echo "<option value='{$course_row['course_name']}'>{$course_row['course_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
    <label for="dateRange">Select Date Range:</label>
    <div class="position-relative">
        <input type="text" id="dateRange" class="form-control">
        <i class="bi bi-caret-down-fill position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
    </div>
</div>

            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>S.no</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Batch</th>
                            <th>Month</th>
                            <?php for ($day = 1; $day <= $totalDays; $day++): ?>
                                <th data-date="<?= "$selectedYear-$selectedMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT); ?>">
                                    <?= $day; ?><br>
                                    <small><?= date('D', strtotime("$selectedYear-$selectedMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT))); ?></small>
                                </th>
                            <?php endfor; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter = 1; while ($row = $result->fetch_assoc()) {      ?>
                            <tr>
                                <td><?= $counter++; ?></td>
                                <td><?= htmlspecialchars($row['student_name']); ?></td>
                                <td><?= htmlspecialchars($row['course_name']); ?></td>
                                <td><?= htmlspecialchars($row['batch_name']); ?></td>
                                <td><?= date('F', mktime(0, 0, 0, $selectedMonth, 1)); ?></td>
                                <?php for ($day = 1; $day <= $totalDays; $day++): ?>
                                    <?php 
                                    $attendanceDate = "$selectedYear-$selectedMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT);

                                    $attendance_query = "SELECT attendance_status FROM student_attendance 
                                                         WHERE student_id = '{$row['student_id']}' 
                                                         AND attendance_date = '$attendanceDate'";
                                    $attendance_result = $conn->query($attendance_query);
                                    if (!$attendance_result) {
                                        die("Attendance query failed: " . $conn->error);
                                    }
                                    $savedAttendance = $attendance_result->fetch_assoc()['attendance_status'] ?? '-';
                                    ?>
                                    <td data-date="<?= $attendanceDate; ?>">
                                        <?= htmlspecialchars($savedAttendance); ?>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>


<script>
$(document).ready(function () {
    $('#dateRange').daterangepicker({
        locale: { format: 'YYYY-MM-DD' },
        opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    $("#batchFilter, #courseFilter, #dateRange").on("change", function () {
        let selectedBatch = $("#batchFilter").val().toLowerCase();
        let selectedCourse = $("#courseFilter").val().toLowerCase();
        let selectedDateRange = $("#dateRange").val().split(" - ");
        let startDate = new Date(selectedDateRange[0]);
        let endDate = new Date(selectedDateRange[1]);

        $("tbody tr").each(function () {
            let batch = $(this).find("td").eq(3).text().toLowerCase();
            let course = $(this).find("td").eq(2).text().toLowerCase();
            let match = true;

            if (selectedBatch && !batch.includes(selectedBatch)) {
                match = false;
            }
            if (selectedCourse && !course.includes(selectedCourse)) {
                match = false;
            }

            let dateMatch = false;
            $(this).find("td[data-date]").each(function () {
                let dateText = $(this).attr("data-date");
                if (dateText) {
                    let attendanceDate = new Date(dateText);
                    if (attendanceDate >= startDate && attendanceDate <= endDate) {
                        dateMatch = true;
                    }
                }
            });

            if (!dateMatch) {
                match = false;
            }

            $(this).toggle(match).append(match ? '' : '<td colspan="100%">No Data</td>');
        });
    });
});

</script>

<?php include "../Components/footer.php"; ?>
