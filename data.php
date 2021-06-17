<?php
session_start();
include './includes/conn.php';
global $mysqli;

if(isset($_SESSION['user'])){
  $userId = $_SESSION['user']['USER_ID'];
}

$query = ("select AVG(Rating) AS Rating, Course_Title from reviews, course, tutor where reviews.Course_id = course.Course_id AND course.User_id = $userId GROUP BY reviews.Course_id");
$ratingQuery = $mysqli->query($query);

$data = array();
foreach ($ratingQuery as $row) {
	$data[] = $row;
}

$enroll = "Select count(Payment_id) AS TOTAL_PAYMENT, Payment_Date AS MONTH from payment, enroll, tutor, course where payment.Enroll_id = enroll.Enroll_id aND enroll.Course_id = course.Course_id AND enroll.Tutor_id = tutor.Tutor_id AND tutor.User_id = $userId Group BY Month(Payment_Date)";
$enrollQuery = $mysqli->query($enroll);

  foreach ($enrollQuery as $row) {
    $data[] = $row;
  }

$course = "select count(Payment_id) AS TOTAL, Course_Title AS Title from payment, enroll, course, tutor where payment.Enroll_id = enroll.Enroll_id AND enroll.Course_id = course.Course_id AND enroll.Tutor_id = tutor.Tutor_id AND tutor.User_id = $userId Group By Course_Title";
$courseQuery = $mysqli->query($course);
foreach ($courseQuery as $row) {
  $data[] = $row;
}

$ratingQuery->close();
print json_encode($data);

?>


