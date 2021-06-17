<?php 
  include './includes/conn.php';
  include './includes/header.php';
  
  $address = $number =  $gender = $skills = $experience = $about = $profile = "";

  $addressErr = $numberErr =  $genderErr = $skillsErr = $experienceErr = $aboutErr = $profileErr = "";

  if(isset($_SESSION['user'])){
    $userId = $_SESSION['user']['USER_ID'];
  }
 
  
  if(isset($_POST['addProfile'])){
    $profile_image_temp =$_FILES['image']['tmp_name'];
      $profile_image = $_FILES['image']['name'];

    if(empty($_POST['address'])){
      $addressErr = 'Please enter your Address';
    }else{
      $address = e($_POST['address']);
    }
    if(empty($_POST['number'])){
      $numberErr = 'Please enter your Number';
    }else{
      $number = e($_POST['number']);
    }
   
    if(empty($_POST['gender'])){
      $genderErr = 'Please select your';
    }else{
      $gender = e($_POST['gender']);
    }
    if(!empty($_FILES['image']['name'])){

      $target_dir = "profiles/";
      $target_file = $target_dir . basename($_FILES["image"]["name"]);

      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      // Check if image file is a actual image or fake image
          $check = getimagesize($_FILES["image"]["tmp_name"]);
          if($check == false) {
             
              $profileErr =  "The file is not an image. Please uplaod the appropriate image file" ;
              
          }

          // Check if file already exists
          if (!file_exists($target_file)) {          

              // Allow certain file formats
              if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                  $profileErr = "Sorry, only JPG, JPEG, PNG files are allowed.";
              }
          }
          move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
          $avatar = "profiles/$profile_image";

      $profile = $avatar ? $avatar : '';
    }
    
    if(!$addressErr  && !$numberErr && !$genderErr && !$profileErr){

  
        $insertQuery = $mysqli->query("update tutor SET 
        
    
        Profile_Image = '$profile', 
        Address = '$address', 
        Number = '$number', 
        Gender = '$gender'

        WHERE User_id = $userId
        ");

        if($insertQuery){
          $_SESSION['profile_completed'] = "You have successfully updated your profile details";
          header('location:/Elearning/profile.php');
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
        <h5 class="text-capitalize profileHeading">Complete your Profile</h5>
          <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" data-aos='fade-right' data-aos-delay="100"  data-aos-offset="10" enctype="multipart/form-data">
              <div class="form-group">
              <input class="form-control" type="text" placeholder="Address" name="address" value='<?php if(isset($_POST['address'])) echo $_POST['address']?>'>
            </div>
              <span class="errorMsg">
                <?php  echo $addressErr ?>
              </span>
            <div class="form-group">
              <input class="form-control" type="text" placeholder="Number" name="number" value='<?php if(isset($_POST['number'])) echo $_POST['number']?>'>
            </div>
            <span class="errorMsg">
                <?php  echo $numberErr ?>
              </span>
            <div class="from-group">
              <div class="form-check">
                <input class="form-check-input" id="gender" type="radio" name="gender"  value="male" <?php if(isset($_POST['gender'])) {echo 'checked="checked"';}?>>
                <label class="form-check-label" for="gender">
                  Male
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="gender" value="female" <?php if(isset($_POST['gender'])) {echo 'checked="checked"';}?>>
                <label class="form-check-label" for="gender">
                  Female
                </label>
              </div>
            </div>
            <span class="errorMsg">
                <?php  echo $genderErr ?>
              </span>
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

            <div class="form-group">
              <input type="file" name="image" class="form-control">
            </div>

            <span class="errorMsg">
                <?php  echo $profileErr ?>
              </span>

            <input type="submit" name='addProfile' class="btn" value="Update">

          </form>
        </div>
      </div>
    </div>
  </div>

<?php
  include './includes/footer.php';
?>