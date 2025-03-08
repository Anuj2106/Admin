<?php
session_start();
include "../Components/connect.php";
include "../Components/header.php";
include "../Components/Topbar.php";
include "../Components/sidebar.php";

// Set how many records per page
$limit = isset($_GET['records_per_page']) ? intval($_GET['records_per_page']) : 10; // Number of records per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Get total records count
$total_query = "SELECT COUNT(*) as total FROM users";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_courses = $total_row['total'];
$total_pages = ceil($total_courses / $limit);

// Fetch users with LIMIT and OFFSET
$sql = "SELECT users.*, master_role.Role FROM users 
        INNER JOIN master_role ON users.role_id = master_role.role_id 
        LIMIT $limit OFFSET $offset"; // Fixed the variable here
$result = $conn->query($sql);
if (!$result) {
    echo "Query Failed: " . $sql . "<br>" . $conn->error;
}
$count = $offset + 1;
?>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Users</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User</li>
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
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="bi bi-person-fill-add"></i> Add User
                            </button>
                            <input type="text" id="searchInput" class="form-control w-25" onkeyup="searchTable()" placeholder="Search Name...">
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>S.No</th>
                                            <th>User</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="TableBody">
                                        <?php while ($row = $result->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $count++; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?php echo $row['image'] ?>" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                                                        <div class="ms-3">
                                                            <p class="fw-bold mb-1"><?php echo $row['user_name']; ?></p>
                                                            <p class="text-muted mb-0"><?php echo $row['user_email']; ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo $row['Role']; ?></td>
                                                <td>
                                                    <button class="btn btn-sm toggle-status <?php echo $row['status'] == 0 ? 'btn-success' : 'btn-danger'; ?>"
                                                            data-phone="<?php echo $row['user_phone']; ?>" data-status="<?php echo $row['status']; ?>" data-role="<?php echo $row['role_id']; ?>">
                                                        <p class="mb-0">
                                                            <?php echo $row['status'] == 0 ? 'Active' : 'Inactive'; ?>
                                                        </p>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm edit-btn btn-primary btn-rounded"
                                                            data-phone="<?php echo $row['user_phone'] ?>" data-role="<?php echo $row['role_id'] ?>">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger delete-btn" data-phone="<?php echo intval($row['user_phone']) ?>" data-role="<?php echo $row['role_id']; ?>">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-info view-btn" data-id="<?php echo intval($row['user_id']) ?>" data-role="<?php echo $row['role_id']; ?>" data-source="users">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <span>Showing <?= $result->num_rows; ?> User</span>
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
                    </div> <!-- End of .card -->
                </div> <!-- End of .col-12 -->
            </div> <!-- End of .row -->
        </div> <!-- End of .container-fluid -->
    </div> <!-- End of .app-content -->

    <?php include "../Components/modal.php"; ?>

</main>

<?php include "../Components/footer.php"; ?>
