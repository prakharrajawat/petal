<?php

@include 'config.php';
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($email, $reset_token)
{
  require('php-mailer/PHPMailer.php');
  require('php-mailer/SMTP.php');
  require('php-mailer/Exception.php');

  $mail = new PHPMailer(true);


  try {
    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth = true;                                   //Enable SMTP authentication
    $mail->Username = 'petalplantsecommerce@gmail.com';                     //SMTP username
    $mail->Password = 'nwuu cwee ljht ipxl';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('petalplantsecommerce@gmail.com', 'PETAL.COM');
    $mail->addAddress($email);     //Add a recipient
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Password reset link from PETAL.COM';
    $mail->Body = "We got a request from you to reset your password!</br> Click the link below:<br>'
    <a href='http://localhost/petal/petal/updatepassword.php?email=$email&reset_token=$reset_token'>Reset Password</a>";


    $mail->send();
    return true;
  } catch (Exception $e) {
    return false;
  }
}

if (isset($_POST['reset-link'])) {

  $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
  $email = mysqli_real_escape_string($conn, $filter_email);

  $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');
  if ($select_users) {
    if (mysqli_num_rows($select_users) == 1) {
      $reset_token = bin2hex(random_bytes(16));
      date_default_timezone_set('Asia/kolkata');
      $date = date("Y-m-d");
      $query = "UPDATE `users` SET `resettoken`='$reset_token',`resettokenexpire`='$date' WHERE `email`='$email'";
      if (mysqli_query($conn, $query) && sendMail($email, $reset_token)) {
        // echo "email sent";
        $message[] = 'Email Sent!';
        // exit();
      } else {
        $message[] = 'Enter a valid email address!';
      }
    } else {
    }
  } else {
    $message[] = 'no user found!';

  }

  //   if (mysqli_num_rows($select_users) > 0) {

  //     $row = mysqli_fetch_assoc($select_users);

  //     if ($row['user_type'] == 'admin') {

  //       $_SESSION['admin_name'] = $row['name'];
//       $_SESSION['admin_email'] = $row['email'];
//       $_SESSION['admin_id'] = $row['id'];
//       header('location:admin_page.php');

  //     }
//     if ($row['user_type'] == 'user') {

  //       $_SESSION['user_name'] = $row['name'];
//       $_SESSION['user_email'] = $row['email'];
//       $_SESSION['user_id'] = $row['id'];
//       header('location:home.php');

  //     } else {
//       $message[] = 'no user found!';
//     }

  //   } else {
//     $message[] = 'incorrect email or password!';
//   }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>login</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <!-- css file link  -->
  <link rel="stylesheet" href="css/style.css?v=80">

</head>

<body class="login-bg">


  <section class="form-container">

    <form action="" method="post">
      <h3 class="reset-heading">RESET PASSWORD</h3>
      <input type="email" name="email" class="box" placeholder="enter your email" required>
      <input type="submit" class="btn" name="reset-link" value="SEND LINK">
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

    </form>
  </section>

</body>

</html>