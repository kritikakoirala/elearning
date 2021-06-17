<?php 
ob_start();
include './includes/conn.php';
include './includes/header.php';

global $mysqli;

if(isset($_SESSION['user'])){
  $userId = $_SESSION['user']['USER_ID'];
}

// $title = $description = $duration = $level = $classes = $minutes = $language = $date = $startTime = $endTime = "";
$titleErr = $descriptionErr = $durationErr = $levelErr = $classesErr = $minutesErr = $languageErr = $feeErr = $dateErr = $startTimeErr = $endTimeErr = $tagsErr = $videoErr = "";
if(isset($_POST['addCourse'])){

  $longDescription = $_POST['longDescription'];

  if(empty($_POST['title'])){
    $titleErr = 'Please enter the title of the course';
  }else{
    $title = e($_POST['title']);
  }
  if(empty($_POST['description'])){
    $descriptionErr = 'Please enter a short description of your course';
  }else{
    $description = e($_POST['description']);
  }
  if(empty($_POST['duration'])){
    $durationErr = 'Please enter the duration of your project';
  }else{
    $duration = e($_POST['duration']);
  }
  if(empty($_POST['level'])){
    $levelErr = 'Please enter the level of your course. Eg Beginner, Advanced';
  }else{
    $level = e($_POST['level']);
  }
  if(empty($_POST['classes'])){
    $classesErr = 'Please enter how many classes are you going to conduct in a week';
  }else{
    $classes = e($_POST['classes']);
  }
  if(empty($_POST['minutes'])){
    $minutesErr = 'Specify how long is each class long';
  }else{
    $minutes = e($_POST['minutes']);
  }
  if(empty($_POST['language'])){
    $languageErr = 'Specify your choice of intruction language';
  }else{
    $language = e($_POST['language']);
  }
  if(empty($_POST['fee'])){
    $feeErr = 'Please enter the fee for students to enroll to this course';
  }else{
    $fee = e($_POST['fee']);
  }
  if(empty($_POST['tags'])){
    $tagsErr = "Please enter the tags for your course";
  }else{
    $tags = $_POST['tags'];
  }
  $maxsize = 10000000; 
  if(isset($_FILES['file']['name'])){

    $name = $_FILES['file']['name'];
    
    $target_dir = "videos/";
    $target_file = $target_dir . $_FILES["file"]["name"];
    $extension = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $extensions_arr = array("mp4","avi","3gp","mov","mpeg", "webm");
    if( in_array($extension,$extensions_arr) ){
 
      // Check file size
      if(($_FILES['file']['size'] >= $maxsize) || ($_FILES["file"]["size"] == 0)) {
         $videoErr = "File too large. File must be less than 5MB.";
      }else{
        $video = $target_file; 
      }
   }
  }

  
  if(!$titleErr && !$descriptionErr && !$durationErr && !$levelErr && !$classesErr && !$minutesErr && !$languageErr && !$feeErr && !$tagsErr && !$videoErr){

    // $video_path=$_FILES['fileToUpload']['name'];

    $checkCourse = $mysqli->query("select * from course where User_id = $userId AND Course_Title = '$title'");

    $checkResult = $checkCourse->fetch_assoc();
    if($checkCourse->num_rows==0){
    $sql = "INSERT INTO course (Course_Title, Course_Description, Tags, Long_Description, Course_Duration, Course_Level, Course_Classes, Time_Per_class, Course_Language, Course_Enrollment_Fee, User_Id, Course_Created, Sample_Video) 
        VALUES ('$title', '$description', '$tags', '$longDescription', '$duration' , '$level', '$classes', '$minutes', '$language','$fee', $userId, CURRENT_TIMESTAMP, '$video')
        ";
        $insertQuery = $mysqli->query($sql);
        echo $mysqli->error;

        if($insertQuery){
          move_uploaded_file($_FILES['file']['tmp_name'],$target_file);
          $_SESSION['course'] = $title;
          
          $_SESSION['success_courseCreated'] = 'New Course has been created!';
          ?>
        
        <?php
        header('location:/Elearning/teacher_dashboard.php');
        }
    }

    
  }else{
    echo $mysqli->error;
  }
}

function e($val){
  return htmlEntities(trim($val), ENT_QUOTES);
}


?>

<div class="courseDashboardTopNav">
  <?php include './includes/topNav.php'; ?>
</div>


<div class="addNewCourse">
  <h5 class="mb-4">Add New Course</h5>
  <div class="container-fluid pl-0">
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-12">
        <form method='POST' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Course Title" name='title' value='<?php if(isset($_POST['title'])) echo $_POST['title']?>'/>
          </div>
          <span class="errorMsg">
            <?php echo $titleErr ?>
          </span>
          <div class="form-group">
            <input type="text" class="tags form-control" placeholder="tags to be associated with your course. Eg. music, art" name="tags">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Short Course Description" name='description' value='<?php if(isset($_POST['description'])) echo $_POST['description']?>'/>
          </div>
          <span class="errorMsg">
            <?php echo $descriptionErr ?>
          </span>
          <div class="form-group">
            <textarea row='190' col='10' class="form-control" placeholder="Long Course Description" name='longDescription' value='<?php if(isset($_POST['longDescription'])) echo $_POST['longDescription']?>'></textarea>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Course Duration e.g 2Weeks, 2 hours" name='duration' value='<?php if(isset($_POST['duration'])) echo $_POST['duration']?>'/>
          </div>
          <span class="errorMsg">
            <?php  echo $durationErr ?>
          </span>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Course Level e.g Beginner, Advanced" name='level' value='<?php if(isset($_POST['level'])) echo $_POST['level']?>'/>
          </div>
          <span class="errorMsg">
            <?php  echo $levelErr ?>
          </span>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="How many classes are you going to conduct in a week e.g 1 class per week" name='classes' value='<?php if(isset($_POST['classes'])) echo $_POST['classes']?>'/>
          </div>
          <span class="errorMsg">
            <?php  echo $classesErr ?>
          </span>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="How many minutes is each class long? e.g 2 hours" name='minutes' value='<?php if(isset($_POST['minutes'])) echo $_POST['minutes']?>'/>
          </div>
          <span class="errorMsg">
            <?php  echo $minutesErr
          ?>
          </span>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Instruction Language" name='language' value='<?php if(isset($_POST['language'])) echo $_POST['language']?>'/>
          </div>
          <span class="errorMsg">
            <?php  echo $languageErr
          ?>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Course Enrollment Fee (in Dollar)" name='fee' value='<?php if(isset($_POST['fee'])) echo $_POST['fee']?>'/>
            
          </div>
          <span class="errorMsg">
            <?php  echo $feeErr;
          ?>
          </span>
          <div class="form-group">
            <label for="video">Upload Sample video for your students.</label>
            <input type="file" class="form-control" name='file'>
            
          </div>
          <span class="errorMsg">
            <?php  echo $videoErr ?>
          </span>
        
          <input type='submit' name='addCourse' class="btn addSchedule" value="Create" />
        </form>
      </div>
    </div>
    
  </div>
</div>

<?php include './includes/footer.php'; ?>