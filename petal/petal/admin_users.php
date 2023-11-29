<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}
;

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_users.php');
}

if (isset($_GET['status'])) {
   $status_id = $_GET['status'];
   $select_user_status = mysqli_query($conn, "SELECT status FROM `users` WHERE id = '$status_id'") or die('query failed');
   $fetch_user_status = mysqli_fetch_assoc($select_user_status);

   //  If status is 1, change to 0; if status is 0, change to 1
   $new_status = ($fetch_user_status['status'] == '1') ? '0' : '1';

   mysqli_query($conn, "UPDATE `users` SET status = '$new_status' WHERE id = '$status_id'") or die('update query failed');
   header('location:admin_users.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css?v=2">

</head>

<body>

   <?php @include 'admin_header.php'; ?>

   <section class="users">

      <h1 class="title">users account</h1>

      <div class="box-container">
         <?php
         $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
         if (mysqli_num_rows($select_users) > 0) {
            while ($fetch_users = mysqli_fetch_assoc($select_users)) {
               ?>
               <div class="box">
                  <p>user id : <span>
                        <?php echo $fetch_users['id']; ?>
                     </span></p>
                  <p>username : <span>
                        <?php echo $fetch_users['name']; ?>
                     </span></p>
                  <p>email : <span>
                        <?php echo $fetch_users['email']; ?>
                     </span></p>
                  <p>user type : <span style="color:<?php if ($fetch_users['user_type'] == 'admin') {
                     echo 'var(--orange)';
                  }
                  ; ?>">
                        <?php echo $fetch_users['user_type']; ?>
                     </span></p>
                  <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>"
                     onclick="return confirm('delete this user?');" class="delete-btn">delete</a>


                  <a href="admin_users.php?status=<?php echo $fetch_users['id']; ?>"
                     onclick="return confirm('<?php echo ($fetch_users['status'] == '0') ? 'activate' : 'deactivate'; ?> this user?');"
                     style="background-color:<?php echo ($fetch_users['status'] == '0') ? 'orange' : 'green'; ?>"
                     class="deactivate">
                     <?php echo ($fetch_users['status'] == '0') ? 'Deactivate' : 'Activate'; ?>
                  </a>
               </div>
               <?php
            }
         }
         ?>
      </div>

   </section>













   <script src="js/admin_script.js"></script>

</body>

</html>