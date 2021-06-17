<?php 
include './includes/conn.php';
include './includes/header.php'; 

if(isset($_SESSION['user'])){
  $userId = $_SESSION['user']['USER_ID'];
}

$getReviews = $mysqli->query("Select * from reviews, course, tutor, user where reviews.Course_id = course.Course_id AND reviews.User_id = user.User_id AND course.User_id = tutor.User_id AND tutor.User_id = $userId");
$reviewsResult = $getReviews->fetch_all(MYSQLI_ASSOC);

?>

  <div class="all_Reviews">
  <?php include './includes/topNav.php'; ?>
    <div class="container">
      <div class="row">
      <?php
        if($getReviews->num_rows>0){
          foreach($reviewsResult as $row){
          ?>
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="single_review d-flex flex-column justify-content-center align-items-center" data-aos='slide-up'>
              <div class="proImage">
                <?php
                  if(!empty($row['Profile_Image'])){
                    ?>
                      <img class="d-flex flex-row justify-content-center align-items-center" src="../Elearning/<?php echo $row['Profile_Image'];?>" alt="profile">
                    <?php
                  }else{?>
                    <i class='fas fa-user-circle mb-4'></i>
                  <?php
                  }
                ?>
              </div>
              <div class="reviewDetail">
                <p>
                  <span class="name text-center"><?php echo $row['First_Name'].' '. $row['Middle_Name'].' '.$row['Last_Name']?></span>

                  <span class="reviewContent mt-4">
                    <i class="fas fa-quote-left"></i> 
                    <blockquote ><?php echo $row['Review']?></blockquote>
                  </span>
                  
                  <div class="reviewFooter d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold"><?php echo $row['Course_Title']?></span>
                    <span class="font-weight-bold mt-4 pt-4"><?php echo $row['Review_Date']?></span>
                  </div>
                  
                </p> 
              </div>
            </div>
          </div>
          <?php
          }
        }
        ?>
      </div>
    </div>
  </div>

<?php include './includes/footer.php'; ?>