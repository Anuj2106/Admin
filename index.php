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

$totalUsers = getTotalCount($conn, "users");
$totalteacher = getTotalCount($conn, "teacher");
$totalcourse = getTotalCount($conn, "course");
$totalstudent = getTotalCount($conn, "student");
?>

<main class="app-main">
  <?php renderDashboardHeader(); ?>
  <div class="app-content">
    <div class="container-fluid">
      <?php
      if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2) {
        renderDashboardContent($conn);
      } else if ($_SESSION['role_id'] == 3) {
        renderTeacherContent();
      } else if ($_SESSION['role_id'] == 4) {
        renderStudentContent();
      }
      ?>
    </div>
  </div>
</main>

<?php
include "./Components/footer.php";
?>