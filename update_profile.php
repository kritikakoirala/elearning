<?php 
  include './includes/conn.php';
  include './includes/header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './vendor/phpmailer/phpmailer/src/Exception.php';
require './vendor/phpmailer/phpmailer/src/SMTP.php';
require './vendor/autoload.php';

  if(isset($_SESSION['user'])){
    $userId = $_SESSION['user']['USER_ID'];
  }
 
  $firstName = $lastName = $email =$username= $password = $confirmPassword=$address = $number =  $gender = $profile = "";

  $firstNameErr = $lastNameErr = $emailErr =$usernameErr= $passwordErr = $confirmPasswordErr=$addressErr = $numberErr =  $genderErr="";

  if(isset($_POST['update_profile'])){
    $firstName = e($_POST['firstName']);
    $middleName = e($_POST['middleName']);
    $lastName = e($_POST['lastName']);
    $username = e($_POST['username']);
    $email = e($_POST['email']);
    $password = e($_POST['password']);
    $address = e($_POST['address']);
    $number = e($_POST['number']);
    $profile_image_temp =$_FILES['image']['tmp_name'];
    $pro_image = $_FILES['image']['name'];
    
    if(!empty($_POST['gender'])){
      $gender = e($_POST['gender']);

    }else{
      $genderErr = "Please select a gender";
    }

    if(empty($_POST['password'])){
      $passwordErr = 'Please enter your Password';
    }else{
      $password = e($_POST['password']);
    }

    if ($_POST['password'] && !preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $_POST['password'])){
      $passwordErr = "Password should be at 8 characters at minimum and contain at least one Uppercase letter, one number and one symbol";
    }

    move_uploaded_file($_FILES["image"]["tmp_name"], "profiles/$pro_image");
        $avatar = "profiles/$pro_image";
        $profile = $avatar ? $avatar : '';
       
           

        if(empty($pro_image)){
            $imagequery = $mysqli->query("SELECT Profile_Image FROM tutor WHERE User_id = $userId");
            $imagequeryResult = $imagequery->fetch_assoc();
            var_dump($imagequery);
            if($imagequery->num_rows>0){
              $avatar = $imagequeryResult['Profile_Image'];
              $profile = $avatar ? $avatar : '';
            }

           
        }
  
    if(!$passwordErr & !$genderErr){
      $insert1Query = $mysqli->query("update tutor SET 
        
        Profile_Image = '$profile', 
        Address = '$address', 
        Number = '$number', 
        Gender = '$gender'

        WHERE User_id = $userId
      ");


      $password = password_hash($password, PASSWORD_DEFAULT);
      $insert2Query = $mysqli->query("update user SET 
        
        First_Name = '$firstName', 
        Last_Name = '$lastName', 
        Middle_Name = '$middleName', 
        Email = '$email',
        username= '$username',
        Password='$password'

        WHERE User_id = $userId
      ");

      if($insert2Query || $insert1Query){
        $today = date("l jS \of F Y, h:i:s A"); 
        $_SESSION['profileUpdated'] = "You have successfully updated your profile";
        header('location:/Elearning/profile.php');
        $mail = new PHPMailer(true); 
        $mail->SMTPDebug = 0;                      
        $mail->IsSMTP();                                      
        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'tutorToStudents@gmail.com';                 
        $mail->Password   = 'ppPassword';                               
        $mail->SMTPSecure = 'tls';         
        $mail->Port       = 587;                                    

        $mail->setFrom('tutorToStudents@gmail.com');
        $mail->addAddress($email);    
        $mail->addReplyTo('tutorToStudents@gmail.com');

        $mail->isHTML(true);                                  
        $mail->Subject = 'Email Verification';
        $mail->Body    = "

        <html>
        <body>
        <p><b>You successfully changed your account Details on $today</b></p>
        
        <p>Please Contact the authority if it wasn't you.</p>
      
        <p>Thank you!</p>
        <br />
        <strong>Team, Tutors To You.</strong>
        </body>
        </html>

        ";
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
          ?>
          <p class = "alert alert-danger alert-dismissible">  
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>                
          <?php echo 'Mail could not be sent. Please try again' ;?></p>  
          <?php   
          
          
        } 
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
    <div class="container-fluid mt-4">
    
      <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8">
          <h5 class="text-capitalize profileHeading">Update your profile</h5>
          <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" data-aos='fade-right' data-aos-delay="100"  data-aos-offset="10" enctype="multipart/form-data">
            <?php
              $getProfile = $mysqli->query("Select * from tutor, user where tutor.User_id = user.User_id AND user.User_id = $userId");
              
              $profileResult = $getProfile->fetch_assoc();
             
              if($getProfile->num_rows>0){
                ?>

            <div class="form-group">
              <label for="">First Name : </label>
              <input type="text" class="form-control" name='firstName' placeholder="First Name" value='<?php echo $profileResult['First_Name']?>'>
              <span class="errorMsg">
                <?php echo $firstNameErr; ?>
              </span>
              
            </div>
            <div class="form-group">
            <label for="">Middle Name : </label>
              <input type="text" class="form-control" name='middleName' placeholder="Middle Name" value='<?php echo $profileResult['Middle_Name']?>'>
            </div>
            <div class="form-group">
            <label for="">Last Name : </label>
              <input type="text" class="form-control" name='lastName' placeholder="Last Name" value='<?php echo $profileResult['Last_Name']?>'>
            </div>
            <span class="errorMsg">
                <?php echo $lastNameErr ?>
              </span>
              <div class="form-group">
              <label for="">Username : </label>
              <input type="text" class="form-control" name='username' placeholder="Username" value='<?php echo $profileResult['username']?>'>
            </div>
            <span class="errorMsg">
                <?php  echo $usernameErr ?>
              </span>
            <div class="form-group">
            <label for="">Email : </label>
              <input type="email" class="form-control" name='email' placeholder="Email" value='<?php echo $profileResult['Email']?>'>
            </div>
            <span class="errorMsg">
                <?php  echo $emailErr ?>
              </span>
            <div class="form-group">
            <label for="">Password : </label>
              <input type="password" class="form-control" name='password' placeholder="Password" value=''>
            </div>
            <span class="errorMsg">
                <?php  echo $passwordErr ?>
              </span>
            <div class="form-group">
            <label for="">Address : </label>
              <input class="form-control" type="text" placeholder="Address" name="address" value='<?php echo $profileResult['Address']?>'>
            </div>
              <span class="errorMsg">
                <?php  echo $addressErr ?>
              </span>
            <div class="form-group">
            <label for="">Phone Number : </label>
              <input class="form-control" type="text" placeholder="Number" name="number" value='<?php echo $profileResult['Number']?>'>
            </div>
            <span class="errorMsg">
                <?php  echo $numberErr ?>
              </span>
            <div class="from-group">
              <div class="form-check">
                <input class="form-check-input" id="gender" type="radio" name="gender"  value="male" <?php  if(isset($profileResult['Gender'])) {echo 'checked="checked"';}?>>
                <label class="form-check-label" for="gender">
                  Male
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="gender" value="female" <?php  if(isset($profileResult['Gender'])) {echo 'checked="checked"';}?>>
                <label class="form-check-label" for="gender">
                  Female
                </label>
              </div>
              <span class="errorMsg">
                <?php  echo $genderErr ?>
              </span>
              <div class="form-group">
                  <label for="user_image" class = "pb-1">Profile Avatar</label><br>
                  <?php
                    if(isset($profileResult['Profile_Image'])){
                      ?>
                        <img width = "50" src="../Elearning/<?php echo $profileResult['Profile_Image']; ?>" alt="user_thumbnail"><br/><br>
                      <?php
                    }
                  ?>
                  
                  <input type="file" name = "image">

              </div>

              <input type="submit" name='update_profile' value="Update" class="btn">
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