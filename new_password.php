<?php
 session_start();
 include('components/header.php');
 ?>
<div class="register_container">
  <div class="form_container">
    <div class="close_register"><a href="home.php"><img src="https://img.icons8.com/ios/50/close-window--v1.png" alt="close-window--v1"/></a></div>
    <h1 class="passwordreset">Select your new password</h1>
    <p class="description">Enter your email & the new password</p>
    <form id="user-details" action="functions/update_user_info.php" method="POST" onsubmit="return validateForm()">
      <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
          <label for="confirm-password">Confirm Password</label>
          <input type="password" id="confirm-password" name="confirm-password" required>
      </div>
      <div class="form-group">
          <button type="submit" name="save-newpassword-btn">Save New Password</button>
          <?php
            if(isset($_SESSION['message'])){ ?>
            <span class="message"> <?= $_SESSION['message'];?></span>

            <?php
              unset($_SESSION['message']);
            }
          ?>
          <span id="emailError"></span>
          <span id="passwordError"></span>
      </div>
      <div class="form-group">
        <p>New to this site?<a href="register.php">&nbsp;<b>Sign Up</b></a></p>
      </div>
    </form>
  </div>
</div>
 
<?php
 //include('includes/footer.php');
?>