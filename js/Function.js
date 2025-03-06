$(document).ready(function () {
  // Handle user role dropdown change
  let userRoleDropdown = $("#userRole");
  if (userRoleDropdown.length) {
      userRoleDropdown.on("change", function () {
          $(".teacher, .student").hide();
          let selectedRole = $(this).val();
          selectedRole == 3 ? $(".teacher").show() : selectedRole == 4 ? $(".student").show() : null;
      });
  }

  // Handle status toggle
  $(document).on("click", ".toggle-status", function () {
      let button = $(this);
      let recordPhone = button.data("phone");
      let role = button.data("role");
      let currentStatus = button.data("status");

      $.post("../Components/update_status.php", { phone: recordPhone, status: currentStatus, role: role }, function (response) {
          if (response.trim() === "success") {
              let newStatus = currentStatus == 1 ? 0 : 1;
              button.data("status", newStatus).toggleClass("btn-success btn-danger").find("p").text(newStatus ? "Inactive" : "Active");
          } else {
              alert("Failed to update status.");
          }
      }).fail(function (xhr) {
          console.error("AJAX Error:", xhr.responseText);
      });
  });

  // Handle view details
  $(document).on("click", ".view-btn", function () {
    $.post("../Components/view_details.php", $(this).data(), function (response) {
        Swal.fire({
            title: "Deatails",
            html: response, // Display the fetched details inside SweetAlert2
            showCloseButton: true,
            showConfirmButton: false,
            width: '600px', // Customize width if needed
        });
    }).fail(function (xhr) {
        console.error("AJAX Error:", xhr.responseText);
    });
});


  // Handle pagination
  $("#recordsPerPage").on("change", function () {
      let urlParams = new URLSearchParams(window.location.search);
      urlParams.set("records_per_page", $(this).val());
      urlParams.set("page", "1");
      window.location.search = urlParams.toString();
  });

  // Handle search
  $("#searchInput").on("input", function () {
      let input = $(this).val().toLowerCase();
      $("#TableBody tr").each(function () {
          let name = $(this).find("td").eq(0).text().toLowerCase();
          let role = $(this).find("td").eq(1).text().toLowerCase();
          $(this).toggle(name.includes(input) || role.includes(input));
      });
  });

  // Handle delete user
  $(document).on("click", ".delete-btn", function () {
      let requestData = $(this).data("phone") && $(this).data("role") ? { phone: $(this).data("phone"), role: $(this).data("role") } : { role: $(this).data("role"), id: $(this).data("id") };
      Swal.fire({
          title: "Are you sure?",
          text: "This action cannot be undone!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Yes, delete it!",
      }).then((result) => {
          if (result.isConfirmed) {
              $.post("../Components/delete.php", requestData, function (response) {
                  Swal.fire({
                      title: response.trim() === "success" ? "Deleted!" : "Failed!",
                      text: response.trim() === "success" ? "The record has been deleted." : response,
                      icon: response.trim() === "success" ? "success" : "error",
                      timer: 2000,
                      showConfirmButton: false,
                  }).then(() => {
                      if (response.trim() === "success") location.reload();
                  });
              }).fail(function (xhr) {
                  console.error("AJAX Error:", xhr.responseText);
                  Swal.fire({ title: "Error!", text: "An error occurred. Try again later.", icon: "error", timer: 5000, showConfirmButton: false });
              });
          }
      });
  });

  // Handle add course
  $("#courseForm").submit(function (e) {
      e.preventDefault();
      $.post("../Course/addcourse.php", $(this).serialize(), function (response) {
          Swal.fire({ icon: response.status === "success" ? "success" : "error", title: response.status === "success" ? "Success" : "Error", text: response.message }).then(() => {
              if (response.status === "success") location.reload();
          });
      }, "json").fail(function (xhr) {
          console.error(xhr.responseText);
          Swal.fire({ icon: "error", title: "Error", text: "Something went wrong!" });
      });
  });

  // Handle update user
  $(".edit-btn").click(function () {
    let userPhone = $(this).data("phone");
    let userRole = $(this).data("role");

    console.log("Fetching details for:", userPhone, "Role:", userRole); // Debugging

    Swal.fire({
        title: "Are you sure?",
        text: "You want to edit this user!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Edit!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../Components/Update.php",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({ fetch: true, phone: userPhone, role: userRole }),
                dataType: "json",
                success: function (response) {
                    console.log("Response received:", response); // Debugging

                    if (response.error) {
                        Swal.fire("Error!", response.error, "error");
                    } else {
                        // Fill form fields with response data
                        $("#editUserId").val(response.user_id);
                        $("#editUserName").val(response.user_name);
                        $("#editUserEmail").val(response.user_email);
                        $("#editUserPhone").val(response.user_phone);
                        $("#editUserPass").val(response.user_pass);
                        $("#editUserRole").val(response.role_id);
                        $("#editUserStatus").val(response.status);

                        // Show specific fields based on role
                        $(".teacher-fields, .student-fields").hide(); // Hide all first
                        if (response.role_id == 3) {
                            $("#editTeacherSalary").val(response.teacher_salary);
                            $("#editTeacherQualification").val(response.teacher_qualification);
                            $("#editTeacherExperience").val(response.teacher_exprience);
                            $("#editTeacherJoinDate").val(response.teacher_join_date);
                            $("#editTeacherAddress").val(response.teacher_address);
                            $(".teacher-fields").show();
                        } else if (response.role_id == 4) {
                            $("#editStudentBatch").val(response.batch_id);
                            $("#editStudentCourse").val(response.student_course);
                            $("#editStudentFees").val(response.student_fees);
                            $("#editStudentGender").val(response.student_gender);
                            $("#editStudentJoinDate").val(response.student_joining_date);
                            $("#editStudentAddress").val(response.student_address);
                            $(".student-fields").show();
                        }

                        $("#editUserModal").modal("show"); // Show the modal
                    }
                },
                error: function (xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                    Swal.fire("Error!", "Something went wrong!", "error");
                }
            });
        }
    });
});


  // Handle edit form submission
  $("#editForm").submit(function (e) {
      e.preventDefault();
      $.post("../Components/Update.php", $(this).serialize() + "&update=true", function (response) {
          Swal.fire({
              title: "Updated!",
              text: "User details updated successfully!",
              icon: "success",
              timer: 2000,
              showConfirmButton: false
          });

          // Reload after 2 seconds
          setTimeout(() => location.reload(), 2000);
      }, "json").fail(() => {
          Swal.fire("Error!", "Update failed!", "error");
      });
  });

});
//  FOR HANDLE COURSE 
$(document).ready(function () {
    // Open Edit Course Modal
    $(".edit-course-btn").click(function () {
        let courseId = $(this).data("id");
        console.log("Fetching course with ID:", courseId);
        
        // Fetch Course Details via AJAX
        $.ajax({
            url: "../Components/Update.php", 
            type: "POST",
            contentType: "application/json",  // Send as JSON
            data: JSON.stringify({ fetch: true, course_id: courseId }), 
            dataType: "json",
            success: function (response) {
                if (response.error) {
                    Swal.fire("Error!", response.error, "error");
                } else {
                    // Fill modal with fetched data
                    $("#editCourseId").val(response.course_id);
                    $("#editCourseName").val(response.course_name);
                    $("#editCourseFees").val(response.course_fees);
                    $("#editCourseGst").val(response.course_fees_gst);
                    $("#editCourseTime").val(response.course_time);

                    $("#editCourseModal").modal("show"); // Show modal
                }
            },
            error: function (xhr) {
                console.error("AJAX Error:", xhr.responseText);
                Swal.fire("Error!", "Something went wrong!", "error");
            }
        });
    });

    // Submit Updated Course Data via AJAX
    $("#editCourseForm").submit(function (e) {
        e.preventDefault();

        let courseId = $("#editCourseId").val();
        let courseName = $("#editCourseName").val();
        let courseFees = $("#editCourseFees").val();
        let courseGst = $("#editCourseGst").val();
        let courseTime = $("#editCourseTime").val();

        let formData = {
            update: true,
            course_id: courseId,
            course_name: courseName,
            course_fees: courseFees,
            course_gst: courseGst,
            course_time: courseTime
        };

        console.log("Sending update data:", formData);

        $.ajax({
            url: "../Components/Update.php",
            type: "POST",
            contentType: "application/json",  // Send JSON
            data: JSON.stringify(formData),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    Swal.fire("Success!", response.success, "success").then(() => {
                        location.reload(); // Reload page after update
                    });
                } else {
                    Swal.fire("Error!", response.error, "error");
                }
            },
            error: function (xhr) {
                console.error("AJAX Error:", xhr.responseText);
                Swal.fire("Error!", "Something went wrong!", "error");
            }
        });
    });
});


