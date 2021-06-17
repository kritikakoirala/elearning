<?php
include './includes/conn.php';
include './includes/header.php'; 

  if(isset($_SESSION['user'])){

    $userId = $_SESSION['user']['USER_ID'];
    $usertype = $_SESSION['user']['USER_TYPE'];
    
    ?>
      <div class="meetingsDashboard">
        <div class="container-fluid">
          <?php
            if($usertype=='teacher'){
              include './includes/topNav.php'; 
            }
          ?>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <h5 class="text-capitalize profileHeading">Meeting Information</h5>
                <div class="table-responsive">
                  <table id ='dataTable' class="table table-bordered table-striped table-hover mt-4 mb-4">
                    <thead>
                      <tr>
                        <th>Course Title</th>
                        <th>Course Scheduled Date</th>
                        <th>Class Time</th>
                        <th>Student Name</th>
                        <th>Meeting Link Sent on</th>
                        <th>Send Meeting Link</th>
                      
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                        if(($_SESSION['user']['USER_TYPE'])=='student'){
                          $tutorMeetings = $mysqli->query("select * from meetinginfo, book_tutor, tutor_schedule, student, tutor, course, user where meetinginfo.bookTutor_id = book_tutor.bookTutor_id AND tutor_schedule.Schedule_id = book_tutor.Schedule_id AND book_tutor.student_id = student.Student_id AND book_tutor.Course_id = course.Course_id AND book_tutor.Tutor_id = tutor.Tutor_id AND tutor.User_id = user.User_id AND student.User_id = $userId ORDER BY meetingLink_sent DESC");
                          $tutorMeetingsResult = $tutorMeetings->fetch_all(MYSQLI_ASSOC);
                          if($tutorMeetings->num_rows>0){
                            foreach($tutorMeetingsResult as $row){
                              ?>
                              <tr>
                                <td><?php echo $row['Course_Title'];?></td>
                                <td><?php echo $row['Schedule_Day'];?></td>
                                <td><?php echo $row['Start_Time'] . ' - '.$row['End_Time'];?></td>
                                <td><?php echo $row['First_Name'].' ' .$row['Middle_Name'].' '. $row['Last_Name'];?></td>
                                <td> <?php echo date('y-m-d g:i a', strtotime($row['meetingLink_sent']));?></td>
                                <td><a href="http://<?php echo $row['meetingLink'];?>" target="_blank"><?php echo $row['meetingLink'];?></a> </td>
                              </tr>
                              
                              <?php
                            }
                          }else{
                            include './no_result.php';
                            showError('', 'Sorry, you have no appointments yet. Your tutor will send you a class meeting link soon.')
                            ?>
                            
                              
                            <?php
                          }

                        
                          
                        }else if (($_SESSION['user']['USER_TYPE'])=='teacher'){
                          $studentMeetings = $mysqli->query("select * from  meetinginfo, book_tutor, tutor_schedule, student,tutor, user, course where meetinginfo.bookTutor_id = book_tutor.bookTutor_id AND book_tutor.Course_id = course.Course_id AND book_tutor.Tutor_id = tutor.Tutor_id  AND tutor_schedule.Schedule_id = book_tutor.Schedule_id AND book_tutor.student_Id = student.Student_id AND student.User_id = user.User_id AND tutor.User_id = $userId ORDER BY meetingLink_sent DESC");
                          $studentMeetingsResult = $studentMeetings->fetch_all(MYSQLI_ASSOC);

                          if($studentMeetings->num_rows>0){
                            foreach($studentMeetingsResult as $row){
                            ?>
                            <tr>
                                <td><?php echo $row['Course_Title'];?></td>
                                <td><?php echo $row['Schedule_Day'];?></td>
                                <td><?php echo $row['Start_Time'] . ' - '.$row['End_Time'];?></td>
                                <td><?php echo $row['First_Name'].' ' .$row['Middle_Name'].' '. $row['Last_Name'];?></td>
                                <td> <?php echo date('y-m-d g:i a', strtotime($row['meetingLink_sent']));?></td>
                                <td><a href="http://<?php echo $row['meetingLink'];?>" target="_blank"><?php echo $row['meetingLink'];?></a> </td>
                              </tr>
                              
                            <?php
                            }
                          }else{
                            ?>
                            <p class = "alert alert-info alert-dismissible py-4 font-weight-bold my-4">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              <span class="note">Sorry, you have no appointments yet. </span>
                              <a href="./teacher_classes.php" class="btn">Send a Class Link</a>
                            </p> 
                            
                              
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
  }


?>

<?php
include './includes/footer.php';
?>