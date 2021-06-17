<?php

include './includes/conn.php';
include './includes/header.php';

  if(isset($_GET['uId'])){
    $user_id = $_GET['uId'];
  }

  $passwordErr = "";

  if (isset($_POST['resetPass'])) {
    if (empty($_POST['newPass'])){
      $passwordErr = 'Please enter a password';
    } else{
      $password = e($_POST['newPass']);
    }

    if ($_POST['newPass'] && !preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $_POST['newPass'])){
      $passwordErr = "Password should be at 8 characters at minimum and contain at least one Uppercase letter, one number and one symbol";
    }

    if (isset($_POST['newPass']) && $_POST['newPass'] !== $_POST['confirmPass']) {
      $passwordErr= 'The two passwords do not match';
    }

    if(!$passwordErr){
      $password = password_hash($password, PASSWORD_DEFAULT);

      $updateQuery = $mysqli->query("update user SET password = '$password' WHERE User_id = $user_id");

      if($updateQuery){?>
        <p class = "alert alert-success alert-dismissible py-4 font-weight-bold mt-4" data-aos='slide-down'>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
            Password has been successfully reset. You can login now. 
            <strong><a href="http://localhost/Elearning/login.php">Login</a></strong>
            
          </p> 
          <?php
                           
      }
    }
  }

  function e($val){
    return htmlEntities(trim($val), ENT_QUOTES);
  }
?>

  <div class="reset_password">
  <h5 class="text-center">Reset Your password</h5>
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <form action="reset_password.php?uId=<?php echo $user_id?>" method="post" class="resetModal">
          <i class="fas fa-unlock fa-4x d-flex justify-content-center align-items-center mb-4 mt-4"></i>
            <div class="form-group">
              <input type="password" class="form-control" name = 'newPass' placeholder="New Password">
            </div>
            <span class="errorMsg">
                <?php echo $passwordErr; ?>
              </span>
            <div class="form-group">
              <input type="password" class="form-control" name="confirmPass" placeholder="Confirm New Password">
            </div>
            <span class="errorMsg">
                <?php echo $passwordErr; ?>
              </span>
            <input class="btn" type="submit" value="Reset" name="resetPass">
          </form>
        </div>
      </div>
    </div>
  </div>

<?php
  include './includes/footer.php';
?>
