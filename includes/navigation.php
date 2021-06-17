<?php

  ob_start();

  global $mysqli;
  session_start();


if(isset($_SESSION['user'])){
  $userId = $_SESSION['user']['USER_ID'];
  // echo $_SESSION['user']['USER_TYPE'];

  // if(($_SESSION['user']['USER_TYPE'])=='teacher'){
  

  //   $bookingQuery = $mysqli->query("select * from book_tutor, tutor, user where book_tutor.Tutor_id = tutor.Tutor_id AND tutor.User_id = user.User_id AND user.User_id = $userId AND book_tutor.Booking_Status = 'pending'");
    
  // }

  // if(($_SESSION['user']['USER_TYPE'])=='student'){
  //   $bookingStudentQuery = $mysqli->query("select * from book_tutor, booking_notification, student where book_tutor.bookTutor_id = booking_notification.bookTutor_id AND book_tutor.student_id = student.Student_id AND student.User_id = $userId");
  //   $bookingStudentResult = $bookingStudentQuery->fetch_assoc();
  // }

}

?>

<nav class="navbar navbar-expand-lg">
  <a class="navbar-brand" href="#">Tutor To You</a>
  <form method="get" action="search.php"  class="searchForm form-inline">

  <div class="form-group has-search">
    <button type="submit" name = "submit" class="fa fa-search form-control-feedback"></button>
    <input id="search"  type="text" class="form-control" placeholder="Search" name = "search">
  </div>
  </form>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <i class="fas fa-bars"></i>
  </button>
  
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav text-uppercase">

      
        <?php 
        if(isset($_SESSION['user'])){

          if(($_SESSION['user']['USER_TYPE'])=='teacher'){
            
            ?>
            <li class="nav-item"><a class="nav-link" href="/Elearning/teacher_dashboard.php">Dashboard</a>  </li>
            <li class="nav-item">
            <a href="/Elearning/profile.php" class="nav-link profileLink"><?php echo $_SESSION['user']['USERNAME']; ?></a>
         </li>
            <?php
          }elseif(($_SESSION['user']['USER_TYPE'])=='student'){
         
            ?>
            <li class="nav-item"><a class="nav-link" href="/Elearning/student_dashboard.php">Dashboard</a></li>
            <li class="nav-item">
            <a href="/Elearning/student_profile.php" class="nav-link profileLink"><?php echo $_SESSION['user']['USERNAME']; ?></a>
         </li>
            <li class="nav-item"><a href='../Elearning/student_notification.php' class="nav-link">Notifications</a></li>
          <?php
          }
        }
        
        ?>
        
        <li class="nav-item"><a href="/Elearning/search_course.php" class="nav-link">Courses</a></li>
      
    
      <?php 
        if(isset($_SESSION['user'])){?>

          <li class="nav-item">
            <a class="nav-link" href="/Elearning/meetings.php">Meetings</a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="/Elearning/logout.php">Logout</a>
          </li>
        <?php
        }else{?>
          <li class="nav-item">
            <a class="nav-link" href="/Elearning/login.php">Login</a>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-toggle="modal"data-target="#registerModal">Register</button>
          </li>
         
        <?php
        }
      ?>
        
    </ul>
  
</nav>



<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Want to connect with us?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="modalteacher">
          <p>Are you a Teacher?</p>
          <a href="/Elearning/teacher_signup.php" >Register Here</a>
        </div>
        <div class="modalstudent">
          <p>Are you a Student?</p>
          <a href="/Elearning/student_signup.php">Register Here</a>
        </div>
      </div>
      <div class="modal-footer">
        <p>Already Have an Account?</p>
        <a class="btn btn-secondary" href='/Elearning/login.php'>Login</a>
      </div>
    </div>
  </div>
</div>
