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
    <h5 class="profileHeading">Total Payments made for each course in a month</h5>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
        <table id ='dataTable' class="table table-bordered mt-4">
          <thead>
            <tr>
              <th>Course Title</th>
              <th>Total Number of Payment</th>
              <th>Month</th>
              <th>Total SUM of Payment</th>
              
            </tr>
          </thead>
          <tbody>
          <?php
          $studentReport  = $mysqli->query("Select count(Payment_id) AS TOTAL_PAYMENT, Payment_Date AS MONTH, Course_Title, SUM(Enrollment_Fee) AS SUM from payment, enroll, tutor, course where payment.Enroll_id = enroll.Enroll_id aND enroll.Course_id = course.Course_id AND enroll.Tutor_id = tutor.Tutor_id AND tutor.User_id = $userId Group BY Month(Payment_Date), Course_Title");

          $studentResult = $studentReport->fetch_all(MYSQLI_ASSOC);

          if($studentReport->num_rows>0){
            
            foreach($studentResult as $row){

              ?>
                <tr>
                
                    <td><?php echo $row['Course_Title'];?></td>
                    <td><?php echo $row['TOTAL_PAYMENT'];?></td>
                    <td><?php echo $row['MONTH'];?></td>
                    <td><?php echo $row['SUM'];?></td>
                    
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