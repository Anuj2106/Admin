<?php
session_start();
if (!isset($_SESSION["Login_in"])) {
  header("Location:/education/Admin/Login.php");
  exit();
}

include "./Components/header.php";
include "./Components/connect.php";
include "./Components/Topbar.php";
include "./Components/sidebar.php";
include_once "./Components/function.php";

$currentYear = date("Y");
$months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

// Initialize attendance arrays with zeros for all months
$presentData = array_fill(0, 12, 0);
$absentData = array_fill(0, 12, 0);
$lateData = array_fill(0, 12, 0);

// Fetch attendance counts per month
$query = "SELECT DATE_FORMAT(attendance_date, '%b') AS month, attendance_status, COUNT(*) AS count 
          FROM student_attendance WHERE YEAR(attendance_date) = '$currentYear' 
          GROUP BY month, attendance_status ORDER BY MONTH(attendance_date)";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Attendance Query Failed: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result)) {
    $index = array_search($row["month"], $months);

    if ($index !== false) {
        if ($row["attendance_status"] === "P") {
            $presentData[$index] = (int)$row["count"];
        } elseif ($row["attendance_status"] === "A") {
            $absentData[$index] = (int)$row["count"];
        } elseif ($row["attendance_status"] === "L") {
            $lateData[$index] = (int)$row["count"];
        }
    }
}

$totalStudentsQuery = "SELECT COUNT(*) as total FROM student";
$totalResult = mysqli_query($conn, $totalStudentsQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalStudents = $totalRow['total'];

// Fetch Course Data for Doughnut Chart
$courseQuery = "SELECT c.course_name, COUNT(s.student_id) as student_count 
                FROM course c 
                LEFT JOIN student s ON c.course_id = s.course_id 
                GROUP BY c.course_id 
                ORDER BY student_count DESC"; // Order by highest enrollment

$courseResult = mysqli_query($conn, $courseQuery);

$courseNames = [];
$studentCounts = [];
$coursePercentages = [];
$colors = [];

while ($row = mysqli_fetch_assoc($courseResult)) {
    $courseNames[] = $row['course_name'];
    $studentCounts[] = $row['student_count'];

    // Calculate percentage of students in each course
    $percentage = ($row['student_count'] / $totalStudents) * 100;
    $coursePercentages[] = round($percentage, 1); // Round to 1 decimal place

    // Generate random color for each course
    $colors[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}

// Convert to JSON for JavaScript
$courseNamesJson = json_encode($courseNames);
$studentCountsJson = json_encode($studentCounts);
$coursePercentagesJson = json_encode($coursePercentages);
$colorsJson = json_encode($colors);


?>

<main class="app-main">
  <?php renderDashboardHeader(); ?>
  <div class="app-content">
    <div class="container-fluid">
      <?php
      if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2) {
        renderDashboardContent($conn);
      } else if ($_SESSION['role_id'] == 3) {
        renderTeacherContent($conn);
      } else if ($_SESSION['role_id'] == 4) {
        renderStudentContent();
      }
      ?>

      <!-- Chart.js Area Chart -->
      <div class="row mt-4 p-4">
  <!-- Monthly Attendance Report Card -->
  <div class="col-md-8 mb-3">
    <div class="card  shadow rounded">
      <div class="card-header text-center border-bottom">
        <h4 class="m-0">Monthly Attendance Report</h4>
      </div>
      <div class="card-body" >
        <div class="card   border-light p-3">
          <canvas id="attendanceChart" ></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Course Enrollment Card -->
  <div class="col-md-4">
    <div class="card   shadow rounded">
      <div class="card-header text-center border-bottom">
        <h4 class="m-0">Course Enrollment</h4>
      </div>
      <div class="card-body p-4">
        <div class="card  text-white border-light p-3">
          <canvas id="courseDoughnutChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('attendanceChart').getContext('2d');

const attendanceChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($months); ?>,
        datasets: [
            {
                data: <?php echo json_encode(array_values($presentData)); ?>,
                backgroundColor: 'rgba(0, 255, 0, 0.2)',
                borderColor: 'green',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 5,       // Adds points
                pointBackgroundColor: 'green',
                pointBorderColor: 'white',
                pointStyle: 'circle',
                hoverRadius: 7
            },
            {
            
                data: <?php echo json_encode(array_values($absentData)); ?>,
                backgroundColor: 'rgba(255, 0, 0, 0.2)',
                borderColor: 'red',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: 'red',
                pointBorderColor: 'white',
                pointStyle: 'rect',  // Square point
                hoverRadius: 7
            },
            {
              
                data: <?php echo json_encode(array_values($lateData)); ?>,
                backgroundColor: 'rgba(255, 165, 0, 0.2)',
                borderColor: 'orange',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: 'orange',
                pointBorderColor: 'white',
                pointStyle: 'triangle',  // Triangle point
                hoverRadius: 7
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
               display:false
            }
        },
        scales: {
            x: {
                grid: { display: false }
            },
            y: {
                grid: { display: false },
                beginAtZero: true
            }
        }
    }
});

</script>


<script>
const courseCtx = document.getElementById('courseDoughnutChart').getContext('2d');


const courseDoughnutChart = new Chart(courseCtx, {
    type: 'doughnut',
    data: {
      
        datasets: [{
            data: <?php echo $studentCountsJson; ?>,  // Student Count
            backgroundColor: <?php echo $colorsJson; ?>, // Dynamic Colors
            borderColor: ['#fff'],
            borderWidth: 2
            
        }]
    },
    options: {
        responsive: true,
        cutout: "80%",  
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        let index = tooltipItem.dataIndex;
                        let courseName = <?php echo $courseNamesJson; ?>[index]; // Fetch course name
                        let percentage = <?php echo $coursePercentagesJson; ?>[index]; // Fetch percentage
                        let studentCount = tooltipItem.raw;
                        return `${courseName}: ${percentage}% (${studentCount} students)`;
                    }
                }
            }
        }
    }
});
</script>


<?php include "./Components/footer.php"; ?>
