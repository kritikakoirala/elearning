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
        <h5 class="profileHeading">Details of each course created by tutor</h5>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
            <table id ='dataTable' class="table table-bordered mt-4">
              <thead>
                <tr>
                  <th>Profile</th>
                  <th>Name</th>
                  <th>Course Title</th>
                  <th>Course Tags</th>
                  <th>Course Duration</th>
                  <th>Course Level</th>
                  <th>Classes Per Week</th>
                  <th>Time Per Class</th>
                  <th>Course Langauge</th>
                  <th>Course Created</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $studentReport  = $mysqli->query("select * from user, tutor, course where course.User_id = user.User_id AND user.User_id = tutor.User_id");
              
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
                        <td><?php echo $row['Tags'];?></td>
                        <td><?php echo $row['Course_Duration'];?></td>
                        <td><?php echo $row['Course_Level'];?></td>
                        <td><?php echo $row['Course_Classes'];?></td>
                        <td><?php echo $row['Time_Per_class'];?></td>
                        <td><?php echo $row['Course_Language'];?></td>
                        <td><?php echo $row['Course_Created'];?></td>
                        
                        
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

  