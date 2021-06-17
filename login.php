<?php include './includes/header.php'; 

include './login_form_validation.php';

$usernameoremail = $password ="";

$usernameoremailErr = $passwordErr = "";

if(isset($_POST['loginBtn'])){
  if(empty($_POST['usernameoremail'])){
    $usernameoremailErr = 'Please enter your Username or Email';
  }else{
    $usernameoremail = e($_POST['usernameoremail']);
  }
  if(empty($_POST['password'])){
    $passwordErr = 'Please enter your Password';
  }else{
    $password = e($_POST['password']);
  }


  if(!$usernameoremailErr && !$passwordErr ){
        
    furtherLoginValidation(e($_POST['usernameoremail']), ($_POST['password']));

    }
}

function e($val){
  return htmlEntities(trim($val), ENT_QUOTES);
}
?>


  <div class="login">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12">
          <div class="loginSidebar" data-aos='fade-left' data-aos-delay="10"  data-aos-offset="100">
            <h3 class="text-left mb-4">Welcome to <span class="font-italic">Tutor To Students</span></h3>
            
            <img src="./images/Blogging Minimalistic/blogging.svg" alt="">
            <p class="font-weight-bold my-4">A Virtual Platfom where we connect you with what you need</p>
            <span class="font-weight-bold">Login to get Started</span>
          </div>
          
        </div>
      
        <div class="col-lg-7 col-md-7 col-sm-12">
       
          <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" data-aos='fade-right' data-aos-delay="100"  data-aos-offset="10">
            <h3 class="text-center mb-4 text-uppercase">Login</h3>

            <?php if(isset($error)) echo '<span class="errorMsg serverErrorMsg mb-4">'.$error.'</span>'; ?>
            
            <div class="form-group">
              <input type="text" class="form-control" name='usernameoremail' placeholder="Email or Username">
            </div>
            <span class="errorMsg">
                <?php echo $usernameoremailErr; ?>
              </span>
            <div class="form-group">
              <input type="password" class="form-control" name='password' placeholder="Password">
            </div>
              <span class="errorMsg">
                <?php  echo $passwordErr ?>
              </span>

            <input type="submit" class="btn loginBtn" value="Login" name='loginBtn'>
          </form>

          <div class="extraOption mt-4 d-flex flex-column align-items-center justify-content-center">
            <a href="http://localhost/Elearning/forgot_password.php" class="mb-4 font-weight-bold">Forgot Password?</a>
            <span class="font-weight-bold">Don't Have an Account?</span>
            <button class="font-weight-bold" data-toggle="modal"data-target="#registerModal">Sign Up</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Want to connect with us?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="modalteacher">
          <p>Are you a Teacher?</p>
          <a href="/Elearning/teacher_signup.php" >Register Here</a>
        </div>
        <div class="modalstudent">
          <p>Are you a Student?</p>
          <a href="/Elearning/student_signup.php">Register Here</a>
        </div>
      </div>
      <div class="modal-footer">
        <p>Already Have an Account?</p>
        <a class="btn btn-secondary" href='/Elearning/login.php'>Login</a>
      </div>
    </div>
  </div>
</div>

<?php include './includes/footer.php'; ?>
