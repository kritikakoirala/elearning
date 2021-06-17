<?php 
  include './includes/conn.php';
  include './includes/header.php'; 
  include './form_validation.php';
 

  $firstName = $lastName = $email =$username= $password = $confirmPassword=$gender="";

  $firstNameErr = $lastNameErr = $emailErr =$usernameErr= $passwordErr = $confirmPasswordErr=$genderErr="";

  if(isset($_POST['signUp'])){
 
    $middleName = e($_POST['middleName']);
    if(empty($_POST['firstName'])){
      $firstNameErr = 'Please enter your First Name';
    }else{
      $firstName = e($_POST['firstName']);
    }

    if(empty($_POST['lastName'])){
      $lastNameErr = 'Please enter your Last Name';
    }else{
      $lastName = e($_POST['lastName']);
    }

    if(empty($_POST['username'])){
      $usernameErr =  "Username is required";
     
    }
    else{
      $username = e($_POST['username']);
    }
    
    if(empty($_POST['email'])){
      $emailErr = 'Please enter your Email';
    }else{
      $email = e($_POST['email']);
    }

    if(!empty($_POST['email']) && !filter_var(($_POST['email']), FILTER_VALIDATE_EMAIL)){
      $emailErr = "Enter the email in a correct format. Eg. abc@gmail.com" ;
   }
  
    if(empty($_POST['password'])){
      $passwordErr = 'Please enter your Password';
    }else{
      $password = e($_POST['password']);
    }

    if ($_POST['password'] && !preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $_POST['password'])){
      $passwordErr = "Password should be at 8 characters at minimum and contain at least one Uppercase letter, one number and one symbol";
    }
    
    if (isset($_POST['password']) && $_POST['password'] !== $_POST['confirmPassword']) {
      $confirmPasswordErr= 'The two passwords do not match';
    }

    if((isset($_POST['gender']))){
      $gender = e($_POST['gender']);
      
    }else{
      $genderErr = 'Please select your gender';
    }
    
   
    if(!$usernameErr && !$emailErr && !$firstNameErr && !$lastNameErr && !$passwordErr && !$confirmPasswordErr && !$genderErr){
        
    furtherValidation(e($_POST['firstName']), e($_POST['middleName']), e($_POST['lastName']), e($_POST['username']), e($_POST['email']), e($_POST['password']), e($_POST['confirmPassword']), ($gender));

    }
  
 }

 function e($val){
  return htmlEntities(trim($val), ENT_QUOTES);
}

?>

  <div class="signup">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12">
          <div class="signupSidebar" >
            <h2 class="text-capitalize mb-4">Sign up with us</h2>
            <img src="./images/student_signup.svg" alt="teacher">
            <p class="font-weight-bold mt-5"><i class="fas fa-plus-circle"></i>  Pick up new Skill</p>
            <p class="font-weight-bold"><i class="fas fa-bullhorn"></i>  Stay productive</p>
          </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12" >

          <div class="googleSignUp d-flex flex-column justify-content-center align-items-center" >
            <h3 class="mb-4">Sign In with Google</h3>
            <button class="btn mt-3"><i class="fab fa-google"></i>  Sign In with Google</button>
          </div>

          <div class="or font-weight-bold text-center text-uppercase">OR</div>

          <?php if(isset($error)) echo '<span class="errorMsg serverErrorMsg">'.$error.'</span>'; ?>
          <form method='POST' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="pb-4">
            <div class="form-group">
              <input type="text" class="form-control" name='firstName' placeholder="First Name" value='<?php if(isset($_POST['firstName'])) echo $_POST['firstName']?>'>
              <span class="errorMsg">
                <?php echo $firstNameErr; ?>
              </span>
              
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name='middleName' placeholder="Middle Name">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name='lastName' placeholder="Last Name" value='<?php if(isset($_POST['lastName'])) echo $_POST['lastName']?>'>
            </div>
            <span class="errorMsg">
                <?php echo $lastNameErr ?>
              </span>
            <div class="form-group">
            
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="male" value="male" <?php if (isset($_POST['gender']) && $_POST['gender']=="male") echo "checked";?>>
                <label class="form-check-label" for="male">Male</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="female" value="female" <?php if (isset($_POST['gender']) && $_POST['gender']=="female") echo "checked";?>>
                <label class="form-check-label" for="female">Female</label>
              </div>
            </div>
            <span class="errorMsg">
                <?php  echo $genderErr ?>
              </span>
            <div class="form-group">
              <input type="text" class="form-control" name='username' placeholder="Username" value='<?php if(isset($_POST['username'])) echo $_POST['username']?>'>
            </div>
            <span class="errorMsg">
                <?php  echo $usernameErr ?>
              </span>
            <div class="form-group">
              <input type="email" class="form-control" name='email' placeholder="Email" value='<?php if(isset($_POST['email'])) echo $_POST['email']?>'>
            </div>
            <span class="errorMsg">
                <?php  echo $emailErr ?>
              </span>
            <div class="form-group">
              <input type="password" class="form-control" name='password' placeholder="Password" value='<?php if(isset($_POST['password'])) echo $_POST['password']?>'>
            </div>
            <span class="errorMsg">
                <?php  echo $passwordErr ?>
              </span>
            <div class="form-group">
              <input type="password" class="form-control" name='confirmPassword' placeholder="Confirm Password" value='<?php if(isset($_POST['confirmPassword'])) echo $_POST['confirmPassword']?>'>
            </div>
            <span class="errorMsg">
                <?php echo $confirmPasswordErr ?>
              </span>
            <input type="submit" class="btn signUpBtn" name='signUp' value='Sign Up'>
          </form>

          <div class="extraOption mt-4 d-flex flex-column align-items-center justify-content-center">
            <span class="font-weight-bold">Already Have an Account?</span>
            <a href="/Elearning/login.php" class="font-weight-bold">Login</a>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php include './includes/footer.php'; ?>