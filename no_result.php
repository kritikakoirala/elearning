<?php
  function showError($message, $message1){
    ?>
    <div class="errorMessage">
      <i class="far fa-frown-open fa-4x mb-4"></i>
      <?php
        if($message!==''){?>
          <p class="mt-4 pt-4">Sorry, you have no <?php echo $message; ?> yet!</p>
        <?php
        }
      ?>
      
      <span><?php echo $message1; ?></span>
      
    </div>
    <?php
  }
?>