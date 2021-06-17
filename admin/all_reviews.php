<?php
  include '../includes/conn.php';
  include 'includes/header.php';
  global $mysqli;

  $getReviews = $mysqli->query("Select * from reviews, course, tutor, user where reviews.Course_id = course.Course_id AND reviews.User_id = user.User_id AND course.User_id = tutor.User_id ORDER BY Review_Date DESC");
  $reviewsResult = $getReviews->fetch_all(MYSQLI_ASSOC);

?>  

<div class="wrapper">
  <?php include 'includes/sideNavigation.php'; ?>
  <div class="content">
    <?php include './includes/topHeader.php'; ?>
    <div class="all_Reviews">
      <?php
        if(isset($_POST['delete'])){
          $reviewId = $_POST['reviewId'];
          
          $check  = $mysqli->query("Select * from reviews where ReviewId = $reviewId");
          if($check->num_rows==1){
            $deleteReview = $mysqli->query("delete from reviews where ReviewId = $reviewId");

            if($deleteReview){?>
              <p class = "alert alert-success alert-dismissible py-4 font-weight-bold">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <?php echo "You have successfully deleted this review";?></p>  
              <?php
            }
          }
        }
      ?>

      <div class="container">
        <div class="row">
          <?php
            if($getReviews->num_rows>0){
              foreach($reviewsResult as $row){
              ?>
              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="single_review d-flex flex-column justify-content-center align-items-center">
                  <div class="proImage">
                    <?php
                      if(!empty($row['Profile_Image'])){
                        ?>
                          
                          <img class="d-flex flex-row justify-content-center align-items-center" src="../<?php echo $row['Profile_Image'];?>" alt="profile">
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
                      <button class="delete text-center font-weight-bold"  data-toggle="modal" data-target="#deleteModal">Delete This Review?</button>

                      <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <form method='post' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <div class="modal-header">
                                  
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>

                                <div class="modal-body">
                                  <h6 class="modal-title" id="exampleModalLabel">Are you sure you want to delete this review?</h6>
                                  <input type="hidden" name='reviewId' value=<?php echo $row['ReviewId']?> >
                                  
                                </div>
                                
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                  <input type="submit" class="btn btn-primary" value="Yes" name="delete"/>
                                </div>
                                </form>
                            </div>
                          </div>
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

    <?php include 'includes/footer.php'; ?>
  </div>
</div>