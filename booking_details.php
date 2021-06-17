<?php
include './includes/conn.php';
include './includes/header.php';

if(isset($_SESSION['user'])){
  $userId = $_SESSION['user']['USER_ID'];
}

$bookingQuery = $mysqli->query("select * from tutor_schedule, book_tutor, tutor, course where tutor_schedule.Schedule_id = book_tutor.Schedule_id AND book_tutor.Tutor_id = tutor.Tutor_id AND tutor.User_id = $userId AND book_tutor.Course_id = course.Course_id AND book_tutor.Booking_Status = 'pending'");
$bookingResult = $bookingQuery->fetch_assoc();

$bookingStudentQuery = $mysqli->query("select * from book_tutor, student, user where book_tutor.student_id = student.Student_id AND student.User_id = user.User_id AND book_tutor.Booking_Status = 'pending'");
$studentResult = $bookingStudentQuery->fetch_assoc();

$scheduleId = $bookingResult['Schedule_id'];
$bookedId = $bookingResult['bookTutor_id'];
$bookedTutor = $bookingResult['Tutor_id'];
$bookedCourse = $bookingResult['Course_id'];
$bookedStudent = $bookingResult['student_id'];
$day = $bookingResult['Schedule_Day'];
$sTime = $bookingResult['Start_Time'];
$eTime = $bookingResult['End_Time'];

if($bookingQuery->num_rows==1){
if(isset($_POST['confirm'])){

    $updateStatus = $mysqli->query("update book_tutor SET Booking_Status = 'Verified' WHERE student_id = $bookedStudent AND Tutor_id = $bookedTutor AND Course_id = $bookedCourse AND Schedule_id = $scheduleId AND Booking_Status = 'pending'");
    if($updateStatus){

      $checkNotification = $mysqli->query("select * from booking_notification where bookTutor_id = $bookedId");
      if($checkNotification->num_rows==0){
        $notificationQuery = $mysqli->query("insert into booking_notification (Message, bookTutor_id, Notification_Date) VALUES ('Booking Request Verified', $bookedId, CURRENT_TIMESTAMP)");
      }

      $_SESSION['classBooked'] = 'Your class is successfully booked. Please note the time and date of the scheduled class';
      header('Location:/Elearning/teacher_dashboard.php');
    }
  
}

if(isset($_POST['cancelCourse'])){
  $updateStatus = $mysqli->query("update book_tutor SET Booking_Status = 'Cancelled' WHERE student_id = $bookedStudent AND Tutor_id = $bookedTutor AND Course_id = $bookedCourse AND Book_Starttime = '$sTime' AND Book_Endtime = '$eTime' AND Book_day = '$day' AND Booking_Status = 'pending'");
  if($updateStatus){
    $checkNotification = $mysqli->query("select * from booking_notification where bookTutor_id = $bookedId");
      if($checkNotification->num_rows==0){
        $notificationQuery = $mysqli->query("insert into booking_notification (Message, bookTutor_id, Notification_Date) VALUES ('Booking Request Cancelled', $bookedId, CURRENT_TIMESTAMP)");
      }
    $_SESSION['classCancelled'] = 'You successfully cancelled the requested class.';
    header('Location:/Elearning/teacher_dashboard.php');
  }
}

?>

  <div class="booking_details">
    <h5 class="text-center my-4">Your booking appointments</h5>
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12">
          <div class="details">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
              <p> <span class="font-weight-bold">Student Name:</span>  <?php echo $studentResult['First_Name'] .' '. $studentResult['Middle_Name'] .' '. $studentResult['Last_Name'];?></p>
              <p><span class="font-weight-bold">Course Name:</span> <?php echo $bookingResult['Course_Title']?></p>
              <p><span class="font-weight-bold">Booking Day:</span> <?php echo $day ?></p>
              <p><span class="font-weight-bold">Booking Start Time:</span> <?php echo $sTime?></p>
              <p><span class="font-weight-bold">Booking End Time: </span><?php echo $eTime?></p>
              <input type='submit' name='confirm' class="btn" value="Confirm">
              <button type='button' name='cancel' class="btn" data-toggle="modal" data-target="#cancelbookModal">Cancel</button>
            </form>
          </div>
          <div class="modal fade" id="cancelbookModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method='post'>
                    <div class="modal-header">
                     
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <h5>Are you sure you want to cancel this booking request?</h5>
                      
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <input type="submit" class="btn btn-primary" value="Confirm" name="cancelCourse"/>
                    </div>
                  </form>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
<?php
}
include './includes/footer.php';
?>