
<!-- MOdal for  Adding user table  -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="userForm" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="userName" class="form-label">Name</label>
            <input type="text" class="form-control" id="userName" name="user_name" required >
          </div>
          <div class="mb-3">
    <label for="userImage" class="form-label">Upload Image</label>
    <input type="file" class="form-control" id="userImage" name="user_image" required>
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
                <select class="form-select" id="Batch" name="batch_id">
                  <option value="">Select</option>
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
                <select class="form-select" id="studentCourse" name="course_id" >
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
                <input type="text" class="form-control" id="studentFees" name="student_fees" placeholder="Fees" >
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
  <!-- Modal for ADDING  Course -->
 <div class="modal fade" id="addCourseModal"  tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCourseModalLabel">Add Course</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="../Course/addcourse.php" id="courseForm" method="POST">
          <div class="mb-3">
            <label for="courseName" class="form-label">Course Name</label>
            <input type="text" class="form-control" id="courseName" name="course_name" required>
          </div>
          <div class="mb-3">
            <label for="courseFees" class="form-label">Fees</label>
            <input type="text" class="form-control" id="courseFees" name="course_fees" required>
          </div>
          <div class="mb-3">
            <label for="courseGst" class="form-label">Gst (In %)</label>
            <input type="text" class="form-control" id="courseGst" name="course_gst" required>
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
<!-- EDIT COURSE MODAL  -->
<div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCourseModalLabel">Edit Course</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editCourseForm" method="POST">
          <input type="hidden" id="editCourseId" name="course_id"> <!-- Hidden ID Field -->
          <div class="mb-3">
            <label for="editCourseName" class="form-label">Course Name</label>
            <input type="text" class="form-control" id="editCourseName" name="course_name" required>
          </div>
          <div class="mb-3">
            <label for="editCourseFees" class="form-label">Fees</label>
            <input type="text" class="form-control" id="editCourseFees" name="course_fees" required>
          </div>
          <div class="mb-3">
            <label for="editCourseGst" class="form-label">Gst (In %)</label>
            <input type="text" class="form-control" id="editCourseGst" name="course_gst" required>
          </div>
          <div class="mb-3">
            <label for="editCourseTime" class="form-label">Duration (in weeks/months)</label>
            <input type="text" class="form-control" id="editCourseTime" name="course_time" required>
          </div>
          <button type="submit" class="btn btn-primary">Update Course</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- End of the Course Modal -->
<!-- ------------------------------------------------ -->
<!-- edit modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm" enctype="multipart/form-data">
          <input type="hidden" id="editUserId" name="user_id">
          <div class="mb-3">
            <label for="editUserName" class="form-label">Name</label>
            <input type="text" class="form-control" id="editUserName" name="user_name" required>
          </div>
          <div class="mb-3">
            <label for="editUserEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="editUserEmail" name="user_email" required>
          </div>
          <div class="mb-3">
            <label for="editUserPhone" class="form-label">Phone No</label>
            <input type="text" class="form-control" id="editUserPhone" name="user_phone" required>
          </div>
          <div class="mb-3">
            <label for="editUserPass" class="form-label">Password</label>
            <input type="password" class="form-control" id="editUserPass" name="user_pass">
          </div>
          <div class="mb-3">
            <label for="editUserRole" class="form-label">Role</label>
            <select class="form-select" id="editUserRole" name="role_id" required>
              <option value="2">Admin</option>
              <option value="3">Teacher</option>
              <option value="4">Student</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="editUserStatus" class="form-label">Status</label>
            <select class="form-select" id="editUserStatus" name="status" required>
              <option value="0">Active</option>
              <option value="1">Inactive</option>
            </select>
          </div>

          <!-- ✅ Image Upload Field -->
          <div class="mb-3">
            <label for="editUserImage" class="form-label">Profile Image</label>
            <input type="file" class="form-control" id="editUserImage" name="user_image" accept="image/*">
            <div class="mt-2">
              <img id="previewUserImage" src="" alt="User Image" width="150" style="border-radius: 8px;">
            </div>
          </div>

          <!-- ✅ Teacher Fields (Hidden by Default) -->
          <div class="teacher-fields" style="display: none;">
            <div class="mb-3">
              <label for="editTeacherSalary" class="form-label">Salary</label>
              <input type="text" class="form-control" id="editTeacherSalary" name="teacher_salary">
            </div>
            <div class="mb-3">
              <label for="editTeacherQualification" class="form-label">Qualification</label>
              <input type="text" class="form-control" id="editTeacherQualification" name="teacher_qualification">
            </div>
            <div class="mb-3">
              <label for="editTeacherExperience" class="form-label">Experience</label>
              <input type="text" class="form-control" id="editTeacherExperience" name="teacher_experience">
            </div>
            <div class="mb-3">
              <label for="editTeacherJoinDate" class="form-label">Joining Date</label>
              <input type="date" class="form-control" id="editTeacherJoinDate" name="teacher_join_date">
            </div>
            <div class="mb-3">
              <label for="editTeacherAddress" class="form-label">Address</label>
              <input type="text" class="form-control" id="editTeacherAddress" name="teacher_address">
            </div>
          </div>

          <!-- ✅ Student Fields (Hidden by Default) -->
          <div class="student-fields" style="display: none;">
            <div class="mb-3">
              <label for="editStudentBatch" class="form-label">Batch Timing</label>
              <select class="form-select" id="editStudentBatch" name="batch_id">
                <option value="1">8:00 AM - 10:00 AM</option>
                <option value="2">10:00 AM - 12:00 PM</option>
                <option disabled>-- Lunch Break (12:00 PM - 1:00 PM) --</option>
                <option value="3">1:00 PM - 3:00 PM</option>
                <option value="4">3:00 PM - 5:00 PM</option>
                <option value="5">5:00 PM - 7:00 PM</option>
              </select>
            </div>
            <div class="mb-3">
    <label for="editStudentCourse" class="form-label">Course</label>
    <select class="form-select" id="editStudentCourse" name="course_id">
        <option value="">-- Select --</option>
        <?php
        $sql = "SELECT * FROM course";
        $result = $conn->query($sql);

        // Assuming $selectedCourseId is retrieved from the database (previously selected value)
        while ($row = $result->fetch_assoc()) {
            $selected = ($row['course_id'] == $selectedCourseId) ? 'selected' : '';
           
            ?>
            <option value="<?php echo $row['course_id']; ?>" <?php echo $selected; ?>>
                <?php echo htmlspecialchars($row['course_name']); ?>
            </option>
        <?php
        }
        ?>
    </select>
</div>


            <input type="hidden" id="hiddenUserImage" name="old_image">

            <div class="mb-3">
              <label for="editStudentFees" class="form-label">Fees</label>
              <input type="text" class="form-control" id="editStudentFees" name="student_fees" readonly>
            </div>
            <div class="mb-3">
              <label for="editStudentAddress" class="form-label">Address</label>
              <input type="text" class="form-control" id="editStudentAddress" name="student_address">
            </div>
            <div class="mb-3">
              <label for="editStudentGender" class="form-label">Gender</label>
              <select class="form-select" id="editStudentGender" name="student_gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="editStudentJoinDate" class="form-label">Joining Date</label>
              <input type="date" class="form-control" id="editStudentJoinDate" name="student_joining_date">
            </div>
          </div>

          <button type="submit" class="btn btn-primary">Update User</button>
        </form>
      </div>
    </div>
  </div>
</div>
