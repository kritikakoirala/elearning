<?php 
  include '../includes/conn.php';

  session_start();

  $query = ("select AVG(Rating) AS Rating, Course_Title from reviews, course, tutor where reviews.Course_id = course.Course_id GROUP BY reviews.Course_id");
  $ratingQuery = $mysqli->query($query);

  $data = array();
  foreach ($ratingQuery as $row) {
    $data[] = $row;
  }

  $student = ("Select count(Student_id) as Total_Students, Course_Title As Course from enroll, course WHERE enroll.Course_id = course.Course_id AND Enroll_Status = 'completed' GROUP BY enroll.Course_id Order BY count(Student_id) LIMIT 4");
  $studentQuery = $mysqli->query($student);

  foreach ($studentQuery as $row) {
    $data[] = $row;
  }

  $enroll = "Select count(Payment_id) AS TOTAL_PAYMENT, Payment_Date AS MONTH from payment, enroll, tutor, course where payment.Enroll_id = enroll.Enroll_id aND enroll.Course_id = course.Course_id AND enroll.Tutor_id = tutor.Tutor_id Group BY Month(Payment_Date)";
  $enrollQuery = $mysqli->query($enroll);

  foreach ($enrollQuery as $row) {
    $data[] = $row;
  }

  $ratingQuery->close();
  print json_encode($data);
?>