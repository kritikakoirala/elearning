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


  $emailErr= "";
  if(isset($_POST['submitEmail'])){
    if(empty($_POST['email'])){
      $emailErr = 'Please enter your Username or Email';
    }else{
      $email = e($_POST['email']);
    }

    $checkEmail = $mysqli->query("select * from user where Email = '$email'");
    if($checkEmail->num_rows>0){

      $checkEmailResult = $checkEmail->fetch_assoc();

      $userId = $checkEmailResult['User_id'];
      $username = $checkEmailResult['username'];
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
      $mail->Subject = 'Forgot Password';
      $mail->Body    = "

      <html>
      <body>
      <h2 class='text-center'><b>Forgot Your Password?</b></h2>
      <div>
        <span>Hi $username, </span>
        
        <p>
          Did you forget your password?
        </p>
       
      </div>

      <p>
        <strong>
          Click the link below to change your password.
        </strong> 
      </p>
      
      <span>
        <a href = 'http://localhost/Elearning/reset_password.php?uId=$userId'>Reset Link</a>
      </span>
     
      <p>
        Kindly ignore the mail if you did not want to change the password.
      </p>
      <br />
      <p>Thank you!</p>
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
        <?php echo 'Message could not be sent. Please try again' ;?></p>  
        <?php   
        
        
      } 
      else {?>
        <p class = "alert alert-success alert-dismissible py-4 font-weight-bold mt-4">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <?php echo "We have sent you an email with a link to reset your password. ";?></p>  
        <?php         
      }   
    }else{
      $emailErr = "We don't have any user with this email in our system. Please type the correct email";
    }
  }

  function e($val){
    return htmlEntities(trim($val), ENT_QUOTES);
  }
?>

  <div class="forgotPassword">
    <h5 class="text-center">Forgot Your password?</h5>
    <div class="container">
      <div class="row d-flex justify-content-center align-items-center">
        <div class="col-lg-8 col-md-8 col-sm-12">
          <form class="forgotModal" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" data-aos='fade-right' data-aos-delay="100"  data-aos-offset="10">
          <i class="fas fa-user-lock fa-4x d-flex justify-content-center align-items-center mb-4 mt-4"></i>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Enter Your Email" name="email">
            </div>
            <span class="errorMsg">
                <?php echo $emailErr; ?>
              </span>

            <input class="btn" type="submit" name="submitEmail" value='Submit'>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php
  include './includes/footer.php';
?>