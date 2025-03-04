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
$limit = isset($_GET['records_per_page']) ? intval($_GET['records_per_page']) : 10; // Number of records per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Get total records count
$total_query = "SELECT COUNT(*) as total FROM teacher";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_courses = $total_row['total'];
$total_pages = ceil($total_courses / $limit);
// Fetch paginated results
$sql = "SELECT * FROM teacher";
$result = $conn->query($sql);
?>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0">Teacher Management</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="/education/Admin/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Teachers</li>
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
            <h5 class="card-title mb-0 me-auto">Course List</h5>
              <div class="d-flex align-items-center mx-auto">
                <label for="recordsPerPage" class="me-2">Show</label>
                <select id="recordsPerPage" class="form-select w-auto" onchange="changeRecordsPerPage()">
                  <option value="10" <?php echo ($limit == 10) ? 'selected' : ''; ?>>10</option>
                  <option value="20" <?php echo ($limit == 20) ? 'selected' : ''; ?>>20</option>
                  <option value="50" <?php echo ($limit == 50) ? 'selected' : ''; ?>>50</option>
                </select>
              </div>
              <input type="text" id="searchInput" class="form-control w-25 ms-2" onkeyup="searchTable()" placeholder="Search Name...">
            </div>
            <div class="card-body">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>#</thc
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Salary</th>
                    <th>Qualification</th>
                    <th>Experience</th>
                    <th>Joining Date</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="TableBody">
                  <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                      <td><?php echo $row['teacher_id']; ?></td>
                      <td><?php echo $row['teacher_name']; ?></td>
                      <td><?php echo $row['teacher_email']; ?></td>
                      <td><?php echo $row['teacher_phone']; ?></td>
                      <td><?php echo $row['teacher_salary']; ?></td>
                      <td><?php echo $row['teacher_qualification']; ?></td>
                      <td><?php echo $row['teacher_exprience']; ?></td>
                      <td><?php echo $row['teacher_join_date']; ?></td>
                      <td><?php echo $row['teacher_address']; ?></td>
                      <td>
                        <button class="btn btn-sm toggle-status <?php echo $row['teacher_status'] == 0 ? 'btn-success' : 'btn-danger'; ?>" data-phone="<?php echo $row['teacher_phone']; ?>" 
                        data-status="<?php echo $row['teacher_status']; ?>" data-role="3"
                        >
                        <p class="mb-0">
                          <?php echo $row['teacher_status'] == 0 ? 'Active' : 'Inactive'; ?>
                        </p>
                        </button>
                      </td>
                      <td>
                        <button type="button" class="btn btn-sm btn-primary btn-rounded">
                          <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm  btn-danger delete-btn" data-phone="<?php echo  intval($row['teacher_phone']) ?>" data-role="3" >
                          <i class="bi bi-trash-fill"></i>
                        </button>
                        <button class="btn btn-sm btn-info view-btn" data-id="<?php echo  intval($row['teacher_id']) ?>" data-role="3" data-source="teacher">
                          <i class="bi bi-eye-fill"></i>
                        </button>

                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="card-footer d-flex justify-content-between">
              <span>Showing <?php echo $result->num_rows; ?> of <?php echo $total_courses; ?> Teacher</span>
              <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                  <a class="page-link" href="?page=<?php echo max(1, $page - 1); ?>&records_per_page=<?php echo $limit; ?>">&laquo;</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                  <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&records_per_page=<?php echo $limit; ?>"><?php echo $i; ?></a>
                  </li>
                <?php } ?>
                <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                  <a class="page-link" href="?page=<?php echo min($total_pages, $page + 1); ?>&records_per_page=<?php echo $limit; ?>">&raquo;</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include "../Components/footer.php";
include "../Components/modal.php"
?>