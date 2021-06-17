<?php

include './includes/conn.php';

function furtherLoginValidation($usernameoremail,$password){
  
  global $mysqli;
  global $error;
  
  $selectQuery = $mysqli->query("Select * from user where (username='$usernameoremail' OR email='$usernameoremail') AND Is_Verified = 1 AND Status = 'active'");

  $selectResult = $selectQuery->fetch_assoc();


  if($selectQuery->num_rows==1){
    $createdDate = $selectResult['created_date'];
    $email = $selectResult['Email'];
    if(password_verify($password,$selectResult['Password'])){
      if($selectResult['Is_Verified']!==0){
        if($selectResult['user_type']=='admin'){
          $_SESSION['user'] = getUserById($selectResult['User_id']);
          header('location: admin/index.php');	
          exit;
        }elseif($selectResult['user_type']=='student'){
          $_SESSION['user'] = getUserById($selectResult['User_id']);

          header('location: student_notification.php');	
          exit;
        }else{
          $_SESSION['user'] = getUserById($selectResult['User_id']);
          $_SESSION['success']  = "You are now logged in";
          header('location: teacher_dashboard.php');	
          exit;
        }
      }else{
        $error="This account is not yet verified. An email was sent to '$email' on '$createdDate'.Please verify your account first.";
      }
    }else{
      $error='Wrong username or password';
    }
  }else{
    $error='The username entered is unregistered in our system. Please register before login';
  }
 
  return $error;
}

function getUserById($id){
	global $mysqli;
	$sql = "SELECT * FROM user WHERE User_id=" . $id;
  $sessionQuery = $mysqli->query($sql);
  $sessionResult = $sessionQuery->fetch_assoc();
  if($sessionQuery->num_rows>0){
    $user['USER_TYPE'] = $sessionResult['user_type'];
    $user['USER_ID'] = $sessionResult['User_id'];
    $user['USERNAME'] = $sessionResult['username'];
    // echo $user['USER_TYPE'];
  }

	return $user;
}

?>