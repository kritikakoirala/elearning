<?php
  include './includes/conn.php';
  include './includes/header.php';
  global $mysqli;

  if(($_SESSION['user'])){
    $userId = $_SESSION['user']['USER_ID'];
  }

  $notificationQuery = $mysqli->query("select * from book_tutor, booking_notification, course, tutor, student, user where book_tutor.bookTutor_id = booking_notification.bookTutor_id AND book_tutor.Tutor_id = tutor.Tutor_id AND tutor.User_id = user.User_id AND book_tutor.Course_id = course.Course_id AND book_tutor.student_id = student.Student_id AND student.User_id = $userId");
  $notificationQueryResult = $notificationQuery->fetch_all(MYSQLI_ASSOC);

?>
  <div class="studentNotifications">
    <p class = "alert alert-info alert-dismissible py-2 font-weight-bold my-4">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        
        <span>Did you check your meetings dashboard yet? Please visit it regularly whenever you have a class. Your tutor will send you the meeting link.</span>
      </p> 
<?php
  if($notificationQuery->num_rows>0){
    ?>
      <h5 class="ml-4 profileHeading">Your notifications.</h5>
    <?php
  }else{
    include './no_result.php';
    showError('', 'Sorry, you have no new notification!');
    ?>
   
  <?php
  }

?>    

    <div class="container-fluid">
      <div class="row">
        <?php
          
          if($notificationQuery->num_rows>0){
            foreach($notificationQueryResult as $row){
              $date = strtotime($row['Notification_Date']);
            ?>
            <div class="col-lg-8 col-md-8 col-sm-8">
              <div class="notificationList my-3">
                <span class="date"><?php echo date('Y-m-d',$date) ?></span>
                  
                <p> <?php echo $row['Message']?> by 
                <span>
                <?php echo $row['First_Name'].' '. $row['Middle_Name'].' '.$row['Last_Name']; ?>
                </span> on 
                <span>
                <?php echo $row['Course_Title'];?>
                </span></p>
              </div>
            </div>
          <?php
            }
          }
        ?>
        
      </div>
    </div>
  </div>

<?php 
  include './includes/footer.php';
?>