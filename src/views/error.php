<div class="container">
  <h1 class="error-code"><?php echo $code ?> error</h1>
  <p class="error-message">
    <?php 
      echo $code === 404 ? 'This page does not exist.' : 'Unexpected error has occured.<br>Please try again later.'
    ?>
  </p>
</div>