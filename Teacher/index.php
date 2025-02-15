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
$sql="SELECT * FROM teacher ";
$result = $conn->query($sql);
?>
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Teacher</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="/education/Admin/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Teacher</li>
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
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Phone No</th>
                          <th>Salary</th>
                          <th>Qualification</th>
                          <th>Exprerience</th>
                          <th>Joining Date </th>
                          <th>Address </th>
                          <th>Status </th>
                          <th>Edit </th>

                        </tr>
                      </thead>
                      <tbody>
                      <?php
                      while ($row = $result->fetch_assoc()) {
                      ?>
                       <tr>
                          <td><?php echo $row['teacher_id'];?></td>
                          <td><?php echo $row['teacher_name']; ?></td>
                          <td><?php echo $row['teacher_email'];?></td>
                          <td><?php echo $row['teacher_phone'];?></td>
                          <td><?php echo $row['teacher_salary'];?></td>
                          <td><?php echo $row['teacher_qualification'];?></td>
                          <td><?php echo $row['teacher_exprience'];?></td>
                          <td><?php echo $row['teacher_join_date'];?></td>
                          <td><?php echo $row['teacher_address'];?></td>
                          <td> <?php echo $row['teacher_status']==0 ? 'Active':'InActive';?></td>
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
<?php
include "../Components/footer.php";
?>