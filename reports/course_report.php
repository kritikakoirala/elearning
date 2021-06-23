<?php
  include '../includes/conn.php';
  include '../includes/header.php';
  global $mysqli;

  if(isset($_SESSION['user'])){
    $userId = $_SESSION['user']['USER_ID'];
   
  }

?>

<div class="wrapper">
  <div class="student_reports">
    <div class="container">
    <h5 class="profileHeading">Total Students enrolled in each course monthly</h5>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
        <table id ='dataTable' class="table table-bordered mt-4">
          <thead>
            <tr>
              <th>Course Title</th>
              <th>Total Number of Student</th>
              <th>Month</th>
              <th>Enrolled Date</th>
              
            </tr>
          </thead>
          <tbody>
          <?php

          $months  = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

          $studentReport  = $mysqli->query("select count(enroll.Student_id) AS TOTAL_STUDENT, Course_Title AS Title, Enrolled_Date as Enroll_Date from enroll, course, tutor, student where enroll.Course_id = course.Course_id AND enroll.Student_id = student.Student_id AND enroll.Tutor_id = tutor.Tutor_id AND tutor.user_id = $userId Group by Course_Title, Month(Enrolled_Date)");

         
          $studentResult = $studentReport->fetch_all(MYSQLI_ASSOC);

          if($studentReport->num_rows>0){
            
            foreach($studentResult as $row){
                $date = $row['Enroll_Date'];
              ?>
                <tr>
                
                    <td><?php echo $row['Title'];?></td>
                    <td><?php echo $row['TOTAL_STUDENT'];?></td>
                    <td><?php echo date('M', strtotime($date)); ?></td>
                    <td><?php echo $row['Enroll_Date'];?></td>
                    
                    
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
  include '../includes/footer.php';
?>