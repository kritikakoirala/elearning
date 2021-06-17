<?php 

include './includes/conn.php';
include './includes/header.php'; 
global $mysqli;

  if(isset($_SESSION['user'])){
    $userId = $_SESSION['user']['USER_ID'];
    
  }
 
  $profileQuery = $mysqli->query("Select * from user, tutor where user.User_id = '$userId' AND user.User_id = tutor.User_Id");
  $profileResult = $profileQuery->fetch_assoc();

  $extraQuery = $mysqli->query("Select * from user, tutor where user.User_id = '$userId' AND user.User_id = tutor.User_Id and Skills ='' and Experience = '' and About_You = ''");

  $extraQueryResult = $extraQuery->fetch_assoc();

  if(isset($_POST['add_schedule'])){

    $date = date('y-m-d', strtotime($_POST['date']));
    $start = $_POST['start'];
    $end = $_POST['end'];
    $course = $_POST['selectCourses'];
    $getDay = date('l', strtotime($date));
  
    $getTutorId = $mysqli->query("select * from tutor where tutor.User_id = $userId");
    $tutorResult = $getTutorId->fetch_assoc();
  
    if($getTutorId->num_rows>0){
  
      $tutor = $tutorResult['Tutor_id'];
  
      $checkQuery = $mysqli->query("select * from tutor_schedule where Schedule_Date = '$date' AND Start_Time = '$start' AND End_Time='$end' AND Tutor_id = $tutor");
  
      if($checkQuery->num_rows==0){
        $insertSchedule = $mysqli->query("insert into tutor_schedule (Schedule_Date, Schedule_Day, Start_Time, End_Time, Tutor_id, Course_Id) VALUES ('$date', '$getDay', '$start', '$end', $tutor, $course)");
  
        if($insertSchedule){
          ?>
          <p class = "alert alert-success alert-dismissible py-3 font-weight-bold mt-4" data-aos='slide-down'>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
            You have successfully added a schedule for your course
          </p> 
    
        <?php
        }else{
          echo mysqli_error($mysqli);
        }
      }else{
        ?>
          <p class = "alert alert-danger alert-dismissible py-4 font-weight-bold mt-4" data-aos='slide-down'>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
            You already have a class on this day. Please check your timing.
          </p> 
        <?php
      }
  
    }
    
  }
 if($profileQuery->num_rows==1){
?> 

<div class="profile">

  <?php

  if(isset($_SESSION['profile_completed'])){
    ?>
    <p class = "alert alert-success alert-dismissible py-4 font-weight-bold my-4">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <?php echo $_SESSION['profile_completed']; ?>
      </p> 
    <?php
    unset($_SESSION['profile_completed']);
  }
  if(isset($_SESSION['profileUpdated'])){
    ?>
    <p class = "alert alert-success alert-dismissible py-4 font-weight-bold my-4">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <?php echo $_SESSION['profileUpdated']; ?>
      </p> 
    <?php
    unset($_SESSION['profileUpdated']);
  }

  include './includes/topNav.php';
  ?>
  
  <div class="topProfile">
    <h4 class="text-center py-4">Your Personal Details</h4>
    <?php
      if(!empty($profileResult['Profile_Image'])){
        ?>
          <img class="profileImg d-flex flex-row justify-content-center align-items-center" src="../Elearning/<?php echo $profileResult['Profile_Image'];?>" alt="profile">
        <?php
      }else{?>
        <i class='fas fa-user-circle mb-4 d-flex flex-row justify-content-center align-items-center'></i>
      <?php
      }
     
    ?>
    <div class="container mt-4">
      <div class="row justify-content-center">

        <div class="col-lg-6 col-md-6 col-sm-6 pl-0 my-4 d-flex flex-column justify-content-start align-items-center">
         
            <p><span class="font-weight-bold">First Name:</span>  <?php echo $profileResult['First_Name'];?></p>
            <p><span class="font-weight-bold">Middle Name:</span> <?php echo $profileResult['Middle_Name'];?></p>
            <p><span class="font-weight-bold">Last Name:</span> <?php echo $profileResult['Last_Name'];?></p>
            <p><span class="font-weight-bold">Username:</span> <?php echo $profileResult['username'];?></p>
        
        </div>
      
        
        <div class="col-lg-6 col-md-6 col-sm-6 pl-0 my-4 d-flex flex-column justify-content-center align-items-center">

            <p><span class="font-weight-bold">Email:</span> <?php echo $profileResult['Email'];?></p>
            
            <p><span class="font-weight-bold">Address:</span>  <?php echo $profileResult['Address'];?></p>
          
            <p><span class="font-weight-bold">Number:</span> <?php echo $profileResult['Number'];?></p>
            <p><span class="font-weight-bold">Gender:</span> <?php echo $profileResult['Gender'];?></p>
            <!-- <?php
          $nullQuery = $mysqli->query("select * from tutor where User_id = $userId AND Skills = '' OR Experience = '' OR About_You = '' OR Address = '' OR Number = '' OR Gender = ''");

          if($nullQuery->num_rows==1){
            ?>
              <a href="complete_teacher_profile.php" class="btn mt-4">Complete your Details</a>
            <?php
          }
        ?> -->
            
        </div>
        

        <a href="update_profile.php" class="btn mt-4">Update your Profile</a>
            


      </div>
        
    </div>
  </div>

  <div class="middleProfile">
    <h4 class="text-center py-4">Your Skills</h4>
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <?php
            
            if($extraQuery->num_rows==0){
              ?>
              <p><span class="font-weight-bold">Skills:</span>  <?php echo $profileResult['Skills'];?></p>
            <p><span class="font-weight-bold">Experience:</span>  <?php echo $profileResult['Experience'];?></p>
            <p><span class="font-weight-bold">Yourself:</span>  <?php echo $profileResult['About_You'];?></p>

            <a href="../Elearning/update_skills.php" class="btn mt-4">Update Your Details</a>
              <?php
            }else{
              ?>
                <div class="noResult d-flex flex-column justify-content-center align-items-center">
                  <p class="font-weight-bold text-center">You haven't added any skills yet. Adding skills make people interested in your course.</p>
                  <a href="../Elearning/complete_teacher_skills.php" class="btn">Add Skills</a>
                </div>
                
              <?php
            }
          ?>
            
            
        </div>
      </div>
    </div>
  </div>


  <div class="tutor_schedule">

    <?php

    $getSchedulesQuery = $mysqli->query("select * from tutor_schedule, tutor, course where tutor_schedule.Tutor_id = tutor.Tutor_id AND tutor.User_id = $userId AND tutor_schedule.Course_Id = course.Course_Id");

    if($getSchedulesQuery->num_rows<0){
      ?>
        <p class="font-weight-bold">Your have no available schedules yet!.</p>
        <span class="font-weight-bold mb-4">Add Your Schedules for your students to connect with you</span>

      <?php
    }
    else{
    ?>
      <div class="addSchedule" id="addSchedule">
        <h5 class="profileHeading">Add your schedules</h5>
        <button type="button" class="btn btn-primary" data-toggle="modal"     data-target="#scheduleModal">
            Add Schedule
        </button>
        <hr>
        
      
      <form method='POST' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          
        <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h6>Add your schedule</h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <label for="datepicker">Date</label>
                    <input type="text" class="form-control" id="datepicker" name="date" required>
                  </div>
                  <div class="form-group">
                    <label for="start">Start Time</label>
                    <input type="text" class="start_timepicker form-control" id="start" name="start" required>
                  </div>
                  <div class="form-group">
                    <label for="end">End Time</label>
                    <input type="text" class="end_timepicker form-control" id="end" name="end" required> 
                  </div>
                  
                  <div class="form-group">
                  <label for="course">Select the course</label>
                  <?php 
                    $getCourses = $mysqli->query("select * from course where User_Id = $userId");
                    $getCoursesResult = $getCourses->fetch_all(MYSQLI_ASSOC);
                    
                  ?>
                    <select name="selectCourses" class="form-control" id='course' required>
                    <option value="" selected>Select Course</option>
                    <?php
                       
                      if($getCourses->num_rows>0){
                        echo "yes";
                        foreach($getCoursesResult as $row){
                          ?>
                            <option value="<?php echo $row['Course_id']?>"><?php echo $row['Course_Title']?></option>
                          <?php
                        }
                      }else{
                        echo "noo";
                      }
                    ?>
                  </select>
                  </div>
                  
                  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary mr-3" data-dismiss="modal">Close</button>
                  <input type="submit" name="add_schedule" class="btn btn-primary" value="Add Schedule" id="addSchedule">
                </div>
              </div>
            </div>
        </div>
      </form>
      </div>

      <table id ='dataTable' class="table table-bordered mt-4">
        <thead>
          <tr>
          <th>Schedule Date</th>
          <th>Schedule Day</th>
          <th>Start Time</th>
          <th>End Time</th>
          <th>Course</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            
          
            $getSchedulesResult = $getSchedulesQuery->fetch_all(MYSQLI_ASSOC);

            if($getSchedulesQuery->num_rows>0){
              foreach($getSchedulesResult as $row){
                ?>
                  <tr>
                    <td><?php echo $row['Schedule_Date'];?></td>
                    <td><?php echo $row['Schedule_Day'];?></td>
                    <td><?php echo $row['Start_Time'];?></td>
                    <td><?php echo $row['End_Time'];?></td>
                    <td><?php echo $row['Course_Title'];?></td>
                  </tr>
                <?php
              }
            }
              
            
          ?>
        </tbody>
      </table>

      
    <?php 
      }
    ?>
  </div>
    
  </div>
    
</div>

<?php

}
include './includes/footer.php';
?>