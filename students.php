<?php
  include './includes/conn.php';
  include './includes/header.php';

  if(isset($_SESSION['user'])){
    $userId = $_SESSION['user']['USER_ID'];
  }

  $getStudents = $mysqli->query("select * from book_tutor, tutor_schedule,  tutor, student, user, course where book_tutor.Tutor_id = tutor.Tutor_id AND book_tutor.student_id = student.Student_id AND book_tutor.Course_id = course.Course_id AND tutor_schedule.Schedule_id = book_tutor.Schedule_id  AND student.User_id = user.User_id AND Tutor.User_id = $userId AND book_tutor.Booking_Status = 'Verified'");
  $getStudentsResult = $getStudents->fetch_all(MYSQLI_ASSOC)
?>

  <div class="studentList">
    <div class="container-fluid">
    <?php include './includes/topNav.php'; ?>
      <div class="row">
        
          <?php
            if($getStudents->num_rows>0){
              foreach($getStudentsResult as $row){
                ?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <div class="list" data-aos='slide-up'>
                    <div class="profileImage">
                      <?php
                        if(!empty($row['Profile_Image'])){
                          ?>
                            <img class="profileImg d-flex flex-row justify-content-center align-items-center" src="../Elearning/<?php echo $row['Profile_Image'];?>" alt="profile">
                          <?php
                        }else{?>
                          <i class='fas fa-user-circle fa-2x mb-4 d-flex flex-row justify-content-center align-items-center'></i>
                        <?php
                        }
                      ?>
                    </div>

                    <div class="studentDetail mt-3 pt-3 d-flex flex-column justify-content-center align-items-center">
                      <p><?php echo $row['First_Name'] . ' ' .$row['Middle_Name'] . ' ' . $row['Last_Name']?></p>
                      <p><?php echo $row['Email'];?></p>
                      <p><?php echo $row['Gender'];?></p>
                    </div>

                    <div class="courseDetails mt-3 pt-3">
                      <p><span class="font-weight-bold">Enrolled In: </span> <?php echo $row['Course_Title']?></p>
                      <p><span class="font-weight-bold">Class Schedule : </span> <?php echo $row['Schedule_Day']?></p>
                      <p><span class="font-weight-bold">Class Timing</span> <?php echo $row['Start_Time'] .' - '. $row['End_Time']?></p>
                    </div>
                  </div>
                  </div>
                <?php
              }
            }else{
              include_once './no_result.php';
              
              showError('students', 'You should create a course first! Only then you can have students enrolling in your class');
            }
          ?>
        
      </div>
    </div>
  </div>

<?php
  include './includes/footer.php';
?>