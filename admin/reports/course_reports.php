<?php
  include '../../includes/conn.php';
  include '../includes/header.php';
  global $mysqli;
?>

<div class="wrapper">
  <?php include '../includes/sideNavigation.php'; ?>
  <div class="content">
    <?php include '../includes/topHeader.php'; ?>
      <div class="student_reports">
        <div class="container">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
            <table id ='dataTable' class="table table-bordered mt-4">
              <thead>
                <tr>
                  <th>Profile</th>
                  <th>Student Name</th>
                  <th>Course Title</th>
                  <th>Enrolled Date</th>
                  <th>Enrollment Fee</th>
                  <th>Payment Date</th>
                  <th>Payment Status</th>
                  
                  
                </tr>
              </thead>
              <tbody>
              <?php
              $studentReport  = $mysqli->query("select * from user, student, course, enroll, payment where payment.Enroll_id = enroll.Enroll_id AND enroll.Course_id = course.Course_id AND enroll.Student_id = student.Student_id AND student.User_id = user.User_id AND enroll.Enroll_Status = 'completed'");
              
              $studentResult = $studentReport->fetch_all(MYSQLI_ASSOC);
         
              if($studentReport->num_rows>0){
                foreach($studentResult as $row){
                  ?>
                    <tr>
                        <td><?php
                          if(!empty($row['Profile_Image'])){
                            ?>
                              
                              <img class="d-flex flex-row justify-content-center align-items-center" src="../../<?php echo $row['Profile_Image'];?>" alt="profile">
                            <?php
                          }else{?>
                            <i class='fas fa-user-circle mb-4'></i>
                          <?php
                          }
                        ?></td>
                        <td><?php echo $row['First_Name'].' '. $row['Middle_Name'].' '.$row['Last_Name'];?></td>
                        
                        
                        <td><?php echo $row['Course_Title'];?></td>
                        <td><?php echo $row['Enrolled_Date'];?></td>
                        <td><?php echo $row['Enrollment_Fee'];?></td>
                        <td><?php echo $row['Payment_Date'];?></td>
                        <td><?php echo $row['Payment_Status'];?></td>
                        
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
    <?php include '../includes/footer.php'; ?>
  </div>
</div>

  