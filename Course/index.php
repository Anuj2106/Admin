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

// Pagination setup
$limit = isset($_GET['records_per_page']) ? intval($_GET['records_per_page']) : 10; // Number of records per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Get total records count
$total_query = "SELECT COUNT(*) as total FROM course";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_courses = $total_row['total'];
$total_pages = ceil($total_courses / $limit);

// Fetch paginated results
$sql = "SELECT * FROM course LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
$count = $offset + 1;
?>

<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0">Courses</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="/education/Admin/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Courses</li>
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
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                Add Course
              </button>
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
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Fees (₹)</th>
                    <th>GST (%)</th>
                    <th>Total (₹)</th>
                    <th>Discount (%)</th>
                    <th>Duration (Weeks)</th>
                    <th> Status</th>
                    <th>Discount</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="TableBody">
                  <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                      <td><?php echo $count++ ?></td>
                      <td><?php echo $row['course_name']; ?></td>
                      <td><?php echo $row['course_fees']; ?></td>
                      <td><?php echo $row['course_fees_gst']; ?></td>
                      <td><?php echo $row['course_total_fees']; ?></td>
                      <td><?php echo $row['course_discount']; ?></td>
                      <td><?php echo $row['course_time']; ?></td>
                      <td><button class="btn btn-sm toggle-status <?php echo $row['course_status'] == 0 ? 'btn-success' : 'btn-danger'; ?>" data-id="<?php echo $row['course_id']; ?>" 
              data-status="<?php echo $row['course_status']; ?>" >
                <p class="mb-0">
                  <?php echo $row['course_status'] == 0 ? 'Active' : 'Inactive'; ?>
                </p>
              </button></td>
              <td>
              <button class="btn btn-sm Discount-btn <?php echo $row['course_dis'] == 0 ? 'btn-success' : 'btn-danger'; ?>" 
        data-id="<?php echo $row['course_id']; ?>" 
        data-discount="<?php echo $row['course_dis']; ?>"
        data-totalfees="<?php echo $row['course_total_fees']; ?>"
        data-discountamount="<?php echo $row['course_discount']; ?>">
    <p class="mb-0">
      <?php echo $row['course_dis'] == 0 ? 'Apply ' : 'Remove'; ?>
    </p>
</button>

</td>
             
                      <td>
                        <button type="button" class="btn btn-sm edit-course-btn btn-primary btn-rounded" data-id="<?php echo intval($row['course_id']); ?>">
                          <i class="bi bi-pencil-square"></i>
                        </button>
                        <!--  Here is role ifd =5 for adding deleting query for course -->
                        <button class="btn btn-sm btn-danger delete-btn"
                          data-id="<?php echo intval($row['course_id']); ?> " data-source="course" data-role="5">
                          <i class="bi bi-trash-fill"></i>
                        </button>

                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="card-footer d-flex justify-content-between">
              <span>Showing <?php echo $result->num_rows; ?> of <?php echo $total_courses; ?> Courses</span>
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
<!-- Discount Modal -->
<div class="modal fade" id="discountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Apply Discount</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="course_id">
        <input type="hidden" id="current_discount">
        <div class="mb-3">
          <label for="discountPercentage" class="form-label">Discount Percentage:</label>
          <input type="number" id="discountPercentage" class="form-control" min="1" max="100">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="applyDiscount">Apply</button>
      </div>
    </div>
  </div>
</div>


<script>
$(document).ready(function () {
    $(".Discount-btn").click(function () {
        let courseId = $(this).data("id");
        let courseDis = $(this).data("discount");

        $("#course_id").val(courseId);
        $("#current_discount").val(courseDis);

        if (courseDis == 0) {
            $("#discountPercentage").val(""); // Reset input
            $("#discountModal").modal("show");
        } else {
            // Confirmation before removing discount
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to remove the discount?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, remove it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "../Components/update_discount.php",
                        type: "POST",
                        data: { course_id: courseId, discount_percentage: 0, action: "remove" },
                        success: function (response) {
                            let data = JSON.parse(response);
                            if (data.status === "success") {
                                Swal.fire({
                                    title: "Discount Removed!",
                                    text: "The discount has been successfully removed.",
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false,
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire("Error!", data.message, "error");
                            }
                        },
                    });
                }
            });
        }
    });

    // Apply discount
    $("#applyDiscount").click(function () {
        let courseId = $("#course_id").val();
        let discountPercentage = $("#discountPercentage").val();

        if (discountPercentage < 1 || discountPercentage > 100) {
            Swal.fire("Invalid Input!", "Enter a valid discount percentage (1-100)", "error");
            return;
        }

        $.ajax({
            url: "../Components/update_discount.php",
            type: "POST",
            data: { course_id: courseId, discount_percentage: discountPercentage, action: "apply" },
            success: function (response) {
                let data = JSON.parse(response);
                if (data.status === "success") {
                    Swal.fire({
                        title: "Discount Applied!",
                        text: `A discount of ${discountPercentage}% has been applied.`,
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire("Error!", data.message, "error");
                }
            },
        });
    });
});


</script>

<?php include "../Components/footer.php";
include "../Components/modal.php";
?>