<?php
  include './includes/conn.php';
  include './includes/header.php';


  global $page, $page_total_page;

  if(isset($_GET['submit'])){

    $search = htmlentities($_GET['search'], ENT_QUOTES);

    if(isset($_GET['search'])){
      $search = htmlentities($_GET['search'], ENT_QUOTES);	
    }

    if(isset($_GET['page'])){
      $page = $_GET['page'];
    }
    else{
      $page=1;
    }

    $results_per_page = 2;
    $start_from = ($page-1)*$results_per_page;
      
    $searchQuery = $mysqli->query("Select * from course where Course_Title LIKE '%$search%' OR Tags LIKE '%$search%' LIMIT $results_per_page OFFSET $start_from");

    $searchResult = $searchQuery->fetch_all(MYSQLI_ASSOC);
    ?>

    <div class="searchWrapper">
      <div class="container-fluid">
        <div class="row">
          <?php
        
            if($searchQuery->num_rows>0){
              foreach ($searchResult as $row) {
               
                ?>
                  <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card">
                      <div class="card-header">
                        <p><?php echo $row['Course_Title']?></p>
                      </div>
                      <div class="card-body">
                        <p><span class="font-weight-bold"> Course Duration: </span><?php echo $row['Course_Duration']?></p>
                        <p><span class="font-weight-bold"> Course Level: </span><?php echo $row['Course_Level']?></p>
                        <p><span class="font-weight-bold"> Classes Per Week: </span><?php echo $row['Course_Classes']?></p>
                        <p><span class="font-weight-bold"> Time Per Class: </span><?php echo $row['Time_Per_class']?></p>
                        <p><span class="font-weight-bold"> Course Language: </span><?php echo $row['Course_Language']?></p>
                        <p><span class="font-weight-bold"> Course Enrollment Fee: </span><?php echo $row['Course_Enrollment_Fee']?></p>
                      </div>
                    </div>
                  </div>
                <?php
              }
            }
            $paginationuery = $mysqli->query("Select * from course where Course_Title LIKE '%$search%' OR Tags LIKE '%$search%'");
            $paginationResult = $paginationuery->num_rows;
            
            $page_total_rec=$paginationResult;
           

            $page_total_page=ceil($page_total_rec/$results_per_page);
           
          ?>
          
        </div>
        <ul class="pagination justify-content-center mt-4">
            <?php

              if($page>1)
              {	
                echo '<li class="page-item"><a class="page-link" href="search.php?search='.$search.'&submit='.$_GET['submit'].'&page='.($page-1).'">Previous</a></li>';
              }
              for($i=1;$i<=$page_total_page;$i++)
              {
                echo '<li class="page-item active">';
                echo '<a class="page-link" href="search.php?search='.$search.'&submit='.$_GET['submit'].'&page='.$i.'"';
                echo '>'.$i.'</a></li>';
              }

              if($page_total_page>$page)
              {
                echo '<li class="page-item"><a class="page-link" href="search.php?search='.$search.'&submit='.$_GET['submit'].'&page='.($page+1).'">Next</a></li>';
              }

            ?>
          </ul>
      </div>
    </div>
    <?php
   
  }
  
  

?>

<?php
   include './includes/footer.php';
?>
  
  
