<?php
  if(isset($_SESSION['user'])){
    $userId = $_SESSION['user']['USER_ID'];
    $usertype = $_SESSION['user']['USER_TYPE'];
  }

  $getDateQuery = $mysqli->query("select * from user where user_id = $userId");
  $dateResult = $getDateQuery->fetch_assoc();
  $createdDate = $dateResult['created_date'];
  $onedaylater = date('Y-m-d H:i:s', strtotime($createdDate . ' +1 day'));
  $currentDate = date('Y-m-d H:i:s');
  

  $getNotification = $mysqli->query("select * from book_tutor, tutor where book_tutor.Tutor_id = tutor.Tutor_id AND tutor.User_Id = $userId AND Booking_Status = 'pending'");
  // var_dump($getNotification->fetch_assoc());
?>
  
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="topButtons">
          <?php
            if($usertype=='teacher'){?>
              <div class="infoBtn">
                <a href = '../Elearning/students.php' class="btn studentBtn">Students</a>
                <a href = '../Elearning/courses.php' class="btn courseBtn">Courses</a>
                <a href="../Elearning/teacher_classes.php" class="btn">Classes</a>
                
              </div>

              <?php
                if($getNotification->num_rows>0){
                  ?>
                  <div class="notifications">
                    <a href="http://localhost/Elearning/teacher_dashboard.php#notification">You have <?php echo $getNotification->num_rows ?> notification</a>
                  </div>
                  <?php
                }
              ?>
          
              <?php
                if($currentDate>$onedaylater){?>
                  <a href='./add_new_course.php' class="btn newCourseBtn">Add New Course</a>
                <?php
                }

            }else if($usertype=='student'){?>
              <a href = '#currentClasses' class="btn">My Classes</a>
            <a href = "#currentEnrolledClass" class="btn">My Enrolled Classes</a>
            <a href="../Elearning/search_course.php" class="btn">Search for courses</a>
            <?php
            }
          ?>
        </div>
      </div>
    </div>
  </div>