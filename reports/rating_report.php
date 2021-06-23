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
    <h5 class="profileHeading">Total Reviews and Ratings in each course</h5>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
        <table id ='dataTable' class="table table-bordered mt-4">
          <thead>
            <tr>
              <th>Course Title</th>
              <th>Total Number of Reviews</th>
              <th>Average Rating</th>
              
            </tr>
          </thead>
          <tbody>
          <?php
          $studentReport  = $mysqli->query("select FORMAT(AVG(Rating), 0) AS Rating, COUNT(ReviewId) AS TOTAL_REVIEW, Course_Title from reviews, course, tutor where reviews.Course_id = course.Course_id AND course.User_id = $userId GROUP BY reviews.Course_id");

         
          $studentResult = $studentReport->fetch_all(MYSQLI_ASSOC);

          if($studentReport->num_rows>0){
            
            foreach($studentResult as $row){

              ?>
                <tr>
                
                    <td><?php echo $row['Course_Title'];?></td>
                    <td><?php echo $row['TOTAL_REVIEW'];?></td>
                    <td><?php echo $row['Rating'];?></td>
                    
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