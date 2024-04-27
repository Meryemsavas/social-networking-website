<!DOCTYPE html>
<?php
require 'header.php';
require 'conn.php';

?>

<div class="wrapper">
  <form method="post" action="send-password-reset.php">
    <h1>Reset password</h1>

    <div class="input-box">
      <input type="email" placeholder="E-mail" required name="email" id="email">
      <a class="reset-pass" href="index.php">Login</a>
    </div>
    <button type="submit" class="btn">Send</button>

  </form>

  <?php

  ?>
  </body>

  </html>