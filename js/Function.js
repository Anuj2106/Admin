// Handle AJAX requests
$(document).ready(function () {
    let userRoleDropdown = $("#userRole");
    
    if (userRoleDropdown.length) {
      userRoleDropdown.on("change", function () {
        $(".teacher, .student").hide();
        
        if ($(this).val() == 3) {
          $(".teacher").show();
        } else if ($(this).val() == 4) {
          $(".student").show();
        }
      });
    }
  });
  

  // Handle status toggle

  $(document).on("click", ".toggle-status", function() {
    var button = $(this);
    var reacordPhone = button.data("phone");  // Get ID (user_id, teacher_id, or student_id)
    var role = button.data("role");  // Get ID (user_id, teacher_id, or student_id)
    var currentStatus = button.data("status"); // Get current status

    console.log("Clicked:", { id: reacordPhone, status: currentStatus , role: role});

    $.ajax({
        url: "../Components/update_status.php", // Adjust path if needed
        type: "POST",
        data: { phone: reacordPhone, status: currentStatus , role: role},
        success: function(response) {
            
            if (response.trim() === "success") {
                var newStatus = (currentStatus == 1) ? 0 : 1;
                button.data("status", newStatus);
                button.toggleClass("btn-success btn-danger");
                button.find("p").text(newStatus ? "Inactive" : "Active");
            } else {
                alert("Failed to update status.");
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            console.log("XHR Response:", xhr.responseText);
        }
    });
});
// Handle view details





$(document).on("click", ".view-btn", function() {
    var userId = $(this).data("id"); // Get the user ID from the data attribute
    var userRole = $(this).data("role"); 
    var userSource = $(this).data("source"); 
    console.log("Viewing:", { id: userId, role: userRole , sourse: userSource});
    // Get the user role from the data attribute

    $.ajax({
        url: "../Components/view_details.php", // Adjust path if needed
        type: "POST",
        data: { id: userId, role: userRole , source: userSource},
        success: function(response) {
            $("#user-details").html(response); // Update the modal body with the response
            $("#viewModal").modal("show"); // Show the modal
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            console.log("XHR Response:", xhr.responseText);
        }
    });
});
    // Handle edit user
     
    
    
// Handle pagination
function changeRecordsPerPage() {
    const recordsPerPage = document.getElementById('recordsPerPage').value;
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('records_per_page', recordsPerPage);
    urlParams.set('page', '1'); // Reset to page 1 when changing records per page
    window.location.search = urlParams.toString();
  }


// Handle search
function searchTable() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let table = document.getElementById("TableBody");
    let rows = table.getElementsByTagName("tr");
    for (let i = 0; i < rows.length; i++) {
        let name = rows[i].getElementsByTagName("td")[0]?.innerText.toLowerCase();
        let role = rows[i].getElementsByTagName("td")[1]?.innerText.toLowerCase();
  
        if (name && role) {
            if (name.includes(input) || role.includes(input)) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
}

// Delete user
$(document).on("click", ".delete-btn", function () { 
    var userrole = $(this).data("role"); 
    var userPhone = $(this).data("phone"); 
    console.log("Deleting User Phone:", userPhone, userrole);

    // Show the delete confirmation modal
    $("#deleteConfirmModal").modal("show");

    // Set up the delete confirmation button click event
    $("#confirmDeleteBtn").off("click").on("click", function () {
        $.ajax({
            url: "../Components/delete.php",
            type: "POST",
            data: { phone: userPhone, role:userrole },
            success: function (response) {
                $("#deleteConfirmModal").modal("hide"); // Hide confirmation modal

                if (response.trim() === "success") {
                    $(".modal-ok").modal("show"); // Show success modal
                    setTimeout(function () {
                        $(".modal-ok").modal("hide");
                        location.reload();
                    }, 2000);
                } else {
                    $(".modal-fail").modal("show"); // Show failure modal
                    setTimeout(function () {
                        $(".modal-fail").modal("hide");
                    }, 2000);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                console.log("XHR Response:", xhr.responseText);
                $(".modal-fail").modal("show"); // Show failure modal
                setTimeout(function () {
                    $(".modal-fail").modal("hide");
                }, 2000);
            }
        });
    });
});


// Edit user
function editUser(userId) {
    $.ajax({
        url: '../Components/edit.php',
        type: 'GET',
        data: {
            user_id: userId
        },
        success: function(response) {
            $('#editUserModal').html(response);
            $('#editUserModal').modal('show');
        },
        error: function() {
            alert('Error in the request');
        }
    });
}

// Load all users initially

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