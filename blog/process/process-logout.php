<?php
  session_start();

  if (isset($_SESSION['user_email'])) {
    unset($_SESSION['user_email']);
  }
  if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
  }

  header('Location:/blog/index.php');
?>
