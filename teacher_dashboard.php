<?php 

include './includes/conn.php';
include './includes/header.php'; 
global $mysqli;

if(isset($_SESSION['user'])){
  $userId = $_SESSION['user']['USER_ID'];
}

$bookingQuery = $mysqli->query("select * from book_tutor, tutor, user where book_tutor.Tutor_id = tutor.Tutor_id AND tutor.User_id = user.User_id AND user.User_id = $userId AND book_tutor.Booking_Status = 'pending'");
$bookingResult = $bookingQuery->fetch_assoc();

// first time in web application

$getDateQuery = $mysqli->query("select * from user where user_id = $userId");
$dateResult = $getDateQuery->fetch_assoc();

$createdDate = $dateResult['created_date'];

$onedaylater = date('Y-m-d H:i:s', strtotime($createdDate . ' +1 day'));
// echo $tomorrow;
$currentDate = date('Y-m-d H:i:s');
?>


<div class="teacher_dashboard">
<div class="alertMessages mt-4 mb-4">
  <?php
      
      if($currentDate<$onedaylater){?>
        <p class = "alert alert-success alert-dismissible py-2 font-weight-bold mt-4" data-aos='slide-down'>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        Welcome to Tutors To you! 
        
       
        </p> 
        <p class = "alert alert-info alert-dismissible py-2 font-weight-bold mt-4" data-aos='slide-down'>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      
        
        Start by completing your profile detais. <a href="http://localhost/Elearning/profile.php">MY PROFILE</a>
        <br>
        <br>
        Users connect with someone with their skills and experience on display.
        </p> 

        <p class = "alert alert-info alert-dismissible py-2 font-weight-bold mt-4" data-aos='slide-down'>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      
        
        You can create courses only after 24 hours of registering.
        <br>
        <br>
        Please wait for it. Thank you!
        </p> 
      <?php
      }
  ?>
</div>
  <?php
  
  if(isset($_SESSION['classBooked'])){
    ?>
     <p class = "alert alert-success alert-dismissible py-4 font-weight-bold my-4">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <?php echo $_SESSION['classBooked']; ?>
      </p> 
    <?php
    unset($_SESSION['classBooked']);
  }else if(isset($_SESSION['classCancelled'])){
    ?>
     <p class = "alert alert-success alert-dismissible py-4 font-weight-bold my-4">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <?php echo $_SESSION['classCancelled']; ?>
      </p> 
    <?php
    unset($_SESSION['classCancelled']);
  }else if(isset($_SESSION['reminder'])){
    ?>
     <p class = "alert alert-success alert-dismissible py-4 font-weight-bold my-4">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <?php echo $_SESSION['reminder']; ?>
      </p> 
    <?php
    unset($_SESSION['reminder']);
  }

  include './includes/topNav.php';

  if(isset($_SESSION['success_courseCreated'])){
    ?>
     <p class = "alert alert-success alert-dismissible py-4 font-weight-bold mt-4">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <?php echo $_SESSION['success_courseCreated']; ?>
      </p> 
    <?php
    unset($_SESSION['success_courseCreated']);
  }
?>



  <div class="briefDetails mt-4 pt-4">
    <div class="container">
      <div class="row">
        <?php
          $countStudents = $mysqli->query("select DISTINCT Student_id from book_tutor, tutor where book_tutor.Tutor_id = tutor.Tutor_id AND tutor.User_id = $userId AND book_tutor.Booking_Status = 'Verified'");
          
          
          ?>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <div class="info text-center" data-aos='slide-up'>
              <i class="fas fa-users fa-2x mb-4"></i>
              <?php
              if($countStudents->num_rows>0 ){
                ?>
                <p><?php echo $countStudents->num_rows; ?> Students</p>
                <?php 
              }else{?>
                <p>0 Students</p>
                <?php
              }
                ?>
              </div>
            </div>
          <?php
          $countCourse = $mysqli->query("select * from course where User_id = $userId");
          
          ?>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <div class="info text-center" data-aos='slide-up'>
              <i class="fas fa-users fa-2x mb-4"></i>
              <?php
              if($countCourse->num_rows>0 ){
                ?>
                <p><?php echo $countCourse->num_rows; ?> Course</p>
                <?php 
              }else{?>
                <p>0 Courses</p>
                <?php
              }
                ?>
              </div>
            </div>
          <?php
            

          $countReview = $mysqli->query("select * from reviews, course, tutor where reviews.Course_id = course.Course_id AND course.User_id = $userId");
         
          ?>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <div class="info text-center" data-aos='slide-up'>
              <i class="fas fa-users fa-2x mb-4"></i>
              <?php
              if($countReview->num_rows>0 ){
                ?>
                <p><?php echo $countReview->num_rows; ?> Review</p>
                <?php 
              }else{?>
                <p>0 Reviews</p>
                <?php
              }
                ?>
              </div>
            </div>
          
      </div>
    </div>
  </div>

  <div id="chart-container">
    <div class="container">
      <div class="row ">
        <div class="col-lg-8 col-md-12 col-sm-12">
          <h6 class="text-center mb-4">Chart showing the most popular course (based on rating)</h6>
          <canvas id="ratingGraph"></canvas>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
          <h6 class="text-center mb-4">Latest Reviews</h6>
          <?php
            $query = ("select * from reviews, course where reviews.Course_id = course.Course_id AND course.User_id = $userId ORDER BY Review_Date DESC LIMIT 3");
            $reviewQuery = $mysqli->query($query);
            $reviewResult = $reviewQuery->fetch_all(MYSQLI_ASSOC);
            if($reviewQuery->num_rows>0){
              foreach($reviewResult as $row){
                ?>
                  <div class="comment" data-aos='slide-up'>
                  <i class="fas fa-comments fa-2x  mr-4"></i> <?php echo $row['Review'];?>
                  </div>
                <?php
              }
              ?>
              <a href="../Elearning/all_reviews.php" class="reviewBtn" data-aos='slide-up'>All Reviews...</a>
              <?php
            }else{
              ?>
                <p class="text-center mt-4 pt-4">Sorry, you have no reviews yet!</p>
              <?php
            }
           
          
          ?>
        </div>
      </div>

      <div class="row ">
        <div class="col-lg-12 col-md-6 col-sm-12">
        <h6 class="text-center mb-4">Chart showing the month with most enrollment</h6>
          <canvas id="enrollGraph"></canvas> 
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12 col-md-6 col-sm-12">
        <h6 class="text-center mb-4">Chart showing the course with most enrollment</h6>
          <canvas id="courseGraph"></canvas> 
        </div>
      </div>
    </div>
    
  </div>


<?php
  if($bookingQuery->num_rows>0){
    ?>
      <div class="notification" id="notification">
        <p><?php echo $bookingQuery->num_rows;?> user has requested a booking session with you</p>
        <a href="../Elearning/booking_details.php" class="btn view">View Details</a>
      </div>
    <?php
  }
?>
  
</div>

<?php include './includes/footer.php'; ?>