<?php
  include '../includes/conn.php';
  include 'includes/header.php';
  global $mysqli;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../vendor/autoload.php';


  
?>  

<div class="wrapper">
  <?php include 'includes/sideNavigation.php'; ?>
  <div class="content">
    <?php include './includes/topHeader.php'; ?>
    <div class="all_students">
    <?php
      if(isset($_POST['disable'])){
        $userId = $_POST['userId'];
        $email = $_POST['email'];
    

        $check  = $mysqli->query("Select * from user where User_id = $userId AND Status = 'active'");
        if($check->num_rows==1){
          $updateStatus = $mysqli->query("update user SET Status = 'passive' where Status = 'active' AND User_id = $userId");
          if($updateStatus){
    
            $current = date('Y-m-d H:i:sa');
         
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
            $mail->Subject = 'Account Disabled';
            $mail->Body    = "
    
            <html>
            <body>
            <p>
              Your account was disabled by the <i>Tutors To You</i> admin on $current. 
              
            </p>
            <p>Please Contact the administrator for further information.</p>
            <br/>
            <p><strong>Note: </strong> You cannot login until your account is active again. </p>
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
              <p class = "alert alert-success alert-dismissible py-4 font-weight-bold">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <?php echo "You have successfully disabled the user. A mail is sent to them. Please expect to hear from them.";?></p>  
              <?php         
            }   
          }
        }
       
      }
    
      if(isset($_POST['enable'])){
        $userId = $_POST['userId'];
        $email = $_POST['email'];

        $check  = $mysqli->query("Select * from user where User_id = $userId AND Status = 'passive'");
        if($check->num_rows==1){
          $updateStatus = $mysqli->query("update user SET Status = 'active' where Status = 'passive' AND User_id = $userId");
      
          if($updateStatus){
            $current = date('Y-m-d h:i:s a');
          
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
              $mail->Subject = 'Account Enabled';
              $mail->Body    = "
      
              <html>
              <body>
              <p>
                Your account was enabled by the <i>Tutors To You</i> admin on $current. 
                
              </p>
              <p>Please Contact the administrator for further information.</p>
              <br/>
              <p><strong>Note: </strong> You can now successfully login as your account is active again. </p>
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
                <p class = "alert alert-success alert-dismissible py-4 font-weight-bold">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <?php echo "You have successfully enabled the user. ";?></p>  
                <?php         
              }   
          }
        }
       
      }
    ?>
    <div class="container mt-4">
        <div class="row col-lg-12 col-md-12 col-sm-12">
          <table id ='dataTable' class="table table-bordered mt-4">
            <thead>
              <tr>
                <th>Profile</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Address</th>
                <th>Number</th>
                <th>Gender</th>
                <th>Status</th>
                <th></th>
                
              </tr>
            </thead>
            <tbody>
              <?php
                $getCourse= $mysqli->query("select * from student, user where student.User_id = user.User_id");
                
                $getCourseResult = $getCourse->fetch_all(MYSQLI_ASSOC);
                if($getCourse->num_rows>0){
                  foreach($getCourseResult as $row){
                    ?>
                      <tr>
                        <td><?php
                          if(!empty($row['Profile_Image'])){
                            ?>
                              
                              <img class="d-flex flex-row justify-content-center align-items-center" src="../<?php echo $row['Profile_Image'];?>" alt="profile">
                            <?php
                          }else{?>
                            <i class='fas fa-user-circle mb-4'></i>
                          <?php
                          }
                        ?></td>
                        <td><?php echo $row['First_Name'].' '. $row['Middle_Name'].' '.$row['Last_Name'];?></td>
                        <td><?php echo $row['username'];?></td>
                        <td><?php echo $row['Email'];?></td>
                        <td><?php echo $row['Address'];?></td>
                        <td><?php echo $row['Number'];?></td>
                        <td><?php echo $row['Gender'];?></td>
                        <td class="status"><?php echo $row['Status'];?></td>
                        
                        <td>
                        <?php
                          if($row['Status']=='active'){
                            ?>
                            <button class="btn" data-toggle="modal" data-target="#deactivateModal">Deactivate</button>

                            <?php
                          }else{
                            ?>
                              <button class="btn" data-toggle="modal" data-target="#activateModal">Activate</button>
                            <?php
                          }
                        ?>
                        </td>
                        
                        <div class="modal fade" id="deactivateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <form method='post' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <div class="modal-header">
                                  
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>

                                <div class="modal-body">
                                  <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to disable this user? </h5>
                                  <input type="hidden" name='userId' value=<?php echo $row['User_id']?> >
                                  <input type="hidden" name="email" value=<?php echo $row['Email']?>>

                                </div>
                                
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                  <input type="submit" class="btn btn-primary" value="Yes" name="disable"/>
                                </div>
                                </form>
                            </div>
                          </div>
                        </div>


                        <div class="modal fade" id="activateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <form method='post' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <div class="modal-header">
                                  
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>

                                <div class="modal-body">
                                  <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to enable this user? </h5>
                                  <input type="hidden" name='userId' value=<?php echo $row['User_id']?>>
                                  <input type="hidden" name="email" value=<?php echo $row['Email']?>>
                                </div>
                                
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                  <input type="submit" class="btn btn-primary" value="Yes" name="enable"/>
                                </div>
                                </form>
                            </div>
                          </div>
                        </div>
                      </tr>
                    <?php
                  }
                }
              ?>
            </tbody>
          </table>

          
        </div>
      </div>
    </div>
    <?php include 'includes/footer.php'; ?>
  </div>
</div>