<?php 
include './includes/conn.php';
include './includes/header.php'; 

$skills = $experience = $about = "";
$skillsErr = $experienceErr = $aboutErr = "";

if(isset($_POST['updateSkills'])){

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
?>

<div class="update_Profile">
<?php  include './includes/topNav.php'; ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
      <h5 class="text-capitalize profileHeading">Add your Skills</h5>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" data-aos='fade-right' data-aos-delay="100"  data-aos-offset="10" enctype="multipart/form-data">
          <?php
            $getProfile = $mysqli->query("Select * from tutor, user where tutor.User_id = user.User_id AND user.User_id = $userId AND Skills ='' and Experience = '' and About_You = ''");
            
            $profileResult = $getProfile->fetch_assoc();
          
            if($getProfile->num_rows==1){
              ?>

              <div class="form-group">
                <input class="form-control" type="text" placeholder="List your Skills" name="skills" value='<?php if(isset($_POST['skills'])) echo $_POST['skills']?>'>
              </div>
              <span class="errorMsg">
                  <?php  echo $skillsErr ?>
                </span>

              <div class="form-group">
                <textarea class="form-control" name="experience" id="experience" cols="30" rows="10" placeholder="Your Experiences" value='<?php if(isset($_POST['experience'])) echo $_POST['experience']?>'></textarea>
              </div>
              <span class="errorMsg">
                  <?php  echo $experienceErr ?>
                </span>

              <div class="form-group">
                <textarea name="about" id="" cols="30" rows="10" class="form-control" placeholder="Tell a little about yourself" value='<?php if(isset($_POST['about'])) echo $_POST['about']?>'></textarea>
              </div>
              <span class="errorMsg">
                  <?php  echo $aboutErr ?>
                </span>

              <input type="submit" name='updateSkills' value="Add" class="btn">
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