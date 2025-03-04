
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="/education/Admin/img/AdminLTELogo.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Edulearn</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="menu"
              data-accordion="false"
            >
            <!-- For Admin & Super Admin Started  -->
<?php
if($_SESSION['role_id']==2 || $_SESSION['role_id']==1)
{
?>
              <li class="nav-item">
                <a href="/education/Admin/index.php" class="nav-link">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                  Dashboard
                  </p>
                </a>
               
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-people"></i>
                  <p>
                  Users
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                  <a href="/education/Admin/User" class="nav-link">
                  <i class="nav-icon bi bi-people"></i>
                  <p>
                  Users
                  </p>
                </a>
                  </li>
                  <li class="nav-item">
                  <a href="/education/Admin/Student" class="nav-link">
                  <i class="nav-icon bi bi-person "></i>
                  <p>
                   Student 
                  </p>
                </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="/education/Admin/Teacher" class="nav-link">
                  <i class="nav-icon bi bi-person-circle"></i>
                  <p>
                   Employee
                  </p>
                </a>
               
              </li>
              
              
              
              <li class="nav-item">
                <a href="/education/Admin/Course" class="nav-link">
                  <i class="nav-icon bi bi-book"></i>
                  <p>
                   Course 
                  </p>
                </a>
              </li>
             
              <li class="nav-item">
                <a href="/education/Admin/Attendance" class="nav-link">
                  <i class="nav-icon bi bi-table"></i>
                  <p>
                  Attendance
                  </p>
                </a>
              </li>
             
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-people"></i>
                  <p>
                  Report
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                <a href="/education/Admin/Batch" class="nav-link">
                  <i class="nav-icon bi bi-stack"></i>
                  <p>
                    Batch
                  </p>
                </a>
               
              </li>
                 
                </ul>
              </li>
              <?php
} else if( $_SESSION['role_id']==3)

{
?>
<li class="nav-item">
                <a href="/education/Admin/index.php" class="nav-link">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                  Dashboard
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/education/Admin/Student" class="nav-link">
                  <i class="nav-icon bi bi-person "></i>
                  <p>
                   Student 
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/education/Admin/Attendance" class="nav-link">
                  <i class="nav-icon bi bi-table"></i>
                  <p>
                  Attendance
                  </p>
                </a>
              </li>
<?php
}
else if ( $_SESSION['role_id']==4)
{
?>
      <!-- For Student  -->
<li class="nav-item">
                <a href="/education/Admin/index.php" class="nav-link">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                  Dashboard
                  </p>
                </a>
              </li>
            
              <li class="nav-item">
                <a href="/education/Admin/Attendance" class="nav-link">
                  <i class="nav-icon bi bi-table"></i>
                  <p>
                  Attendance
                  </p>
                </a>
              </li>
<?php
}

?>
            
                </ul>
              </li>
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>