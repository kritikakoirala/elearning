<?php

include './includes/conn.php';
include './includes/header.php'; 
?>

<div class="displayCourses">
  <?php 
  if(isset($_SESSION['success_courseEdited'])){
    ?>
     <p class = "alert alert-success alert-dismissible py-4 font-weight-bold my-4">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <?php echo $_SESSION['success_courseEdited']; ?>
      </p> 
    <?php
    unset($_SESSION['success_courseEdited']);
  }
  include './includes/topNav.php'; ?>
    <div class="container-fluid mt-4">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
        <h5 class="text-capitalize profileHeading">All Courses</h5>

        <div class="videoReminder alert-info">
          
          <p class=" font-weight-bold mt-4">
            Including a sample video in your course increases your chances of being recognised and gain more students. 
          </p>
          <span class="font-weight-bold mb-4">Have you included sample videos in your courses? If no, do it now!</span><i class="far fa-smile-wink fa-2x "></i>
        </div>
        
        
        <div class="table-responsive">
          <table id ='dataTable' class="table table-striped table-hover mt-4 mb-4">
            <thead>
              <tr>
                <th>Course Title</th>
                <th>Course Created Date</th>
                <th>No of students</th>
                <th>Details</th>
                <th>Edit</th>
              </tr>
            </thead>
            <tbody>

            <?php
        
              $selectCoursesQuery = $mysqli->query("Select * from course where User_Id = '$userId' ORDER BY Course_Created DESC");
            
              $queryResult = $selectCoursesQuery->fetch_all(MYSQLI_ASSOC);
          
              if($selectCoursesQuery->num_rows>0){
                foreach($queryResult as $row){
                    $courseId = $row['Course_id'];
                  ?>
                  <tr>
                    <td class="courseTitle text-center"><?php echo $row['Course_Title'];?></td>
                    <td><?php echo $row['Course_Created']?></td>
                    <td>
                      <?php
                        $studentQuery = $mysqli->query("Select * from enroll where enroll.Course_id = $courseId AND Enroll_Status = 'completed'");
                        if($studentQuery->num_rows>0){
                          echo "$studentQuery->num_rows students"; 
                        }else{
                          echo "0 Students";
                        }
                      ?>
                    </td>
                    <td><a href="/Elearning/course_detail.php?id=<?php echo $row['Course_id'] ?>" class="font-weight-bold">View Details</a></td>
                    <td><a href="edit_courses.php?editId=<?php echo $courseId;?>"><i class="fas fa-edit"></i></a></td>
                  </tr>
                  <?php

                }
              }else{
                include_once './no_result.php';
                
                showError('courses', 'You have no course yet. Only when you have a course, students can book it.');
              }
              ?>
              
            </tbody>
          </table>
        </div>
        </div>
      </div>
    </div>
  </div>

<?php include './includes/footer.php'; ?>