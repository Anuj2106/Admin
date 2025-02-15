<?php
session_start();
include "../Components/connect.php";
include "../Components/header.php";
include "../Components/Topbar.php";     
include "../Components/sidebar.php";
$sql="SELECT * FROM course ";
$result = $conn->query($sql);
?>
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Course</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="/education/Admin/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Course</li>
                    </ol>
                </div>
                <div class="col-12 mb-3">
                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                            Add Course
                        </button>
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
                          <th>S.No</th>
                          <th>Name</th>
                          <th>Fees</th>
                          <th>Duration</th>
                         

                        </tr>
                      </thead>
                      <tbody>
                      <?php
                      while ($row = $result->fetch_assoc()) {
                      ?>
                       <tr>
                          <td><?php echo $row['course_id'];?></td>
                          <td><?php echo $row['course_name']; ?></td>
                          <td><?php echo $row['course_fees'];?></td>
                          <td><?php echo $row['course_time'];?></td>
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
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCourseModalLabel">Add Course</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="addcourse.php" method="POST">
          <div class="mb-3">
            <label for="courseName" class="form-label">Course Name</label>
            <input type="text" class="form-control" id="courseName" name="course_name" required>
          </div>
          <div class="mb-3">
            <label for="courseFees" class="form-label">Fees</label>
            <input type="text" class="form-control" id="courseFees" name="course_fees" required>
          </div>
          <div class="mb-3">
            <label for="courseTime" class="form-label">Duration (in weeks/months)</label>
            <input type="text" class="form-control" id="courseTime" name="course_time" required>
          </div>
          <button type="submit" class="btn btn-primary">Add Course</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
include "../Components/footer.php"; 
?>