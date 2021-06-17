<?php 

  include './includes/conn.php';
  include './includes/header.php'; ?>

  <div class="find_courses">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-4">
          <div class="sidebar">
            <div class="categories">
              <h5 class="mb-4">Categories</h5>
              <?php
                $catQuery = $mysqli->query("select * from course");
                
      
                $catResult = $catQuery->fetch_all(MYSQLI_ASSOC);
                $tagsArr = array();
                
                if($catQuery->num_rows>0){

                  foreach ($catResult as $row) {

                    $explode = explode(',', $row['Tags']);
                  
                    foreach ($explode as $tag ) {
                      array_push($tagsArr, trim(strtolower($tag)));
                    }

                    $uniqueTags = array_unique($tagsArr);
                    
                  }
                  foreach($uniqueTags as $tags){?>

                    <div class="form-group">
                      <input type="checkbox" name="cat" id="cat" class="common_selector categories" value="<?php echo $tags; ?>">
                      <label for="cat"><?php echo $tags; ?></label>
                    </div>
                  <?php
                  }
                }
              ?>
              
            </div>

            <div class="filter-widget">
              <h5 class="fw-title">Price</h5>
              <div class="filter-range-wrap p-filter">
                  <input type="hidden" id="hidden_minimum_price" value="0" />
                  <input type="hidden" id="hidden_maximum_price" value="5000" />
                  <p id="price_show">1-500</p>
                  <div id="price_range"></div>
              </div>
              
            </div>
          </div>

          <div class="mobileSidebar">
          <button class="toggler" type="button">
            <i class="fas fa-bars fa-2x"></i>
          </button>
          <div class="categories mobile">
              <h4 class="mb-4">Categories</h4>
              <?php
                $catQuery = $mysqli->query("select * from course");
                
      
                $catResult = $catQuery->fetch_all(MYSQLI_ASSOC);
                $tagsArr = array();
                
                if($catQuery->num_rows>0){

                  foreach ($catResult as $row) {

                    $explode = explode(',', $row['Tags']);
                  
                    foreach ($explode as $tag ) {
                      array_push($tagsArr, trim(strtolower($tag)));
                    }

                    $uniqueTags = array_unique($tagsArr);
                    
                  }
                  foreach($uniqueTags as $tags){?>

                    <div class="form-group">
                      <input type="checkbox" name="cat" id="cat" class="common_selector categories" value="<?php echo $tags; ?>">
                      <label for="cat"><?php echo $tags; ?></label>
                    </div>
                  <?php
                  }
                }
              ?>
            </div>
          </div>
        </div>

        <div class="col-lg-9 col-md-8 col-sm-8">
          <div class="main">
          <h5>Filter your courses</h5>

            <div class="sort mt-4 mb-4">
              <div class="form-group">
                <select class = "form-control sorting" id="sortByTitle">                                 
                    <option value="">Sort by Title</option>
                    <option value="ascTitle">Ascending</option>
                    <option value="descTitle">Descending</option>
                </select>
              </div>
             
              
            </div>
            <div class="courseLists">  
              
              <div class="filter_data">
              </div>
            </div>
          </div>
          </div>
        </div>

      </div>
    </div> 
  </div>

<?php include './includes/footer.php'; ?>