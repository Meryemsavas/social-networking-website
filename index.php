<!DOCTYPE html>
<?php
require 'header.php';
require 'conn.php';
?>

<div class="wrapper">
  <form action="login-database.php" method="post">
    <img src="./images/logo.png" class="logo" width="100" height="100">
    <h1>login</h1>
    <div class="input-box">
      <input type="text" id="username" placeholder="Username" minlength="3" required name="username">
      <i class='bx bxs-user'></i>
    </div>

    <div class="input-box">
      <input type="password" id="password" placeholder="Password" minlength="8" maxlength="25" required name="password">
      <i class='bx bxs-lock-alt'></i>
    </div>

    <div class="remember-forgot">
      <input type="checkbox" id="showPasswordCheckbox"> <!-- Add checkbox for show password -->
      <label for="showPasswordCheckbox">Show Password</label> <!-- Label for the checkbox -->

      <script>
        document.getElementById("showPasswordCheckbox").addEventListener('change', function() {
          var passwordField = document.getElementById("password");
          if (passwordField.type === "password") {
            passwordField.type = "text";
          } else {
            passwordField.type = "password";
          }
        });
      </script>

      <a href="reset.php">Forgot Password?</a>
    </div>

    <button type="submit" class="btn">Login</button>

    <div class="register-link">
      <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
  </form>
</div>



</body>

</html>