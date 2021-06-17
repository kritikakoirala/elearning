<?php
  include './includes/conn.php';

  if(isset($_POST['action'])){
    if(isset($_POST['action'])=='Add'){
      echo $_POST["doctor_schedule_date"];
      echo "yesss";
    }else{
      echo "no";
    }
  }else{
    echo "noooooooooooooooooooo";
  }
?>