<?php 
include './includes/conn.php';
include './includes/header.php'; 

$skills = $experience = $about = "";
$skillsErr = $experienceErr = $aboutErr = "";

  if(isset($_POST['update_skills'])){

    echo "cnb";
    if(empty($_POST['skills'])){
      $skillsErr = 'Please enter your Skills';
    }else{
      $skills = e($_POST['skills']);
    }
    if(empty($_POST['experience'])){
      $experienceErr = 'Please enter your Experience';
    }else{
      $experience = e($_POST['experience']);
    }
    if(empty($_POST['about'])){
      $aboutErr = 'Please tell us a little about yourself';
    }else{
      $about = e($_POST['about']);
    }
  
    if(!$skillsErr && !$experienceErr && !$aboutErr){
  
      $insertQuery = $mysqli->query("update tutor SET       
      Skills = '$skills', 
      Experience = '$experience', 
      About_You = '$about'
      
      WHERE User_id = $userId
      ");
    
      if($insertQuery){
        $_SESSION['profile_completed'] = "You have successfully updated your profile details";
        header('location:/Elearning/profile.php');
      }else{
        echo mysqli_error($mysqli);
      }
    }
  
  }
  function e($val){
    return htmlEntities(trim($val), ENT_QUOTES);
  }
  if(isset($_POST['cancel'])){
    header('location:/Elearning/profile.php');
  }
?>

<div class="update_Profile">
  <?php  include './includes/topNav.php'; ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8">
        <h5 class="text-capitalize profileHeading">Update your Skills</h5>
          <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" data-aos='fade-right' data-aos-delay="100"  data-aos-offset="10" enctype="multipart/form-data">
            <?php
              $getProfile = $mysqli->query("Select * from tutor, user where tutor.User_id = user.User_id AND user.User_id = $userId");
              
              $profileResult = $getProfile->fetch_assoc();

        
              if($getProfile->num_rows>0){
                ?>

              <div class="form-group">
                <label for="">Skills</label>
                <input class="form-control" type="text" name="skills" value='<?php echo $profileResult['Skills']?>'>
              </div>
              <span class="errorMsg">
                  <?php  echo $skillsErr ?>
                </span>

              <div class="form-group">
                <label for="">Experience</label>
                <textarea class="form-control" name="experience" id="experience" cols="30" rows="10" placeholder="Your Experiences"><?php echo $profileResult['Experience']?></textarea>
              </div>
              <span class="errorMsg">
                  <?php  echo $experienceErr ?>
                </span>

              <div class="form-group">
                <label for="">Tell us About Yourself</label>
                <textarea name="about" id="" cols="30" rows="10" class="form-control" placeholder="Tell a little about yourself"><?php echo $profileResult['About_You']?></textarea>
              </div>
              <span class="errorMsg">
                  <?php  echo $aboutErr ?>
                </span>

              <input type="submit" name='update_skills' value="Update" class="btn">
              <input type="submit" name='cancel' value="Cancel" class="btn">
              </div>
              <?php
              }

            ?>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php
include './includes/footer.php';
?>