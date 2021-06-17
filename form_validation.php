
<?php 

include './includes/conn.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './vendor/phpmailer/phpmailer/src/Exception.php';
require './vendor/phpmailer/phpmailer/src/SMTP.php';
require './vendor/autoload.php';


function furtherValidation($firstName, $middleName,  $lastName, $username, $email, $password, $confirmPassword, $gender){
  
  global $mysqli;
  global $error;

  
  $Emailquery = $mysqli->query("Select * from user where Email = '$email'");
  $usernameQuery = $mysqli->query("Select * from user where username = '$username'");

  if ($Emailquery->num_rows>0){
    $error='This email is already registered in our system. Please choose another email';
  }

  if ($usernameQuery->num_rows>0){
    $error='This username is already registered in our system. Please choose another username';
  }

  
  if(!isset($error)){
    $password = password_hash($password, PASSWORD_DEFAULT);
    // $password = md5($password); //encrypt the password
    $user_activation_code = md5(time().$username);
   
    if(isset($_POST['signUp'])){
      $sql = "INSERT into user (First_Name, Middle_Name, Last_Name, username, Email, Password, user_type,verification_code, created_date, is_Verified, Status)
      Values ('$firstName', '$middleName', '$lastName', '$username', '$email', '$password', 'student', '$user_activation_code', CURRENT_TIMESTAMP, 0, 'passive')
      ";
      $insertQuery=$mysqli->query($sql);
      if($insertQuery){
       
        $_SESSION['Success'] = "<p>Your account has been successfully Created. </p>";
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
        <p><b>Thank you for signing up with us!!</b></p>
        <div>
          Your account has been created. You can now Log in with the following credentials.
        </div>
        <br />
        <div>
            <strong>Username:</strong> $username
            
            <br />
            <strong>Password: </strong> $password
        </div>
        <br />
        <div>
          Please click here to activate your account.
          <a href = 'http://localhost/Elearning/verify.php?vKey=$user_activation_code'>Activation Link</a>
        </div>
        <br />

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
          <?php echo 'Message could not be sent. Please try again' ;?></p>  
          <?php   
          
          
        } 
        else {?>
          <p class = "alert alert-success alert-dismissible py-4 font-weight-bold mt-4">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <?php echo "Congratulation! Your account has been created. 
          We've sent a mail to '$email'. Please use the provided verification link to proceed further" ;?></p>  
          <?php         
        }   
        
      }
      else{
        echo $mysqli->error;
      }
    }
    
    else if(isset($_POST['teachersignUp'])){
      $sql = "INSERT into user (First_Name, Middle_Name, Last_Name, username, Email, Password, user_type,verification_code, created_date, is_Verified, Status)
      Values ('$firstName', '$middleName', '$lastName', '$username', '$email', '$password', 'teacher', '$user_activation_code', CURRENT_TIMESTAMP, 0, 'passive')
      ";
      $insertQuery=$mysqli->query($sql);
      if($insertQuery){
       
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
        <p><b>Thank you for signing up with us!!</b></p>
        <div>
          Your account has been created. You can now Log in with the following credentials.
        </div>
        <br />
        <div>
            <strong>Username:</strong> $username
            
            <br />
            <strong>Password: </strong> $password
        </div>
        <br />
        <div>
          Please click here to activate your account.
          <a href = 'http://localhost/Elearning/verify.php?vKey=$user_activation_code'>Activation Link</a>
        </div>
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
        else {
          ?>
          <p class = "alert alert-success alert-dismissible py-4 font-weight-bold">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <?php echo "Congratulation! Your account has been created. 
          We've sent a mail to '$email'. Please use the provided verification link to proceed further" ;?></p>  
          <?php         
        }   
        
      }
      else{
        echo $mysqli->error;
      }
    }
  }
  
  return $error;

}
?>

