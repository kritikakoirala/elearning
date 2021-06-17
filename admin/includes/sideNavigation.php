<?php session_start(); ?>


  <nav id="sidebar" class="navbar">
    <div class="sideHeader">
    <i class="fas fa-user-circle fa-4x mb-4"></i>
    <?php
      if(isset($_SESSION['user'])){?>
        <h4><?php echo $_SESSION['user']['USERNAME']; ?></h4>
        
        <?php
      }
    ?>
    </div>
    
    <ul class="navbar-nav">
      <li class="nav-item">
      <i class="fas fa-tachometer-alt"></i>
        <a class="nav-link" href="http://localhost/Elearning/admin/index.php"> Dashboard</a>
      </li>
      <li class="nav-item"> 
      <i class="fas fa-comments"></i>
        <a class="nav-link" href="http://localhost/Elearning/admin/all_reviews.php">  Reviews</a>
      </li>
      <li class="nav-item">
      <i class="fas fa-book-reader"></i>
        <a class="nav-link" href="http://localhost/Elearning/admin/students.php">  Students</a>
      </li>
      <li class="nav-item">
      <i class="fas fa-users"></i> 
        <a class="nav-link" href="http://localhost/Elearning/admin/tutors.php"> Tutors</a>
      </li>
      <li class="nav-item">
      <i class="fas fa-book-open"></i>
        <a class="nav-link" href="http://localhost/Elearning/admin/all_courses.php">  Courses</a>
      </li>
      <li>
        
        <a href="#reportSubMenu" data-toggle = "collapse" aria-expanded="false" class="dropdown-toggle nav-link"><i class="fa fa-folder-open pr-2"></i>  Reports</a>
      
        <ul class="collapse list-unstyled" id="reportSubMenu">
            <li class="nav-item"><i class="fa fa-eye"></i>
                  <a class="nav-link" href="http://localhost/Elearning/admin/reports/student_reports.php">
                     Student Report</a>
            </li>
            <li class="nav-item"><i class="fa fa-cart-plus">
                  </i>
                <a class="nav-link" href="http://localhost/Elearning/admin/reports/tutor_reports.php">Tutor Report</a>
            </li>
            <li class="nav-item"><i class="fa fa-plus-square"></i>
                <a class="nav-link" href="http://localhost/Elearning/admin/reports/course_reports.php">
                  Course Report</a>
            </li>
            
          
        </ul>
    </li>
    </ul>

  </nav>
  