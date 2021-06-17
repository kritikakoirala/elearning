<?php
  include '../includes/conn.php';
  include '../includes/header.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
  require '../vendor/phpmailer/phpmailer/src/Exception.php';
  require '../vendor/phpmailer/phpmailer/src/SMTP.php';
  require '../vendor/autoload.php';

  global $mysqli;

  if(isset($_SESSION['enroll'])){
    $enrollId = $_SESSION['enroll'];
    
  }

  if(isset($_SESSION['user'])){
    $userId = $_SESSION['user']['USER_ID'];
  }

  if(!empty($_GET['tx']) && !empty($_GET['amt']) && !empty($_GET['cc']) && !empty($_GET['st'])){
    $payDate = $_GET['payment_date'];
    $paymentStatus = $_GET['payment_status'];
    $getCurrentEnrollQuery = $mysqli->query("select * from enroll where Enroll_id = $enrollId");

    $updateEnroll = $mysqli->query("update enroll set Enroll_Status = 'completed' Where Enroll_id = $enrollId");

    if($updateEnroll){
      
     
        $tutorEmail = $mysqli->query("select * from enroll, tutor, user where enroll.Tutor_id = tutor.Tutor_id AND tutor.User_id = user.User_id AND enroll.Enroll_id = $enrollId ");
        $tutorEmailResult = $tutorEmail->fetch_assoc();
        $tutorEmail = $tutorEmailResult['Email'];

        $paymentVerificationQuery = $mysqli->query("select * from enroll, payment, tutor, student, user where enroll.Enroll_id = payment.Enroll_id AND enroll.Tutor_id = tutor.Tutor_id AND tutor.User_id = user.User_id AND enroll.Enroll_id = $enrollId");

        $paymentResult = $paymentVerificationQuery->fetch_assoc();
       
         
        if($paymentVerificationQuery->num_rows==0){ 
          $paymentQuery = $mysqli->query("insert into payment (Enroll_id, Payment_Date, Payment_Status) Values ($enrollId, '$payDate', '$paymentStatus')");
        }

        $paymentCheckQuery = $mysqli->query("select * from payment where Enroll_id = $enrollId");

          if($paymentCheckQuery){
            $emailQuery = $mysqli->query("select * from user where User_id = $userId");
            $emailResult = $emailQuery->fetch_assoc();
            $studentEmail = $emailResult['Email'];



            $address = array($tutorEmail, $studentEmail);
            

            require_once '../pdf/pdf.php';
            // require_once './pdf/pdf.php';
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
            foreach($address as $email){
              $mail->addAddress($email);   
              // echo $email;
            }
             
            $mail->addReplyTo('tutorToStudents@gmail.com');
            $mail->isHTML(true);                                  
            $mail->Subject = 'Payment Details';
            $mail->Body    = "
            <html?>
              <body>
                <p><b>Thank you for your payment!!</b></p>
                <div>
                <strong?> Please see, We have attached your payment details. </strong>
                </div>
                <br />
                <p>Thank you!</p>
              
                <strong>Team, Tutors To You.</strong>
              </body>
            </html>
            ";
            $mail->addAttachment('../pdf/ProductInvoice.pdf', '../pdf/ProductInvoice.pdf');

            $mail->AltBody = 'This is your payment Details';

            
            if($mail->send()) {
              unlink('../pdf/ProductInvoice.pdf');
            } 
               
          }
          
      
    }else{
      echo ('something is wrong. Please try again.');
    }


  }

  if($updateEnroll){
    ?>

    <div class="paymentSuccess" data-aos='zoom-in'>
      <div class="container">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="message text-center">
              <h3>Thank you for your payment!!.</h3>
              <p class="my-4">We have mailed you your payment details. Please check your email. </p>
              <span class="font-weight-bold">Also, please check your dashboard for your futher schedules. </span>
              <a href = 'http://localhost/Elearning/student_dashboard.php' class="btn mt-4">Go to Dashboard</a>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php
}
  include '../includes/footer.php';
?>