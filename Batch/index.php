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
$total_query = "SELECT COUNT(*) as total FROM batch";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_batch = $total_row['total'];
$total_pages = ceil($total_batch / $limit);

// Fetch paginated results
$sql = "SELECT * FROM batch LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
$count = $offset + 1;
?>
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Batch</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="/education/Admin/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">batch</li>
                    </ol>
                </div>
               
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->
    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-12">
                <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                <h1> Batches </h1>   
                <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBatchModal">
                        Add  Batch
                    </button> -->
              
              <!-- <input type="text" id="searchInput" class="form-control w-25 ms-2" onkeyup="searchTable()" placeholder="Search Name..."> -->
            </div>
                    
                <div class="card-body">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Batch No</th>
                          <th>Timing</th>
                        
                         

                        </tr>
                      </thead>
                      <tbody>
                      <?php
                      while ($row = $result->fetch_assoc()) {
                      ?>
                       <tr>
                          <td><?php echo $row['batch_id'];?></td>
                          <td><?php echo $row['batch_name'] ; ?> </td>
                       </tr>
                      <?php
                      }
                      ?>
                      </tbody >
                    </table>
                </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer d-flex justify-content-between">
                <span>Showing <?php echo $result->num_rows; ?> of <?php echo $total_batch; ?> batch</span>
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
            <!-- /.card -->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->

</main>
<!-- Modal Start -->
<!-- <div class="modal fade" id="addBatchModal" tabindex="-1" aria-labelledby="addBatchModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addBatchModalLabel">Add </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="addbatch.php" method="POST">
          <div class="mb-3">
          <label for="Batch" class="form-label"> Batch Timing</label>
     <select class="form-select" id="Batch" name="batch_name" required>
     <option value="8-10">8:00 AM - 10:00 AM</option>
  <option value="10-12">10:00 AM - 12:00 PM</option>
  <option disabled>-- Lunch Break (12:00 PM - 1:00 PM) --</option>
  <option value="1-3">1:00 PM - 3:00 PM</option>
  <option value="3-5">3:00 PM - 5:00 PM</option>
  <option value="5-7">5:00 PM - 7:00 PM</option>
            </select>
          </div>
         
          <button type="submit" class="btn btn-primary">Add batch</button>
        </form>
      </div>
    </div>
  </div>
</div> -->
<?php
include "../Components/footer.php"; 
?>