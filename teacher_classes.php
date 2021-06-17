<?php 

include './includes/conn.php';
include './includes/header.php'; 
global $mysqli;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './vendor/phpmailer/phpmailer/src/Exception.php';
require './vendor/phpmailer/phpmailer/src/SMTP.php';
require './vendor/autoload.php';

if(isset($_SESSION['user'])){
  $userId = $_SESSION['user']['USER_ID'];
}

$getTimeQuery = $mysqli->query("select * from book_tutor, tutor_schedule, tutor, user where book_tutor.Tutor_id = tutor.Tutor_id AND tutor.User_id = user.User_id AND tutor_schedule.Schedule_id = book_tutor.Schedule_id AND Booking_Status = 'Verified'");

$result = $getTimeQuery->fetch_all(MYSQLI_ASSOC);

if($getTimeQuery->num_rows>0){
  foreach($result as $row){
    $date = $row['Schedule_Day'];
    $startTime = $row['Start_Time'];
    $fullDate = ($date.''.$startTime);
    $scheduledTime = date('Y-m-d H:i:s', strtotime('-1 day', strtotime($fullDate)));
  
    $currentTime = date('Y-m-d H:i:s');

    $allTutors = array($row['Email']);

  }
}

// insert into book_tutor query
$getClasses = $mysqli->query("select * from book_tutor, tutor_schedule,  student, course, tutor, user where book_tutor.Course_id = course.Course_id AND book_tutor.Tutor_id = tutor.Tutor_id  AND book_tutor.student_id = student.Student_id  AND tutor_schedule.Schedule_id = book_tutor.Schedule_id AND  student.User_id = user.User_id AND tutor.User_id = $userId AND book_tutor.Booking_Status = 'verified'");
$classesResult = $getClasses->fetch_all(MYSQLI_ASSOC);

$meetlink = $message = $email = "";
$meetErr = "";

if(isset($_POST['sendLink'])){
  if(empty($_POST['meetingLink'])){
   $meetErr = "Please enter the meeting link"; 
  }else{
    $meetlink=  e($_POST['meetingLink']);
  }

  if(!empty($_POST['message'])){
    $message = e($_POST['message']);
  }
 
  $email = e($_POST['email']);
  $bookingId = e($_POST['bookingId']);

  if(!$meetErr){
    $checkQuery = $mysqli->query("select * from meetinginfo WHERE meetingLink = '$meetlink' AND bookTutor_id = $bookingId");

    
    if($checkQuery->num_rows==0){
      $insertQuery = $mysqli->query("insert into meetinginfo (meetingWith, meetingLink,message, bookTutor_id, meetingLink_sent) VALUES ('$email', '$meetlink', '$message', $bookingId, CURRENT_TIMESTAMP)");
      if($insertQuery){
        $_SESSION['Success'] = "<p>Your account has been successfully Created. </p>";
        $mail = new PHPMailer(true); 
        $mail->SMTPDebug = 0;                      
        $mail->IsSMTP();                                      
        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'tutorToStudents@gmail.com';                 
        $mail->Password   = 'ppPassword';                               
        $mail->SMTPSecure = 'tls';         
        $mail->Port       = 587;                                    

        $mail->setFrom('tutorToStudents@gmail.com');
        $mail->addAddress($email);    
        $mail->addReplyTo('tutorToStudents@gmail.com');

        $mail->isHTML(true);                                  
        $mail->Subject = 'Email Verification';
        $mail->Body    = "

        <html>
        <body>
        <p><b>You have been invited for a class by your tutor. </b></p>
        <div>
          Here is you meeting link: 
          $meetlink
        </div>
        <br />
        
        <div>
          Here is your message from your tutor: 
          <p><strong>$message</strong></p>
        </div>
        <br />

        <p>Thank you!</p>
        
        <strong>Team, Tutors To You.</strong>
        </body>
        </html>

        ";
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
          ?>
          <p class = "alert alert-danger alert-dismissible">  
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>                
          <?php echo 'Message could not be sent. Please try again' ;?></p>  
          <?php   
          
          
        } 
        else {?>
          <p class = "alert alert-success alert-dismissible py-4 font-weight-bold">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <?php echo "Your message along with the meeting link has been sent to your student. Please save the meeting link or visit the <strong>Meetings</strong> page to see all your classes along with the links."
          ;?></p>  
          <?php         
        }   
        

      }else{
        echo mysqli_error($mysqli);
      }
      
    }else{?>
      <p class = "alert alert-success alert-dismissible py-4 font-weight-bold">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <?php echo "You've already sent this link to this student. Please try another or use the old link."
        ;?>
      </p>  
      <?php
    }

  }
}

function e($val){
  return htmlEntities(trim($val), ENT_QUOTES);
}

?>

<div class="classesDashboard">
  <?php
  
    if($getClasses->num_rows>0){
      ?>
        <div class="topNotify mb-4">
          <p class = "alert alert-info alert-dismissible py-3 font-weight-bold mt-4">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <span>Did you send the meeting link to your closest class yet?</span>
          </p> 
        </div>
      <?php
    }

   include './includes/topNav.php'; 

  ?>

  <div class="currentClasses" id = 'currentClasses'>
    <?php
              
      if($getClasses->num_rows>0){?>
        
        <div class="container-fluid">
         
          <div class="row"> 
            <div class="col-lg-12 col-md-12 col-sm-12">
              <h5 class="text-capitalize profileHeading">My Class Schedules</h5>
              <div class="table-responsive">
                <table id ='dataTable' class="table table-bordered table-striped table-hover mt-4 mb-4">
                  <thead>
                    <tr>
                      <th>Course Title</th>
                      <th>Course Scheduled Date</th>
                      <th>Class Time</th>
                      <th>Student Name</th>
                      <th>Student Email</th>
                      <th>Send Meeting Link</th>
                    
                    </tr>
                  </thead>
                  <tbody>
                    <?php  
                  
                    foreach($classesResult as $row){
                    ?>
                      <tr>
                        <td><?php echo $row['Course_Title']; ?></td>
                        <td></span><?php echo $row['Schedule_Day']; ?></td>
                        <td><?php echo $row['Start_Time'] .' - '. $row['End_Time']; ?></td>
                        <td><?php echo $row['First_Name'].' '. $row['Middle_Name'].' '.$row['Last_Name']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><button class="btn sendLink" data-toggle="modal" data-target="#classModal" data-value="<?php echo $row['Email'] ?>" data-id = "<?php echo $row['bookTutor_id']?>">Send Class Link</button></td>
                        
                      </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <?php
      }else{
        include_once './no_result.php';
              
        showError('booked class', 'You have no course yet. Only when you have a course, students can book it.');
      }
    ?>
  </div>


  <div class="modal fade" id="classModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      <form method='post' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Send the Meeting Link. </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <span>This link will be sent to both the student's dashboard and mail. </span>
            
              <div class="form-group">
                <label for="">Send To</label>
                <input class='form-control studentEmail' name="email" type="text" readonly>
              </div>

              <div class="form-group">
                <input type="text" name='meetingLink' class="form-control" placeholder="Meeting Link" required>
              </div>
              <div class="form-group">
                <textarea class="form-control" name="message" placeholder='Do you have any message for them?'></textarea>
              </div>
              <input type="hidden" name="bookingId" class="bookingId">
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-primary" value="Send" name="sendLink"/>
          </div>
          </form>
      </div>
    </div>
  </div>

</div>

<?php include './includes/footer.php'; ?>