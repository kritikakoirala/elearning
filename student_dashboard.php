<?php

  include './includes/conn.php';
  include './includes/header.php'; 
  global $mysqli;

  if(($_SESSION['user']['USER_TYPE'])=='student'){
    $userId = $_SESSION['user']['USER_ID'];
    $bookingStudentQuery = $mysqli->query("select * from book_tutor, student, user where book_tutor.student_id = student.Student_id AND student.User_id = user.User_id AND user.User_id = $userId AND book_tutor.Booking_Status NOT IN ('pending')");
    $bookingStudentResult = $bookingStudentQuery->fetch_assoc();
    if($bookingStudentQuery->num_rows>0){
      $status = $bookingStudentResult['Booking_Status'];
    }
    
  }
?>

<!-- <div class="notify" id="notify">
  <?php 
  
    if($status=='Verified'){
      ?>
        <p class = "alert alert-success alert-dismissible py-4 font-weight-bold my-4">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <span>Your resquested class was verified. Please note the time for your class as scheduled</span>
      </p> 
      <?php
    }else{
      ?>
        <p class = "alert alert-danger alert-dismissible py-4 font-weight-bold my-4">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <span>Your resquested class was not confirmed and cancelled by the tutor due to some reason. Contact your tutor or try again with different class schedule.</span>
      </p> 
      <?php
    }
  ?>
</div> -->

<div class="studentDashboard">
  <?php include './includes/topNav.php'?>

  <div class="currentClasses my-4" id = 'currentClasses'>

    <div class="container-fluid">
    
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <h5 class="text-capitalize profileHeading">My Class Schedules</h5>
          <span class=" note">(Verified by the tutors)</span>
          <div class="table-responsive">
            <table id ='dataTable' class="table table-bordered table-striped table-hover mt-4 mb-4">
              <thead>
                <tr>
                  <th>Course Title</th>
                  <th>Course Scheduled Date</th>
                  <th>Class Time</th>
                  <th>Tutor Name Name</th>
                  
                </tr>
              </thead>
              <tbody>
                <?php
                  $getClasses = $mysqli->query("select * from book_tutor, tutor_schedule, student, course, tutor, user where book_tutor.Course_id = course.Course_id AND book_tutor.Tutor_id = tutor.Tutor_id AND tutor.User_id = user.User_id AND book_tutor.student_id = student.Student_id AND tutor_schedule.Schedule_id = book_tutor.Schedule_id AND student.User_id = $userId AND book_tutor.Booking_Status = 'verified'");
                  $classesResult = $getClasses->fetch_all(MYSQLI_ASSOC);
                  if($getClasses->num_rows>0){
                    foreach($classesResult as $row){
                    ?>
                  
                    <tr>
                      <td><?php echo $row['Course_Title']; ?></td>
                      <td><?php echo $row['Schedule_Day']; ?></td>
                      <td><?php echo $row['Start_Time'] .' - '. $row['End_Time']; ?></td>
                      <td><?php echo $row['First_Name'].' '. $row['Middle_Name'].' '.$row['Last_Name']; ?></td>
                      
                    </tr>
                
                    <?php
                    }
                  }else{
                    ?>
                    <p class = "alert alert-info alert-dismissible py-4 font-weight-bold my-4">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      
                      <span>Sorry, you have no booked classes yet!.</span>
                      <a href="/Elearning/search_course.php" class="font-weight-bold ml-3 mt-4">Search Courses</a>
                    </p> 
                      
                    
                    <?php
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="currentEnrolledClass my-4" id = 'currentEnrolledClass'>
    <p class="heading py-4">These are the classes you are currently enrolled in.
      
    </p>
    <div class="container-fluid">
      <div class="row">
        
      <div class="col-lg-12 col-md-12 col-sm-12">
      <table id ='dataTable' class="table table-bordered mt-4">
        <thead>
          <tr>
            <th>Enrolled Date</th>
            <th>Course</th>
            <th>Tutor</th>
            <th>Enroll Status</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
        <?php
            $getEnroll= $mysqli->query("select * from enroll, student, course, tutor, user where enroll.Course_id = course.Course_id AND enroll.Tutor_id = tutor.Tutor_id AND tutor.User_id = user.User_id AND enroll.student_id = student.Student_id AND student.User_id = $userId");
            $enrollResult = $getEnroll->fetch_all(MYSQLI_ASSOC);
            if($getEnroll->num_rows>0){
              foreach($enrollResult as $row){
                ?>
                  <tr>
                    <td><?php echo $row['Course_id'];?></td>
                    <td><?php echo $row['Course_Title'];?></td>
                    <td><?php echo $row['First_Name'].' '. $row['Middle_Name'].' '.$row['Last_Name']; ?></td>
                    <td><?php echo $row['Enroll_Status'];?></td>
                    <td><a href="http://localhost/Elearning/course_detail.php?id=<?php echo $row['Course_id'];?>">View Details</a></td>
                  </tr>
                <?php
              }
            }
          ?>
        </tbody>
      </table>
      </div>
      </div>
    </div>
  </div>
</div>
<?php
  include './includes/footer.php';
?>