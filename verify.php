
<?php

include "./includes/conn.php";      
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="CleckHuddersfax Website">
    <meta name="keywords" content="CleckHudddersfax, shopping, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CleckHuddersfax Online Mart</title>
    <link rel="stylesheet" href="../Elearning/css/index.css">

    
<style>


body{
  background-color:#fff;
  margin:0 auto;
}

.verification{
  min-height: 100vh;
}

.verification h2{
  margin-bottom: 1em;
}

.verification, .verification-success{
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.verification img{
  width: 30%;
  height: 30%;
  object-fit: cover;
}

.verification-success p{
  color: #3C5C91;
  margin: 2.5em 0;
  font-size: 1em;
  font-weight: bold;
  text-align: center;
}

.verification a{
  text-decoration: none;
}

.verification-error{
  color: #3C5C91;
  margin: 1em 0;
  font-size: 1.2em;
  font-weight: bold;
  text-align: center;
}

@media (max-width:600px) {
  .verification{
    margin:0 1em;
  }
    .verification-success p{
    font-size: .9em;
  }

}

</style>

<body>
<?php
if(isset($_GET['vKey'])){
  $vKey = $_GET['vKey'];   

  global $mysqli;

  $sql = "select * from user where Is_Verified = 0 AND verification_code = '$vKey'";

  $selectQuery = $mysqli->query($sql);
  $selectResult = $selectQuery->fetch_assoc();


  

  // echo $emailResult;

  if($selectQuery->num_rows>0){
    $emailResult = $selectResult['Email'];
    if($selectResult['Is_Verified']==0){

    $updateSql="update user SET Is_Verified = 1 where verification_code = '$vKey'";

    $updateQuery = $mysqli->query($updateSql);
     
      if($updateQuery){
        
        $insertStudentSql = "INSERT into student (User_id) SELECT User_id from user where user_type = 'student' AND Email = '$emailResult'";
        $insertStudentQuery = $mysqli->query($insertStudentSql);

        $insertTutorSql = "INSERT into tutor (User_id) SELECT User_id from user where user_type = 'teacher' AND Email = '$emailResult'";
        $insertTutorQuery = $mysqli->query($insertTutorSql);

        $updateStatus = "update user SET Status = 'active' where Status = 'passive'";
        $updateStatusQuery = $mysqli->query($updateStatus);


        if($insertStudentQuery||$insertTutorQuery){
          ?>
            
            <section class="verification">
            
            <h2>Welcome To Tutors to You !</h2>
            <div class="verification-success">
            
            <img src="./images/landingPage.svg" alt="">
            <p>Your Email was successfully verified. Please Proceed to Login to enter the website.</p>
            <a href="login.php" class="btn">Login</a>
            
            </div>
            </section>
          <?php
        }

      }else{
        ?>
        <div class="verification-error">
           <p>Your Email is already verified. 
            <br />
            You can go ahead!
            <br />
           <a href="login.php" class="btn">Login</a></p>
        </div>
    <?php
    }
    }
  }
    
}


else{
  echo "Invalid login";
}


//insert into admin

$adminSql = "Select Email from user where user_type='admin'";
$adminQuery = $mysqli->query($adminSql);
$adminResult = $adminQuery->fetch_assoc();

if($adminQuery->num_rows>0){
  $email = $adminResult['Email'];
  

  $checkAdminSql = "Select * from admin, user where admin.User_id = user.User_id";
  $checkAdminQuery = $mysqli->query($checkAdminSql);
  echo $checkAdminQuery->num_rows;
  if($checkAdminQuery->num_rows==0){
    $inserAdminQuery = $mysqli->query("insert into ADMIN (USER_ID) Select USER_ID FROM user WHERE user_type='admin' AND EMAIL = '$email'");
  }
}

?>
</body>
</html>