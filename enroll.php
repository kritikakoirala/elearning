<?php
  include './includes/conn.php';
  include './includes/header.php';
  // include './enroll_action.php';

  if(isset($_GET['courseId'])){
    $courseId = $_GET['courseId'];
  }

  if(isset($_SESSION['user'])){
    $studentId = $_SESSION['user']['USER_ID'];
  }


  if(isset($_POST['proceedToPay'])){
   
    if(!empty($_POST['course_title']) && !empty($_POST['tutor']) && !empty($_POST['student']) && !empty($_POST['amount'])){
      

      $studentId = $_POST['student_id'];
      $TutorId = $_POST['tutor_id'];
      $courseId = $_POST['course_id'];
      $amount = $_POST['amount'];
     
      echo $studentId, $TutorId, $courseId;
      $checkQuery = $mysqli->query("select * from enroll where Student_id = $studentId AND Tutor_id = $TutorId AND Course_id = $courseId");
      $checkQueryResult = $checkQuery->fetch_assoc();
     
      if($checkQuery->num_rows==0){
        $enrollQuery = $mysqli->query("insert into enroll (Student_id, Tutor_id, Course_id, Enrollment_Fee, Enroll_Status, Enrolled_Date) VALUES($studentId,$TutorId,$courseId, $amount, 'pending', CURRENT_TIMESTAMP) ");
        
        if($enrollQuery){ 
          $_SESSION['course'] = $courseId;
          $_SESSION['tutor'] = $TutorId;
          $_SESSION['student'] = $studentId;
          header('Location:enroll_action.php');
        }
      }else{
        $_SESSION['course'] = $courseId;
          $_SESSION['tutor'] = $TutorId;
          $_SESSION['student'] = $studentId;
          header('Location:enroll_action.php');
      }
    
    }
  }
?>

<div class="enrollDetails">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <p class="font-weight-bold text-center mb-4">Check your course Details</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

        <?php

          $getSingleCourseQuery = $mysqli->query("select * from course where course_id = $courseId");
          $courseResult = $getSingleCourseQuery->fetch_assoc();
        
          if($getSingleCourseQuery->num_rows>0){
             
            ?>
              <div class="form-group">
                <label for="courseTitle">Course Title</label>
                <input type="text" class="form-control" id='courseTitle' name='course_title' value= '<?php echo $courseResult['Course_Title'] ?>' readonly>
                <input type="hidden" name='course_id' value='<?php echo $courseId ?>'>
              </div>

              <div class="form-group">
                <label for="courseTitle">Course Duration</label>
                <input type="text" class="form-control" id='courseTitle' name='course_duration' value= '<?php echo $courseResult['Course_Duration'] ?>' readonly>
                
              </div>

              <div class="form-group">
                <label for="courseTitle">Classes Per Week</label>
                <input type="text" class="form-control" id='courseTitle' name='course_classes' value= '<?php echo $courseResult['Course_Classes'] ?>' readonly>
                
              </div>
            <?php
          }
          
            $tutorProfileQuery = $mysqli->query("Select * from course, user, tutor where course.Course_Id = '$courseId' AND course.User_Id = user.User_id AND user.User_id = tutor.User_Id");
            $tutorResult = $tutorProfileQuery->fetch_assoc();
            if($tutorProfileQuery->num_rows>0){
              ?>
                <div class="form-group">
                  <label for="tutor">Tutor</label>
                  <input type="text" class="form-control" id='tutor' name='tutor' value='<?php echo $tutorResult['First_Name'] .' '. $tutorResult['Middle_Name'] .' '. $tutorResult['Last_Name'];?>' readonly>
                  <input type="hidden" name='tutor_id' value='<?php echo $tutorResult['Tutor_id'];?>'>
                </div>
              <?php
            }else{
              echo $tutorProfileQuery->error;
            }

            $studentQuery = $mysqli->query("select * from student, user where student.User_id = '$studentId' AND student.User_id = user.User_Id");
            $studentResult = $studentQuery->fetch_assoc();
            if($studentQuery->num_rows>0){
              ?>
              <div class="form-group">
                <label for="student">Student</label>
                <input type="text" class="form-control" id='student' name='student' value='<?php echo $studentResult['First_Name'] .' '. $studentResult['Middle_Name'] .' '. $studentResult['Last_Name'];?>' readonly>
                <input type="hidden" name='student_id' value='<?php echo $studentResult['Student_id'];?>'>
              </div>
              <?php
            }
          ?>

          <div class="form-group">
            <label for="free">Enrollment Fee</label>
            
            <input type="text" class="form-control" id='fee' name='amount' value= '<?php echo $courseResult['Course_Enrollment_Fee'] ?>' readonly>
          </div>
          
          <a href='http://localhost/Elearning/course_detail.php?id=<?php echo $courseId;?>' class="btn">Go Back</a>
          <input type="submit" class="btn" name='proceedToPay' value="Proceed">
        </form>
      </div>
    </div>
  </div>
</div>

<?php 
include './includes/footer.php'
?>