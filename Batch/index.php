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
$sql="SELECT * FROM batch ";
$result = $conn->query($sql);
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
                        <li class="breadcrumb-item active" aria-current="page">Course</li>
                    </ol>
                </div>
                <div class="col-12 mb-3">
                        <!-- Button to trigger modal -->
                        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBatchModal">
                            Add  Batch
                        </button> -->
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
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Batch No</th>
                          <th>Timing</th>
                          <th>Edit</th>
                         

                        </tr>
                      </thead>
                      <tbody>
                      <?php
                      while ($row = $result->fetch_assoc()) {
                      ?>
                       <tr>
                          <td><?php echo $row['batch_id'];?></td>
                          <td><?php echo $row['batch_name'] ; ?> </td>
                         
                          <td><a href="#" class="btn btn-sm btn-warning">Edit</a></td>
                       </tr>
                      <?php
                      }
                      ?>
                      </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-end">
                      <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                      <li class="page-item"><a class="page-link" href="#">1</a></li>
                      <li class="page-item"><a class="page-link" href="#">2</a></li>
                      <li class="page-item"><a class="page-link" href="#">3</a></li>
                      <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                    </ul>
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