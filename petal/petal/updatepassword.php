<?php

@include 'config.php';

session_start();

require("config.php");
if (isset($_GET['email']) && isset($_GET['reset_token'])) {
  date_default_timezone_set('Asia/kolkata');
  $date = date("Y-m-d");
  $query = "SELECT * from `users` WHERE `email` ='$_GET[email]' AND `resettoken`='$_GET[reset_token]' AND `resettokenexpire`='$date'";

  $result = mysqli_query($conn, $query);
  if ($result) {
    if (mysqli_num_rows($result) == 1) {
      $message[] = "You can now update your password";
      if (isset($_POST['update'])) {
        $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
        $pass = mysqli_real_escape_string($conn, md5($filter_pass));
        $filter_cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);
        $cpass = mysqli_real_escape_string($conn, md5($filter_cpass));
        if ($pass === $cpass) {
          $update_query = "UPDATE `users` SET `password`='$pass' WHERE `email`='$_GET[email]'";
          $run = mysqli_query($conn, $update_query);
          $message[] = "password updated";
          // header('location:login.php');

          // $message[] = "working passwords";
        } else {
          $message[] = "Both password doesn't match";
        }
      }
    } else {
      $message[] = "Invalid or Expired link";
    }
  } else {
    echo "query failed";
  }
} else {
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Password</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <!-- css file link  -->
  <link rel="stylesheet" href="css/style.css?v=2">

</head>

<body class="login-bg">



  <section class="form-container">

    <form action="" method="post">
      <h3>RESET PASSWORD</h3>
      <input type="password" name="pass" class="box" placeholder="enter new password" required>
      <input type="password" name="cpass" class="box" placeholder="confirm new password" required>
      <input type="submit" class="btn" name="update" value="Update Password">
    </form>
    <?php
    if (isset($message)) {
      foreach ($message as $message) {
        echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
      }
    }
    ?>
  </section>

</body>

</html>