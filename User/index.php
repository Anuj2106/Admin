<?php
session_start();
include "../Components/header.php";
include "../Components/Topbar.php";
include "../Components/sidebar.php";
include "../Components/connect.php";

// Set how many records per page
$records_per_page = 10;

// Get the current page number from the URL, default to page 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

// Calculate the offset for SQL query
$offset = ($page - 1) * $records_per_page;

// Count total number of users
$total_users_query = "SELECT COUNT(*) AS total FROM users";
$total_users_result = $conn->query($total_users_query);
$total_users_row = $total_users_result->fetch_assoc();
$total_users = $total_users_row['total'];

// Calculate total pages
$total_pages = ceil($total_users / $records_per_page);

// Fetch users with LIMIT and OFFSET
$sql = "SELECT users.*, master_role.Role FROM users 
        INNER JOIN master_role ON users.role_id = master_role.role_id 
        LIMIT $records_per_page OFFSET $offset";
$result = $conn->query($sql);
if (!$result) {
    echo "Query Failed: " . $sql . "<br>" . $conn->error;
}
?>

<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Users</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User</li>
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
                <div class="col-12 mb-3">
                    <!-- Button to trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-person-fill-add">
 </i>
                        
                        Add User
                        
                    </button>
                </div>
                <div class="col-12">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Phone No</th>
                          <th>Role</th>
                          <th>Status</th>
                          <th>Edit</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                      while ($row = $result->fetch_assoc()) {
                      ?>
                       <tr>
                          <td><?php echo $row['user_id'];?></td>
                          <td><?php echo $row['user_name']; ?></td>
                          <td><?php echo $row['user_email'];?></td>
                          <td><?php echo $row['user_phone'];?></td>
                          <td><?php echo $row['Role'];?></td>
                          <td> <?php echo $row['status']==0 ? 'Active':'InActive';?></td>
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
                        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo ($page - 1); ?>">&laquo;</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo ($page + 1); ?>">&raquo;</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
</main>
<!--  Form for user -->
<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="adduser.php" method="POST">
          <div class="mb-3">
            <label for="userName" class="form-label">Name</label>
            <input type="text" class="form-control" id="userName" name="user_name" required>
          </div>
          <div class="mb-3">
            <label for="userEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="userEmail" name="user_email" required>
          </div>
          <div class="mb-3">
            <label for="userPhone" class="form-label">Phone No</label>
            <input type="text" class="form-control" id="userPhone" name="user_phone" required>
          </div>
          <div class="mb-3">
            <label for="userPass" class="form-label">Password</label>
            <input type="text" class="form-control" id="userPass" name="user_pass" required>
          </div>
          <div class="mb-3">
            <label for="userRole" class="form-label">Role</label>
            <select class="form-select" id="userRole" name="role_id" required>
              <option value="2">Admin</option>
              <option value="3">Teacher</option>
              <option value="4">Student</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="userStatus" class="form-label">Status</label>
            <select class="form-select" id="userStatus" name="status" required>
              <option value="0">Active</option>
              <option value="1">Inactive</option>
            </select>
           
          </div>
          <!--  Teacher Data Begin -->
          <div class="teacher" style="display: none;">
              <div class="mb-3">
                <label for="Salary" class="form-label">Salary</label>
                <input type="text" class="form-control" id="Salary" name="teacher_salary" require>
              </div>
              <div class="mb-3">
                <label for="teacherQualification" class="form-label">Qualification</label>
                <input type="text" class="form-control" id="teacherQualification" name="teacher_qualification">
              </div>
              <div class="mb-3">
                <label for="teacherExperience" class="form-label">Exprience</label>
                <input type="text" class="form-control" id="teacherExperience" name="teacher_exprience">
              </div>
              <div class="mb-3">
                <label for="teacherJoinDate" class="form-label">Joining Date</label>
                <input type="Date" class="form-control" id="teacherJoinDate" name="teacher_join_date">
              </div>
              <div class="mb-3">
                <label for="teacheraddress" class="form-label">Address</label>
                <input type="text" class="form-control" id="teacheraddress" name="teacher_address">
              </div>
              </div>
              
 <!--  Teacher Data End  -->
   <!--  Student Data Start -->
   <div class="student" style="display: none;">
   <div class="mb-3">
          <label for="Batch" class="form-label"> Batch Timing</label>
     <select class="form-select" id="Batch" name="batch_id" required>
     <option value="1">8:00 AM - 10:00 AM</option>
  <option value="2">10:00 AM - 12:00 PM</option>
  <option disabled>-- Lunch Break (12:00 PM - 1:00 PM) --</option>
  <option value="3">1:00 PM - 3:00 PM</option>
  <option value="4">3:00 PM - 5:00 PM</option>
  <option value="5">5:00 PM - 7:00 PM</option>
            </select>
          </div>
   <div class="mb-3">
                <label for="studentCourse" class="form-label">Course</label>
                <input type="text" class="form-control" id="studentCourse" name="student_course">
              </div>
   <div class="mb-3">
                <label for="studentFees" class="form-label">Fees</label>
                <input type="text" class="form-control" id="studentFees" name="student_fees">
              </div>
   <div class="mb-3">
                <label for="studentAddress" class="form-label">Address</label>
                <input type="text" class="form-control" id="studentAddress" name="student_address">
              </div>
              <div class="mb-3">
                <label for="StudentGender" class="form-label">Gender</label>
                <select class="form-select" id="StudentGender" name="student_gender" required>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Other">Other</option>
                </select>
                <div class="mb-3">
                             <label for="studentjoining" class="form-label">Joining Date</label>
                             <input type="Date" class="form-control" id="student" name="student_joining_date">
                           </div>
              </div>

   </div>
   <!--  Student Data End  -->
          <button type="submit" class="btn btn-primary">Add User</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  document.getElementById('userRole').addEventListener('change',function(){
    document.querySelector('.teacher').style.display='none';
    document.querySelector('.student').style.display='none';
    if(this.value==3){
      document.querySelector('.teacher').style.display='block';
    }
    else if(this.value==4){
      document.querySelector('.student').style.display='block';
    }


  });
</script>
<?php
include "../Components/footer.php";
?>