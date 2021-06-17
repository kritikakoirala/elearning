<?php
  include './includes/conn.php';
  include './includes/header.php';
  include './paypal/config.php';

  global $mysqli;

  if(isset($_SESSION['course'])){
    $course = $_SESSION['course'];
 
  }
  if(isset($_SESSION['tutor'])){
    $tutor = $_SESSION['tutor'];
   
  }
  if(isset($_SESSION['student'])){
    $student = $_SESSION['student'];
   
  }
  

    $query = $mysqli->query("select * from enroll where Student_id = $student AND Tutor_id = $tutor AND Course_id = $course");   
    $queryResult = $query->fetch_assoc();
    if($query->num_rows>0){
      $_SESSION['enroll'] = $queryResult['Enroll_id'];

    }


  function e($val){
    return htmlEntities(trim($val), ENT_QUOTES);
  }
  
?>


  <div class="confirmPay mt-4" data-aos='zoom-in'>
    <h2 class="my-3 text-center">Pay with Payal!</h2>
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="confirmBox mt-4 d-flex flex-column justify-content-center align-items-center">
          <!-- <img src='./images/pay.svg' alt='pay' class='payImg mt-2 text-right'/> -->
          
            <img src='./images/paypal.png' alt='paypal'/>
            <form action="<?php echo PAYPAL_URL; ?>" method="GET">
              <input type="hidden" name="cmd" value="_xclick" />

              <!-- Identify your business so that you can collect the payments. -->

              <input type='hidden' name='business' value='<?php echo PAYPAL_ID;?>'>                                             

              <!-- Specify details about the item that buyers will purchase. -->
             
              <input type='hidden' name='course_id' value='<?php echo $course;?>'> 

              <input type='hidden' name='tutor_id' value='<?php echo $tutor;?>'> 
              <input type='hidden' name='student_id' value='<?php echo $student;?>'> 
              <input type='hidden' name='amount' value='<?php echo $queryResult['Enrollment_Fee'];?>'>
              <input type='hidden' name='currency_code' value='<?php echo PAYPAL_CURRENCY;?>'>
             

              <!-- Specify URLs -->
              <input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL; ?>">
              <input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">
              <input type="hidden" name="notify_url" value="<?php echo PAYPAL_NOTIFY_URL; ?>">

              <!-- Specify a Buy Now button. -->
              <input type="submit" class='btn mt-4' name="pay_now" id="pay_now" Value="Pay Now">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php
include './includes/footer.php'
?>