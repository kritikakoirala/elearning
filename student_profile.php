<?php 

include './includes/conn.php';
include './includes/header.php'; 
global $mysqli;

$profileQuery = $mysqli->query("Select * from user, student where user.User_id = $userId AND user.User_id = student.User_Id");
$profileResult = $profileQuery->fetch_assoc();

?>

<div class="profile">
<?php include './includes/topNav.php'?>
  <?php
  if(isset($_SESSION['profileUpdated'])){
    ?>
    <p class = "alert alert-success alert-dismissible py-4 font-weight-bold my-4">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <?php echo $_SESSION['profileUpdated']; ?>
      </p> 
    <?php
    unset($_SESSION['profileUpdated']);
  }

  
  ?>

  

<div class="topProfile">
    <h4 class="text-center py-4">Your Personal Details</h4>
    <?php
      if(!empty($profileResult['Profile_Image'])){
        ?>
          <img class="profileImg d-flex flex-row justify-content-center align-items-center" src="../Elearning/<?php echo $profileResult['Profile_Image'];?>" alt="profile">
        <?php
      }else{?>
        <i class='fas fa-user-circle mb-4 profileImg d-flex flex-row justify-content-center align-items-center'></i>
      <?php
      }
     
    ?>
    <div class="container mt-4">
      <div class="row justify-content-center">

        <div class="col-lg-6 col-md-6 col-sm-6 pl-0 my-4 d-flex flex-column justify-content-center align-items-center">
         
            <p><span class="font-weight-bold">First Name:</span>  <?php echo $profileResult['First_Name'];?></p>
            <p><span class="font-weight-bold">Middle Name:</span> <?php echo $profileResult['Middle_Name'];?></p>
            <p><span class="font-weight-bold">Last Name:</span> <?php echo $profileResult['Last_Name'];?></p>
            <p><span class="font-weight-bold">Username:</span> <?php echo $profileResult['username'];?></p>
        
        </div>
      
        
        <div class="col-lg-6 col-md-6 col-sm-6 pl-0 my-4 d-flex flex-column justify-content-center align-items-center">

            <p><span class="font-weight-bold">Email:</span> <?php echo $profileResult['Email'];?></p>
            
            <p><span class="font-weight-bold">Address:</span>  <?php echo $profileResult['Address'];?></p>
          
            <p><span class="font-weight-bold">Number:</span> <?php echo $profileResult['Number'];?></p>
            <p><span class="font-weight-bold">Gender:</span> <?php echo $profileResult['Gender'];?></p>
            <!-- <?php
          $nullQuery = $mysqli->query("select * from tutor where User_id = $userId AND Skills = '' OR Experience = '' OR About_You = '' OR Address = '' OR Number = '' OR Gender = ''");

          if($nullQuery->num_rows==1){
            ?>
              <a href="complete_teacher_profile.php" class="btn mt-4">Complete your Details</a>
            <?php
          }
        ?> -->
            
        </div>
        
        <a href="student_profile_update.php" class="btn mt-4">Update your Profile</a>
      </div>  
    </div>
  </div>

</div>
<?php
include './includes/footer.php';
?>