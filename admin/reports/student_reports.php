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
        <h5 class="profileHeading">Details of students enrolled in each course</h5>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
            <table id ='dataTable' class="table table-bordered mt-4">
              <thead>
                <tr>
                  <th>Profile</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Enroll In</th>
                  <th>Enrollment Fee</th>
                  <th>Enrolled Status</th>
                  <th>Enrolled Date</th>
                  

                </tr>
              </thead>
              <tbody>
              <?php
              $studentReport  = $mysqli->query("select * from user, student, course, enroll where enroll.Student_id = student.Student_id AND user.User_id = student.User_id AND enroll.Course_id = course.Course_id");
              
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
                        
                        <td><?php echo $row['Email'];?></td>
                        
                        <td><?php echo $row['Course_Title'];?></td>
                        <td><?php echo $row['Enrollment_Fee'];?></td>

                        <td><?php echo $row['Enroll_Status'];?></td>
                        <td><?php echo $row['Enrolled_Date'];?></td>
                        
                        
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

  