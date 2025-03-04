<!-- MOdal for  Adding user table  -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="../User/adduser.php" method="POST">
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
            <input type="password" class="form-control" id="userPass" name="user_pass" required>
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
                <input type="text" class="form-control" id="Salary" name="teacher_salary">
              </div>
              <div class="mb-3">
                <label for="teacherQualification" class="form-label">Qualification</label>
                <input type="text" class="form-control" id="teacherQualification" name="teacher_qualification">
              </div>
              <div class="mb-3">
                <label for="teacherExperience" class="form-label">Experience</label>
                <input type="text" class="form-control" id="teacherExperience" name="teacher_experience">
              </div>
              <div class="mb-3">
                <label for="teacherJoinDate" class="form-label">Joining Date</label>
                <input type="date" class="form-control" id="teacherJoinDate" name="teacher_join_date">
              </div>
              <div class="mb-3">
                <label for="teacherAddress" class="form-label">Address</label>
                <input type="text" class="form-control" id="teacherAddress" name="teacher_address">
              </div>
          </div>
          <!--  Teacher Data End  -->
          <!--  Student Data Start -->
          <div class="student" style="display: none;">
              <div class="mb-3">
                <label for="Batch" class="form-label">Batch Timing</label>
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
                <select class="form-select" id="studentCourse" name="student_course" >
                  <option value="">-- Select --</option>
                  <?php
                  $sql = "SELECT * FROM course";
                  $result = $conn->query($sql);
                  while ($row = $result->fetch_assoc()) {
                  ?>
                    <option value="<?php echo $row['course_id']; ?>"><?php echo $row['course_name']; ?></option>
                  <?php
                  }
                  ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="studentFees" class="form-label">Fees</label>
                <input type="text" class="form-control" id="studentFees" name="student_fees" placeholder="Fees" readonly>
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
              </div>
              <div class="mb-3">
                <label for="studentJoinDate" class="form-label">Joining Date</label>
                <input type="date" class="form-control" id="studentJoinDate" name="student_joining_date">
              </div>
          </div>
          <!--  Student Data End  -->
          <button type="submit" class="btn btn-primary">Add User</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End OF the Teacher table  -->
 <!-- ------------------------------------------------ -->
  <!-- Modal for Course -->
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
<!-- End of the Course Modal -->
<!-- ------------------------------------------------ -->

