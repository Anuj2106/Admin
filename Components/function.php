<?php
// Path: Components/function.php
// Function to connect to the database
function connectDatabase() {
 include "connect.php";
    return $conn;
}
// Function to authenticate user
function authenticateUser($conn, $email, $password) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_email = ? AND user_pass = ? AND status = 0 ");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}
// Function to handle user role
function handleUserRole($role_id) {
    switch ($role_id) {
        case 1:
            $_SESSION["role"] = "Super Admin";
            break;
        case 2:
            $_SESSION["role"] = "Admin";
            break;
        case 3:
            $_SESSION["role"] = "Teacher";
            break;
        case 4:
            $_SESSION["role"] = "Student";
            break;
        default:
            return false;
    }
    header("Location: /education/Admin/index.php");
    exit();
    
}
//  Function to handle logout
function logout() {
    session_start();
    session_unset();
    session_destroy();
    header("Location: /education/Admin/Login.php");
    exit();
}
?>
<?php
function getTotalCount($conn, $table) {
  $sql = "SELECT COUNT(*) as total FROM $table";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $count = $row["total"] ?? 0;
  return $count > 0 ? $count : 0;
}

function renderSmallBoxWidget($total, $label, $icon, $link, $bgColor, $textColor = 'text-white') {
  echo "
  <div class='col-lg-3 col-6'>
    <div class='small-box $bgColor'>
      <div class='inner $textColor'>
        <h3>$total</h3>
        <p>$label</p>
      </div>
      <i class='bi $icon small-box-icon'></i>
      <a href='$link' class='small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover'>
        More info <i class='bi bi-link-45deg'></i>
      </a>
    </div>
  </div>";
}

function renderDashboardHeader() {
  echo "
  <div class='app-content-header'>
    <div class='container-fluid'>
      <div class='row'>
        <div class='col-sm-6'>
          <h3 class='mb-0'>Dashboard</h3>
        </div>
        <div class='col-sm-6'>
          <ol class='breadcrumb float-sm-end'>
            <li class='breadcrumb-item'><a href='#'>Home</a></li>
            <li class='breadcrumb-item active' aria-current='page'>Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>";
}

function renderDashboardContent($conn) {
  $widgets = [
      ["count" => getTotalCount($conn, "users"), "label" => "Users", "icon" => "bi-people-fill", "link" => "/education/Admin/User", "bgColor" => "text-bg-primary"],
      ["count" => getTotalCount($conn, "teacher"), "label" => "Employees", "icon" => "bi-person-video", "link" => "/education/Admin/Teacher", "bgColor" => "text-bg-success"],
      ["count" => getTotalCount($conn, "student"), "label" => "Students", "icon" => "bi-person-fill-add", "link" => "/education/Admin/Student", "bgColor" => "text-bg-warning"],
      ["count" => getTotalCount($conn, "course"), "label" => "Courses", "icon" => "bi-book", "link" => "/education/Admin/Course", "bgColor" => "text-bg-danger"],
      ["count" => getTotalCount($conn, "student"), "label" => "Attendance", "icon" => "bi-table", "link" => "/education/Admin/Attendance", "bgColor" => "text-bg-success"]
  ];

  echo "<div class='container-fluid'>";

  // Loop through widgets and create a new row after every 4 items
  foreach ($widgets as $index => $widget) {
      if ($index % 4 == 0) {
          // Close previous row if not the first and open a new row
          if ($index > 0) echo "</div>";
          echo "<div class='row'>";
      }

      renderSmallBoxWidget($widget["count"], $widget["label"], $widget["icon"], $widget["link"], $widget["bgColor"]);
  }

  echo "</div></div>"; // Close last row and container
}


function renderTeacherContent($conn) {
  $totalcourse = getTotalCount($conn, "course");
  $totalstudent = getTotalCount($conn, "student");
  echo "<div class='row'>";
  renderSmallBoxWidget($totalstudent, 'Students', 'bi-person-fill-add', '#', 'text-bg-warning', 'text-white');
  renderSmallBoxWidget($totalcourse, 'Courses', 'bi-book', '#', 'text-bg-danger');
  renderSmallBoxWidget($totalstudent, 'Attendance', 'bi-table', '#', 'text-bg-success');
  echo "</div>";
}

function renderStudentContent() {
  echo "<div class='row'>";
  renderSmallBoxWidget(10, 'Attendance', 'bi-table', '#', 'text-bg-success');
  echo "</div>";
}
?>