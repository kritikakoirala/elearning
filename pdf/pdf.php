<?php

  include '../includes/conn.php';
  require '../vendor/autoload.php';
  require 'CustomPdf.php';

  global $mysqli;
  if(isset($_SESSION['enroll'])){
    $enrollId = $_SESSION['enroll'];
  }

  $enrollQuery = $mysqli->query("Select * from enroll, payment, student, tutor, course where enroll.Enroll_id = payment.Enroll_id AND  enroll.Enroll_id = $enrollId AND enroll.Student_id = student.Student_id AND enroll.Tutor_id = tutor.Tutor_id AND enroll.Course_id = course.Course_Id");
  $enrollResult = $enrollQuery->fetch_assoc();

  $paymentDate = $enrollResult['Payment_Date'];
  $title = $enrollResult['Course_Title'];
  $duration = $enrollResult['Course_Duration'];
  $class = $enrollResult['Course_Classes'];
  $fee = $enrollResult['Enrollment_Fee'];

  $studentQuery = $mysqli->query("select * from user, student, enroll where enroll.Enroll_id = $enrollId AND enroll.Student_id = student.Student_id AND student.User_id = user.User_id");

  $studentResult = $studentQuery->fetch_assoc();
  $studentName = $studentResult['First_Name'].' '.$studentResult['Middle_Name'].' '. $studentResult['Last_Name'];
  $studentEmail = $studentResult['Email'];

  $tutorQuery = $mysqli->query("select * from user, tutor, enroll where enroll.Enroll_id = $enrollId AND enroll.Tutor_id = tutor.Tutor_id AND tutor.User_id = user.User_id");
  $tutorResult = $tutorQuery->fetch_assoc();
  $tutorName = $tutorResult['First_Name'].' '. $tutorResult['Middle_Name'] .' '. $tutorResult['Last_Name'];
  $tutorEmail = $tutorResult['Email'];


  $pdf = new CustomPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setFontSubsetting(true);
  $pdf->SetFont('dejavusans', '', 12, '', true);
   
  // start a new page
  $pdf->AddPage();
   
  // date and invoice no
  $pdf->Write(0, "\n", '', 0, 'C', true, 0, false, false, 0);
  $pdf->writeHTML("<b>DATE:</b> $paymentDate");
 
  $pdf->Write(0, "\n", '', 0, 'C', true, 0, false, false, 0);
   
  // address
  $pdf->writeHTML("<b>Tutor Details</b>");
  $pdf->writeHTML("$tutorName");
  $pdf->writeHTML("$tutorEmail");
  $pdf->Write(0, "\n", '', 0, 'C', true, 0, false, false, 0);
   
  // bill to
  $pdf->writeHTML("<b>BILL TO:</b>", true, false, false, false, 'R');
  $pdf->writeHTML("$studentName", true, false, false, false, 'R');
  $pdf->writeHTML("$studentEmail", true, false, false, false, 'R');
  $pdf->Write(0, "\n", '', 0, 'C', true, 0, false, false, 0);

    // address
    $pdf->writeHTML("<b>Course Details:</b>");
    $pdf->writeHTML("<b>Course Title: </b> $title");
    $pdf->writeHTML("<b>Course Duration:</b> $duration");
    $pdf->writeHTML("<b>Course Classes:</b> $class");
    $pdf->writeHTML("<b>Enrollment Fee: </b> $fee");
    $pdf->Write(0, "\n", '', 0, 'C', true, 0, false, false, 0);
   
   
  // comments
  $pdf->SetFont('', '', 12);
  $pdf->Write(0, "\n\n\n", '', 0, 'C', true, 0, false, false, 0);
  $pdf->writeHTML("If you have any questions about this invoice, please contact:", true, false, false, false, 'C');
  $pdf->writeHTML("tutorToStudents@gmail.com", true, false, false, false, 'C');
 
// save pdf file
  $pdf->Output(__DIR__ . './ProductInvoice.pdf', 'F');

?>