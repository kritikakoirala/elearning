<?php
  include './includes/conn.php';
  include './includes/header.php';

  if(isset($_SESSION['user'])){
    $userId = $_SESSION['user']['USER_ID'];
  }

  if(isset($_GET['editId'])){
    $courseId = $_GET['editId'];
  }

  $getCourses = $mysqli->query("select * from course where User_id = $userId AND Course_id = $courseId");
  $getCoursesResult = $getCourses->fetch_assoc();
  $titleErr = $descriptionErr = $durationErr = $levelErr = $classesErr = $minutesErr = $languageErr = $feeErr = $dateErr = $startTimeErr = $endTimeErr = $tagsErr = $videoErr = "";

  if(isset($_POST['editCourse'])){
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
      echo $name;
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
      $sql = "
      Update course SET 
       Course_Title  = '$title',
       Course_Description = '$description', 
       Tags = '$tags', 
       Long_Description = '$longDescription', 
       Course_Duration = '$duration', 
       Course_Level = '$level', 
       Course_Classes = '$classes', 
       Time_Per_class = '$minutes', 
       Course_Language = '$language', 
       Course_Enrollment_Fee = '$fee', 
       User_Id = $userId, 
       Course_Created = CURRENT_TIMESTAMP, 
       Sample_Video = '$video'

       where Course_id = $courseId
      ";

      $updateSql = $mysqli->query($sql);
      if($updateSql){
        move_uploaded_file($_FILES['file']['tmp_name'],$target_file);          
          $_SESSION['success_courseEdited'] = 'Course has been edited!';
          ?>
        
        <?php
        header('location:/Elearning/courses.php');
      }else{
        echo mysqli_error($mysqli);
      }
    }
}

function e($val){
  return htmlEntities(trim($val), ENT_QUOTES);
}
  
?>

  <div class="edit_courses">
    
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8">
        <h5 class="profileHeading">Edit your course</h5>
          <form method='POST' action="http://localhost/Elearning/edit_courses.php?editId=<?php echo $courseId;?>" enctype="multipart/form-data">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Course Title" name='title' value='<?php echo $getCoursesResult['Course_Title']?>'/>
          </div>
          <span class="errorMsg">
            <?php echo $titleErr ?>
          </span>
          <div class="form-group">
            <input type="text" class="tags form-control" placeholder="tags to be associated with your course. Eg. music, art" name="tags" value='<?php echo $getCoursesResult['Tags']?>'>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Short Course Description" name='description'  value='<?php echo $getCoursesResult['Course_Description']?>'/>
          </div>
          <span class="errorMsg">
            <?php echo $descriptionErr ?>
          </span>
          <div class="form-group">
            <textarea row='190' col='10' class="form-control" placeholder="Long Course Description" name='longDescription'> <?php echo $getCoursesResult['Long_Description']?> </textarea>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Course Duration e.g 2Weeks, 2 hours" name='duration'  value='<?php echo $getCoursesResult['Course_Duration']?>'/>
          </div>
          <span class="errorMsg">
            <?php  echo $durationErr ?>
          </span>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Course Level e.g Beginner, Advanced" name='level'  value='<?php echo $getCoursesResult['Course_Level']?>'/>
          </div>
          <span class="errorMsg">
            <?php  echo $levelErr ?>
          </span>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="How many classes are you going to conduct in a week e.g 1 class per week" name='classes'  value='<?php echo $getCoursesResult['Course_Classes']?>'/>
          </div>
          <span class="errorMsg">
            <?php  echo $classesErr ?>
          </span>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="How many minutes is each class long? e.g 2 hours" name='minutes'  value='<?php echo $getCoursesResult['Time_Per_class']?>'/>
          </div>
          <span class="errorMsg">
            <?php  echo $minutesErr
          ?>
          </span>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Instruction Language" name='language'  value='<?php echo $getCoursesResult['Course_Language']?>'/>
          </div>
          <span class="errorMsg">
            <?php echo $languageErr ?>
          </span>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Course Enrollment Fee (in Dollar)" name='fee'  value='<?php echo $getCoursesResult['Course_Enrollment_Fee']?>'/>
            
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
        
          <input type='submit' name='editCourse' class="btn addSchedule" value="Update" />
          </form>
        </div>
      </div>
    </div>
  </div>
<?php
  include './includes/footer.php';
?>