<?php

include './includes/conn.php';
include './includes/header.php'; 

if(isset($_GET['id'])){
  $courseId = $_GET['id'];
}

if(isset($_SESSION['user'])){
  $userId = $_SESSION['user']['USER_ID'];
  $userType = $_SESSION['user']['USER_TYPE'];
}

// insert into enroll
$email = $message ="";

$emailErr = $messageErr = "";
if(isset($_POST['postReview'])){
  if(empty($_POST['email'])){
    $emailErr = 'Please enter your Email';
  }else{
    $email = e($_POST['email']);
  }
  if(empty($_POST['message'])){
    $messageErr = 'Please enter your review for this course';
  }else{
    $message = e($_POST['message']);
  }

  $rating = $_POST['hdnRateNumber'];
  $rating = $rating?$_POST['hdnRateNumber']:0;

  if(!$emailErr && !$messageErr ){
    if(isset($_SESSION['user'])){
      $reviewQuery = $mysqli->query("select * from reviews where Course_id = $courseId AND User_id = $userId AND Review = '$message'");
     
      if($reviewQuery->num_rows==0){
      
        $insertReviewQuery = $mysqli->query("INSERT into reviews (Course_id, User_id, Review, Rating,  Review_Date, Reply_To)
        Values ($courseId, $userId, '$message', $rating, CURRENT_TIMESTAMP, '')
        ");
        if($insertReviewQuery){?>
          <p class = "alert alert-success alert-dismissible py-4 font-weight-bold my-4">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <?php echo 'Your review has successfully posted' ?>
          </p> 

          <?php
        }else{
          echo mysqli_error($mysqli);
        }
      }
    }
    else{
      ?>
        <p class = "alert alert-danger alert-dismissible py-4 font-weight-bold my-4">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          Please login to leave a comment. <a href="http://localhost/Elearning/login.php">Login</a>
        </p> 
      <?php
    }

  }
}

if(isset($_POST['reply'])){
  if(empty($_POST['email'])){
    $emailErr = 'Please enter your Email';
  }else{
    $email = e($_POST['email']);
  }
  if(empty($_POST['message'])){
    $messageErr = 'Please enter your review for this course';
  }else{
    $message = e($_POST['message']);
  }

  $parentId = $_POST['parentReviewId'];

  if(!$emailErr && !$messageErr ){
    
      $reviewQuery = $mysqli->query("select * from reviews where Course_id = $courseId AND User_id = $userId AND Review = '$message'");
      var_dump($reviewQuery->num_rows);
      if($reviewQuery->num_rows==0){
      
        $insertReviewQuery = $mysqli->query("INSERT into reviews (Course_id, User_id, Review, Review_Date, Reply_To)
        Values ($courseId, $userId, '$message', CURRENT_TIMESTAMP, $parentId)
        ");
        if($insertReviewQuery){?>
          <p class = "alert alert-success alert-dismissible py-4 font-weight-bold my-4">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <?php echo 'Your review has successfully posted' ?>
          </p> 

          <?php
        }else{
          echo mysqli_error($mysqli);
        }
      }
  }
}

function e($val){
  return htmlEntities(trim($val), ENT_QUOTES);
}


$tutorProfileQuery = $mysqli->query("Select * from course, tutor, user where course.User_Id = tutor.User_id AND tutor.User_id = user.User_id AND course.Course_id = $courseId");
$tutorResult = $tutorProfileQuery->fetch_assoc();
$tutorId = $tutorResult['Tutor_id'];


if(isset($_SESSION['user'])){
  if($userType=='student'){
    $studentProfileQuery = $mysqli->query("Select * from user, student where user.User_id = student.User_Id AND student.user_id = $userId");
    $studentResult = $studentProfileQuery->fetch_assoc();
    
    $studentId = $studentResult['Student_id'];
  }
}

$getSingleCourseQuery = $mysqli->query("select * from course where Course_id = $courseId");
$courseResult = $getSingleCourseQuery->fetch_assoc();

?>
  <div class="course_details">
    <?php
      if(isset($_SESSION['tutorBooked'])){
       
        ?>
         <p class = "alert alert-success alert-dismissible py-4 font-weight-bold my-4">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <?php echo $_SESSION['tutorBooked']; ?>
          </p> 
        <?php
        unset($_SESSION['tutorBooked']);
      }else if(isset($_SESSION['error'])){
        
        ?>
         <p class = "alert alert-danger alert-dismissible py-4 font-weight-bold my-4">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <?php echo $_SESSION['error']; ?>
          </p> 
        <?php
        unset($_SESSION['error']);
      }
      if($getSingleCourseQuery->num_rows>0){
    ?>
    <div class="course_video">
      <video controls>
        <source src="<?php echo $courseResult['Sample_Video'];?>" >
       
      </video>
    </div>

    <div class="courseDetail">
      <h4 class="mb-3"><?php echo $courseResult['Course_Title']; ?></h4>
      <p class="desc mb-3 text-justify"><?php echo $courseResult['Course_Description']; ?></p>
      <p class="duration py-2"> <span class=" font-weight-bold"> Course Duration:</span> <?php echo $courseResult['Course_Duration']; ?></p>
      <p class="level  py-2"><span class=" font-weight-bold">Course Level:</span> <?php echo $courseResult['Course_Level']; ?></p>
      <p class="weeklyClass  py-2"><span class=" font-weight-bold">No of Weekly Classes:</span> <?php echo $courseResult['Course_Classes']; ?></p>
      <p class="classTime  py-2"><span class=" font-weight-bold">Time per Class:</span> <?php echo $courseResult['Time_Per_class']; ?></p>
      <p class="instruction  py-2"><span class=" font-weight-bold">Instruction Language:</span> <?php echo $courseResult['Course_Language']; ?></p>
      <p class="tags  py-2"><span class=" font-weight-bold">Tags:</span> <?php echo $courseResult['Tags']; ?></p>
      
      <?php
        $getRating = $mysqli->query("select  FORMAT(AVG(DISTINCT Rating), 2) as Rating from reviews where Course_id = $courseId Group By Course_id");
        if($getRating->num_rows>0){
          $getRatingResult = $getRating->fetch_assoc();
          ?>
            <span class="font-weight-bold">Rating:</span> <?php echo $getRatingResult['Rating']?> <i class="fas fa-star checked"></i>
          <?php
        }
      ?>

    <div class="courseLongDesc">
      <h4 class="mb-4">Course Description</h4>
      <p class="text-justify"> <?php if($courseResult['Long_Description']) echo $courseResult['Long_Description']; ?></p>
    </div>
      
    </div>
    <?php 
      } 
    ?>

    <div class="enrollCTA d-flex flex-md-row flex-sm-column flex-column justify-content-between align-items-center ">
     
      <p class="font-weight-bold text-uppercase mb-sm-3 mb-3" >Taught on Zoom</p>

      <div class="enrollBtns d-flex flex-column justify-content-center align-items-center" data-aos='fade-left'>
        <?php
        if(isset($_SESSION['user'])){
          if($userType=='student'){
            $enrollCheck = $mysqli->query("select * from enroll, user, student, tutor where Course_id = $courseId AND enroll.Student_id = student.Student_id AND student.User_id = $userId");

            $enrollResult = $enrollCheck->fetch_assoc();
          
          
            if($enrollCheck->num_rows>0){
              if($enrollResult['Enroll_Status'] == 'pending'){
                ?>
                <span class="note mb-4 font-weight-bold">You have yet to pay for this class. Please pay to be able to book for classes.</span>
                <a href='/Elearning/enroll.php?courseId=<?php echo $courseId;?>' class="btn enrollBtn mb-3">Pay Now</a>
                
                <?php
              }else{
                ?>
                <a class="btn scheduleBtn mb-3" href="#schedule">Book Now</a>
              <?php
              }
              
            }else{
              
              ?>
                <a href='/Elearning/enroll.php?courseId=<?php echo $courseId;?>' class="btn enrollBtn mb-3">Enroll Now</a>
              <?php
            }

          

          }
        }
        ?>
        
        <a class="btn scheduleBtn" href="#schedule">Check availlable Schedules</a>
      </div>
      
    </div>

    <?php

    if($tutorProfileQuery->num_rows>0){
      ?>
      <div class="teacherProfile">
        <div class="teacherDesc d-flex flex-column justify-content-center align-items-center mb-4">
          <i class="fas fa-user-circle fa-5x mb-4"></i>
          <h3><?php echo $tutorResult['First_Name'].' '.$tutorResult['Middle_Name'].' '. $tutorResult['Last_Name'];?></h3>
          
          <span class="mt-2 mb-4"><?php  echo $tutorResult['Skills'] ?></span>
        </div>

        <div class="briefInfo">
          <p class="text-justify"><?php  echo $tutorResult['About_You'] ?></p>
        </div>

        Message the Tutor
      </div>
      <?php
    }
      ?>

    <div class="schedules" id="schedule">
      <h4 class="mb-4 pb-4">Weekly Schedules</h4>
      <?php
    
        $schedule = $mysqli->query("select * from tutor_schedule where tutor_schedule.Tutor_id = $tutorId AND tutor_schedule.Course_Id = $courseId");
        
        $scheduleResult = $schedule->fetch_all(MYSQLI_ASSOC);

        if($schedule->num_rows>0){
          foreach($scheduleResult as $scheduleResult){
            $scheduleId = $scheduleResult['Schedule_id'];
            ?>
              <div class="scheduleBox">
             
                <p class="ml-4"> <span class="font-weight-bold mr-4"><?php echo $scheduleResult['Schedule_Day']?>:</span>  <?php echo $scheduleResult['Start_Time']. ' - ' . $scheduleResult['End_Time']?></p>
            
                <?php
                if(isset($_SESSION['user'])){
                  if($userType=='student'){
                    $checkBookingQuery = $mysqli->query("select * from book_tutor where course_id = $courseId AND tutor_id = $tutorId AND student_id = $studentId AND Schedule_id = $scheduleId");
                    
                    if($checkBookingQuery->num_rows==0){
                      
                      
                      $getDateQuery = $mysqli->query("select * from enroll where enroll.Student_id = $studentId");
                      $dateResult = $getDateQuery->fetch_assoc();
                    
                      $createdDate = $dateResult['Enrolled_Date'];

                      $onedaylater = date('Y-m-d H:i:s', strtotime($createdDate . ' +1 day'));
                      // echo $tomorrow;
                      $currentDate = date('Y-m-d H:i:s');
                      if($currentDate>$onedaylater){

                      ?>
                        <button class="btn enrollBtn mb-3" data-toggle="modal" data-target="#bookModal">Book This Time</button>
                      <?php
                      }else{
                        ?>
                          <p class = "alert alert-info alert-dismissible py-2 font-weight-bold mt-4" data-aos='slide-down'>
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                          You can only book a course 24 hours after enrolling in it.
                          <span>Please wait for it. Thank you!</span>
                                
                        </p> 
                        <?php
                      }
                    }else{
                      ?>
                        <input type="text" disabled value="Booking Request Sent" class="btn">
                      <?php
                    }
                  }
                }
                ?>
              </div>
            <?php
            }
              if(isset($_POST['bookCourse'])){

                $checkEnroll = $mysqli->query("select * from enroll, student where enroll.student_id = student.Student_id AND student.User_id = $userId AND enroll.Course_id = $courseId AND enroll.Enroll_Status = 'completed'");
                
                
                if($checkEnroll->num_rows>0){

                  $checkBookingQuery = $mysqli->query("select * from book_tutor where course_id = $courseId AND tutor_id = $tutorId AND student_id = $studentId");
                  
                  if($checkBookingQuery->num_rows==0){

                    $checkTime = $mysqli->query("select * from book_tutor where Schedule_id = $scheduleId AND student_id = $studentId");
                    if($checkTime->num_rows==0){
                    
                      $insertBookingQuery = $mysqli->query("insert into book_tutor (student_id, Tutor_id, Schedule_id, Course_id, Booking_status, Booking_Date) values ($studentId, $tutorId, $scheduleId ,$courseId, 'pending', CURRENT_TIMESTAMP)");
                      if($insertBookingQuery){
                        $_SESSION['tutorBooked'] = "Your request has been sent to your tutor. Please wait for their response. You will be notified either on your dashboard or via mail.";
                      }
                      else{
                        $_SESSION['error'] = "Something went wrong! Please try again";
                      }
                    }
                    else{?>
                      <p class = "alert alert-danger alert-dismissible py-4 font-weight-bold my-4">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                          You already have a class booked on the date you are current trying to book. Please choose another time to avoid clashing. 
                      </p> 
                <?php
                    }
                  }
                }else{
                  ?>
                <p class = "alert alert-danger alert-dismissible py-4 font-weight-bold my-4">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  You are not yet enrolled in this course. Please enroll first.
                </p> 

                  <?php
                }
              }
            ?>

            <div class="modal fade" id="bookModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <form action="http://localhost/Elearning/course_detail.php?id=<?php echo $courseId;?>" method='post'>
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Book this Course</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <p>Choosen Time: <?php echo $scheduleResult['Start_Time']. ' - ' . $scheduleResult['End_Time']?> </p>
                        <p>Choosen Day: <?php echo $scheduleResult['Schedule_Day']?> </p>
                        <p>Tutor Name: <?php echo $tutorResult['First_Name'].' '.$tutorResult['Middle_Name'].' '. $tutorResult['Last_Name']; ?></p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Book" name="bookCourse"/>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            <?php
          
        }else{
          ?>
            <p>Sorry, no schedules for this class is added. Please try again later.</p>
            
          <?php
          if(isset($_SESSION['user'])){
            if($_SESSION['user']['USER_TYPE']=='teacher'){
              ?>
              <a href="http://localhost/Elearning/profile.php#addSchedule" class="btn">Add Schedule for this course.</a>
            <?php
            }
          }
         
        }
      
      ?>
    </div>
    <div class="reviews">
      <div class="panel-group">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title mb-4">
              <a data-toggle="collapse" >Leave a Review</a>
            </h4>
            
          </div>
          <div id="collapse1" class="">
            
            <form method="POST" action="../Elearning/course_detail.php?id=<?php echo $courseId?>">
              <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control" type="email" name="email" >
              </div>
                <span class="errorMsg">
                  <?php  echo $emailErr ?>
                </span>
              
              <div class="form-group">
                <label for="messgae">Your Review</label>
                <textarea name="message" id="messgae" cols="20" rows="5" class="form-control"></textarea>
              </div>

                <span class="errorMsg">
                  <?php  echo $messageErr ?>
                </span>
              <div class="personal-rating">
                                            
                <h5 class = "mt-4 mb-4" >Rate the Product</h5>
                <div class="rating mb-3">
                    <i class="fa fa-star fa-1x"></i>
                    <i class="fa fa-star fa-1x"></i>
                    <i class="fa fa-star fa-1x"></i>
                    <i class="fa fa-star fa-1x"></i>
                    <i class="fa fa-star fa-1x"></i>
                </div>
                    
              </div>
              <input type="hidden" name="hdnRateNumber" id="hdnRateNumber">
              
              <input type="submit" name='postReview' class="btn my-4">
              <p class="note font-weight-bold my-4">Don't worry, your email will not be public.</p>
            </form>

            <div class="showReviews">
              <h5 class="my-4">All Comments</h5>
              <?php
              
                $review = $mysqli->query("select * from reviews, user, student where Course_Id = $courseId AND reviews.User_id = student.User_id AND student.User_id = user.User_id AND Reply_To = 0");
                $reviewResult = $review->fetch_all(MYSQLI_ASSOC);
                
                if($review->num_rows>0){
                  foreach($reviewResult as $row){
                    $reviewId = $row['ReviewId']
                    ?>
                    <div class="review d-flex flex-row align-items-center">
                      <div class="be-img-comment">	
                        <?php
                          if(!empty($row['Profile_Image'])){
                            ?>
                              <img class="proImg" src="../Elearning/<?php echo $row['Profile_Image'];?>" alt="profile">
                            <?php
                          }else{?>
                            <i class='fas fa-user-circle mb-4 fa-3x profileImg d-flex flex-row justify-content-center align-items-center'></i>
                          <?php
                          }
                        ?>
                      </div>
                      <div class="be-comment-content">
                        <input type="hidden" name="parentId" id="parentId" value="<?php echo $reviewId;?>">
                        
                        <span class="be-comment-name font-weight-bold"><?php echo $row['username'];?></span>
                          
                        <span class="be-comment-time">
                            <i class="fa fa-clock-o"></i>
                            <span class="font-weight-bold"><?php echo $row['Review_Date'];?></span>
                        </span>
                          <p class="be-comment-text">
                            <?php echo $row['Review']; ?>
                            <br>
                            <?php
                              if(isset($_SESSION['user'])){
                                ?>
                                  <button class="reply">Reply</button>
                                <?php
                              }

                              $replyReview = $mysqli->query("select * from reviews, user, student where Course_Id = $courseId AND reviews.User_id = student.User_id AND student.User_id = user.User_id AND Reply_To = $reviewId");
                              $replyreviewResult = $replyReview->fetch_all(MYSQLI_ASSOC);
                
                              if($replyReview->num_rows>0){
                                
                                foreach($replyreviewResult as $row){
                                  $replyReviewId = $row['ReviewId']
                                  ?>
                                  <div class="review d-flex flex-row align-items-center">
                                    <div class="be-img-comment">	
                                      <?php
                                        if(!empty($row['Profile_Image'])){
                                          ?>
                                            <img class="proImg" src="../Elearning/<?php echo $row['Profile_Image'];?>" alt="profile">
                                          <?php
                                        }else{?>
                                          <i class='fas fa-user-circle mb-4 fa-3x profileImg d-flex flex-row justify-content-center align-items-center'></i>
                                        <?php
                                        }
                                      ?>
                                    </div>
                                    <div class="be-comment-content">
                                      <input type="hidden" name="parentId" id="parentId" value="<?php echo $replyReviewId;?>">
                                      
                                      <span class="be-comment-name font-weight-bold"><?php echo $row['username'];?></span>
                                        
                                      <span class="be-comment-time">
                                          <i class="fa fa-clock-o"></i>
                                          <span class="font-weight-bold"><?php echo $row['Review_Date'];?></span>
                                      </span>
                                      <p class="be-comment-text">
                                        <?php echo $row['Review']; ?>
                                        <br>
                                        <?php
                                          if(isset($_SESSION['user'])){
                                            ?>
                                              <button class="reply">Reply</button>
                                            <?php
                                          }
                                          ?>
                                      </p>
                                    </div>
                                  </div>
                                  <?php
                                }
                              }
                            ?>
                            
                          </p>
                          <div class="replyTo">
                            <form method="POST" action="../Elearning/course_detail.php?id=<?php echo $courseId?>">
                              <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" type="email" name="email" >
                              </div>
                              <span class="errorMsg">
                                <?php  echo $emailErr ?>
                              </span>
                            
                              <div class="form-group">
                                <label for="messgae">Your Review</label>
                                <textarea name="message" id="messgae" cols="20" rows="5" class="form-control"></textarea>
                              </div>

                              <span class="errorMsg">
                                <?php  echo $messageErr ?>
                              </span>
                              <input type="text" name="parentReviewId" id = "parentReviewId">
                              <input type="submit" name="reply" value="Reply">
                            </form>
                          </div>
                            
                      
                        </div>
                    </div>
                    
                    
                  <?php
                  }
                  
                }else{
                  ?>
                    <p>Sorry, there are no reviews on this course yet.</p>
                    <span>Be the first one to leave a comment.</span>
                  <?php
                }


              
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php 

// }
include './includes/footer.php'; ?>