// for  taking attendace 
$(document).ready(function () {
    $(".attendance-dropdown").on("change", function () {
        var student_id = $(this).data("student");
        var course_id = $(this).data("course"); // Added course_id
        var batch_id = $(this).data("batch"); // Added batch_id
        var date = $(this).data("date");
        var status = $(this).val();

        $.ajax({
            url: "../Components/update_attendance.php",
            type: "POST",
            data: {
                student_id: student_id,
                course_id: course_id,
                batch_id: batch_id,
                date: date,
                status: status
            },
            success: function (response) {
                console.log(response);
            },
            error: function () {
                alert("Error updating attendance.");
            }
        });
    });
});

$(document).ready(function () {
    $("#batchFilter, #courseFilter").on("change", function () {
        let selectedBatch = $("#batchFilter").val().toLowerCase();
        let selectedCourse = $("#courseFilter").val().toLowerCase();
        
        
        $("tbody tr").each(function () {
            let batch = $(this).find("td").eq(3).text().toLowerCase(); // Batch Name (4th Column)
            let course = $(this).find("td").eq(2).text().toLowerCase(); // Course Name (3rd Column)

            if (
                (selectedBatch === "" || batch.includes(selectedBatch)) &&
                (selectedCourse === "" || course.includes(selectedCourse))
            ) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});



// 
const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
const Default = {
  scrollbarTheme: 'os-theme-light',
  scrollbarAutoHide: 'leave',
  scrollbarClickScroll: true,
};
document.addEventListener('DOMContentLoaded', function () {
  const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
  if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
    OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
      scrollbars: {
        theme: Default.scrollbarTheme,
        autoHide: Default.scrollbarAutoHide,
        clickScroll: Default.scrollbarClickScroll,
      },
    });
  }
});
