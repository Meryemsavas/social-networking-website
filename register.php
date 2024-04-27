<!DOCTYPE html>
<?php
require 'header.php';
require 'conn.php';

?>

<div class="wrapper">
  <form method="post" action="process-signup.php">
    <h1>Create account</h1>

    <div class="input-box">
      <input type="text" name="firstName" placeholder="First name" minlength="3" required>
    </div>

    <div class="input-box">
      <input type="text" name="lastName" placeholder="Last name" minlength="3" required>
    </div>

    <div class="input-box">
      <input type="text" name="username" placeholder="Username" minlength="3" required>
    </div>

    <div class="input-box">
      <label for="email"></label>
      <input type="email" name="email" placeholder="E-mail" id="email" minlength="10" required>
    </div>

    <div class="input-box">
      <input type="password" name="password" placeholder="Password" required minlength="8" maxlength="25">
    </div>

    <button type="submit" class="btn">Submit</button>

    <div class="register-link">
      <p>Already have an account? <a href="index.php">Login</a></p>
    </div>
  </form>
</div>

<script>
  function validateForm() {
    var firstName = document.forms["signupForm"]["firstName"].value;
    var lastName = document.forms["signupForm"]["lastName"].value;
    var username = document.forms["signupForm"]["username"].value;
    var email = document.forms["signupForm"]["email"].value;
    var password = document.forms["signupForm"]["password"].value;
    var dateOfBirth = document.forms["signupForm"]["dateOfBirth"].value;

// als form niet is ingevuld krijgen ze een notificztie
    if (firstName == "" || lastName == "" || username == "" || email == "" || password == "" || dateOfBirth == "") {
      alert("Please fill in all fields.");
      return false;
    }
  }
</script>

</body>

</html>