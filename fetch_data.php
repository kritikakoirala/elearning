<?php

include 'includes/conn.php';

$query = "Select * from course, user where course.User_id = user.User_id ";

if(isset($_POST["action"])){
  
  $query = "Select * from course, user where course.User_id = user.User_id ";

  if(isset($_POST["min_price"], $_POST["max_price"])){

    $minimum_price = $_POST["min_price"];
    $maximum_price = $_POST["max_price"];
   
    $query.= " 
    AND Course_Enrollment_Fee BETWEEN $minimum_price AND $maximum_price       
    ";
  }
  
  if(isset($_POST["categories"])){
    $cat_filter = implode("','", $_POST["categories"]);  
    $query.= "AND Tags IN ('".$cat_filter."')";
  }

  if(isset($_POST['titleSort'])){
    $value = $_POST['titleSort'];
    if($value == 'ascTitle'){
      $query.="ORDER BY Course_Title ASC";
    }
    elseif($value == 'descTitle'){
        $query.="ORDER BY Course_Title DESC";
    }
  }




  $filterQuery = $mysqli->query($query);
  $filterQueryResult = $filterQuery->fetch_all(MYSQLI_ASSOC);

  if($filterQuery->num_rows>0){
    foreach($filterQueryResult as $row){
        $courseId = $row['Course_id']
      ?>

      <div class="list">
        <a href="/Elearning/course_detail.php?id=<?php echo $row['Course_id']?>" class="btn viewBtn">View Details</a>
          <div class="topBar pb-3">
            <a href = "/Elearning/course_detail.php?id=<?php echo $row['Course_id']?>" class="courseTitle"><?php echo $row['Course_Title']?></a>
            <p class="mt-4">by <span><?php echo $row['First_Name'] .' '. $row['Middle_Name'].' '. $row['Last_Name'] ?></span></p>
          </div>
          <div class="bottomBar pt-3">
            <div class="courseBrief">
              <p class="shortDesc pb-4"><?php echo $row['Course_Description'];?></p>
              <p><span class="font-weight-bold pr-3">Level:</span><?php echo $row['Course_Level'];?></p>
              <p><span class="font-weight-bold pr-3">Duration:</span><?php echo $row['Course_Duration'];?></p>
            </div>
          </div>
          <div class="rating d-flex justify-content-start justify-content-sm-end justify-content-md-end ">
           
            <?php
              $getRating = $mysqli->query("select  FORMAT(AVG(DISTINCT Rating), 2) as Rating from reviews where Course_id = $courseId Group By Course_id");
              if($getRating->num_rows>0){
                $getRatingResult = $getRating->fetch_assoc();
                
                ?>
                  <span><?php echo $getRatingResult['Rating']?> <i class="fas fa-star fa-2x checked" ></i></span>
                <?php
              }
            ?>

          </div>
      </div>
      <?php
    }
  }
}