<?php 

include './includes/conn.php';
include './includes/header.php'; 
global $mysqli;
?>

<div class="landingPage">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="overlay"></div>
        <div class="landingPageContent">
          <video autoplay muted loop id="myVideo">
            <source src="./images/landingPage.mp4" type="video/mp4">
          </video>

          <div class="landingcontent">
            <h1 data-aos="slide-down">Learn anything from anywhere !</h1>
            <h5 data-aos="slide-down" data-aos-duration="4000">Don't limit yourself withing the 4 walls. </h5>
            <a href="http://localhost/Elearning/login.php" class="btn landingBtn" data-aos="slide-up">Join Us</a>
          </div>
        
        </div>
      </div>
    </div>
  </div>
</div>

<section class="teachers">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-5 col-md-5 col-sm-12">
        <img src="./images/homepageTeacher.jpg" alt="teacher" class="teacherImg"  data-aos="fade-right">
        <!-- <img src="./images/teacher.svg" alt="teacher" class="teacherImg"  data-aos="fade-right"> -->
      </div>
      <div class="col-lg-6 col-md-6 col-sm-12 mt-4 ml-4" data-aos="fade-left">
        <span>Teachers</span>
        <p>Don't let lack of physical classes discourage you.</p>
        <a class="btn teacherBtn" href="http://localhost/Elearning/login.js">Join Us</a>
      </div>
    </div>
  </div>
</section>

<section class="detailsSection">
  <?php
    $totalCourse = $mysqli->query("Select * from course");
    $totalStudents = $mysqli->query("Select * from user where user_type = 'student'");
    $totalTeacher = $mysqli->query("Select * from user where user_type = 'teacher'");
  ?>
  <h3 class="text-center pt-4 pb-4">Connect with us</h3>
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-4">

        <div class="courses" data-aos="fade-up">
        <i class="fas fa-book-open"></i>
          <?php echo $totalCourse->num_rows; ?> courses
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="teacher" data-aos="fade-up">
        <i class="fas fa-chalkboard-teacher"></i>
        <?php echo $totalTeacher->num_rows; ?> Teachers
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="student" data-aos="fade-up">
        <i class="fas fa-users"></i>
        <?php echo $totalStudents->num_rows; ?> Students
        </div>
      </div>
      <div class="btn detailsBtn" data-aos="fade-up">Connect with us</div>
    </div>
  </div>
</section>

<section class="students">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-5 col-md-5 col-sm-12 order-sm-1 order-2">
        <div class="content" data-aos="fade-right">
          <span>Students</span>
          <p>Don't let lack of physical classes discourage you.</p>
          <a href="http://localhost/Elearning/login.php" class="btn teacherBtn">Join Us</a>
        </div>
       
      </div>

      <div class="col-lg-6 col-md-6 col-sm-12 order-sm-2 order-1 ml-4">
        <img src="./images/students.jpg" alt="student" class="studentImg" data-aos="fade-left">
        <!-- <img src="./images/students.svg" alt="student" class="studentImg" data-aos="fade-left"> -->
      </div>
      
    </div>
  </div>
</section>

<section class="features">
  <h3 class="text-center">Don't limit yourself to School</h3>
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-6 col-sm-12 ">
        <div class="content" data-aos="zoom-in">
        <i class="fab fa-leanpub"></i>
          <p>Learn At your Own Pace</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 col-sm-12 ">
        <div class="content" data-aos="zoom-in">
        <i class="fas fa-globe"></i>
          <p>Learn from anywhere in the world</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="content" data-aos="zoom-in">
        <i class="fas fa-biking"></i>
          <p>Pick up a new interest, learn from the best</p>
        </div>
      </div>
    </div>
  </div>
</section>


<?php include './includes/footer.php'; ?>