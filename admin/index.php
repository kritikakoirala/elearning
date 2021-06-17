<?php
  include '../includes/conn.php';
  include 'includes/header.php';
  global $mysqli;
?>

<div class="wrapper">
  <?php include 'includes/sideNavigation.php'; ?>
  <div class="content">
    <?php include './includes/topHeader.php'; ?>

      <div class="topDetails">
          <div class="container">
            <div class="row">
              <?php
                $countStudents = $mysqli->query("select * from user where user_type = 'student' AND Is_Verified = 1");
                if($countStudents->num_rows>0 ){
                  ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                      <div class="info text-center">
                      <i class="fas fa-users fa-2x mb-4"></i>
                        <p><?php echo $countStudents->num_rows; ?> Students</p>
                      </div>

                    </div>
                  <?php 
                } 
                $countTeacher = $mysqli->query("select * from user where user_type = 'teacher' AND Is_Verified = 1");
                if($countTeacher->num_rows>0 ){
                  ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                      <div class="info text-center">
                      <i class="fas fa-users fa-2x mb-4"></i>
                        <p><?php echo $countTeacher->num_rows; ?> Teacher</p>
                      </div>
                    </div>
                  <?php 
                  } 

                $countCourse = $mysqli->query("Select * from course");
                if($countCourse->num_rows>0 ){
                  ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                      <div class="info text-center">
                      <i class="fas fa-users fa-2x mb-4"></i>
                        <p><?php echo $countCourse->num_rows; ?> Courses</p>
                      </div>
                    </div>
                  <?php 
                  } 
              ?>
        
            </div>
          </div>
      </div>

      <div id="chart-container">
        <div class="container">
          <div class="row">
            <div class="col-lg-8 col-md-12 col-sm-12">
              <h6 class="text-center mb-4">Chart showing the most popular course (based on rating)</h6>
              <canvas id="ratingGraph"></canvas>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12">
              <h6 class="text-center mb-4">Latest Reviews</h6>
              <?php
                $query = ("select * from reviews, course where reviews.Course_id = course.Course_id ORDER BY Review_Date DESC LIMIT 3");
                $reviewQuery = $mysqli->query($query);
                $reviewResult = $reviewQuery->fetch_all(MYSQLI_ASSOC);
                if($reviewQuery->num_rows>0){
                  foreach($reviewResult as $row){
                    ?>
                      <div class="comment">
                      <i class="fas fa-comments fa-2x  mr-4"></i> <?php echo $row['Review'];?>
                      </div>
                    <?php
                  }
                }
              ?>
              <a href="../admin/all_reviews.php" class="reviewBtn" >All Reviews...</a>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <h6 class="text-center mb-4">Chart showing the month with most enrollment</h6>
              <canvas id="enrollGraph"></canvas> 
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
              <h6 class="text-center mb-4">Chart showing the most popular course (based on number of student)</h6>
              <canvas id = "studentGraph"></canvas>
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12" style="margin:auto 0">
              <h6 class="text-center mb-4"> Courses </h6>
              <?php
                $studentsQuery = $mysqli->query("Select * from course, user where course.User_id = user.User_id Order By Course_id DESC LIMIT 3");
                $studentResult = $studentsQuery->fetch_all(MYSQLI_ASSOC);
                if($studentsQuery->num_rows>0){
                  foreach($studentResult as $row){
                    ?>
                      <div class="user comment">
                      <i class="fas fa-book-reader fa-2x  mr-4"></i> 
                      
                        <?php echo $row['Course_Title'];?>
                        <span class="font-italic">by</span>  
                         
                        <?php echo $row['First_Name'].' '.$row['Middle_Name'].' '.$row['Last_Name']?>
                      
                      </div>
                      
                    <?php
                  }
                }
              ?>
              <a href="../Elearning/admin/all_courses.php" class="reviewBtn" >All Courses...</a>
            </div>
          </div>
        </div>
      </div>
      
    <?php include 'includes/footer.php'; ?>
  </div>
</div>
  
