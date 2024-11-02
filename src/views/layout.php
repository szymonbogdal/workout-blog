<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title; ?></title>
  <link rel="stylesheet" href="/workout_blog/public/css/main.css">
  <?php
    if(isset($css)){
      echo "<link rel='stylesheet' href='/workout_blog/public/css/views/$css'>";
    }
    if(isset($js)){
      echo "<script type='module' src='/workout_blog/public/js/views/$js'></script>";
    }
  ?>
  <script>
    window.userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0 ?>
  </script>
</head>
<body>
  <?php require $content; ?>
</body>
</html>