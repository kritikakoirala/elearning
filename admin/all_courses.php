<?php
  include '../includes/conn.php';
  include 'includes/header.php';
  global $mysqli;

?>  

<div class="wrapper">
  <?php include 'includes/sideNavigation.php'; ?>
  <div class="content">
    <?php include './includes/topHeader.php'; ?>
    <div class="all_courses">

      <div class="container">
        <div class="row col-lg-12 col-md-12 col-sm-12">
          <table id ='dataTable' class="table table-bordered mt-4">
            <thead>
              <tr>
                <th>Course_Id</th>
                <th>Course Title</th>
                <th>Course Tags</th>
                <th>Course Duration</th>
                <th>Course Level</th>
                <th>Classes Per Week</th>
                <th>Time Per Class</th>
                <th>Course Langauge</th>
                <th>Course Enrollment fee</th>
                
              </tr>
            </thead>
            <tbody>
              <?php
                $getCourse= $mysqli->query("select * from course, user where course.User_Id = user.User_id");
                
                $getCourseResult = $getCourse->fetch_all(MYSQLI_ASSOC);
                if($getCourse->num_rows>0){
                  foreach($getCourseResult as $row){
                    ?>
                      <tr>
                        <td><?php echo $row['Course_id'];?></td>
                        <td><?php echo $row['Course_Title'];?></td>
                        <td><?php echo $row['Tags'];?></td>
                        <td><?php echo $row['Course_Duration'];?></td>
                        <td><?php echo $row['Course_Level'];?></td>
                        <td><?php echo $row['Course_Classes'];?></td>
                        <td><?php echo $row['Time_Per_class'];?></td>
                        <td><?php echo $row['Course_Language'];?></td>
                        <td><?php echo $row['Course_Enrollment_Fee'];?></td>
                       
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
    <?php include 'includes/footer.php'; ?>
  </div>
</div>