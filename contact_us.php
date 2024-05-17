<?php
session_start();
 include('components/header.php');
 include('components/navbar.php');
 include('components/frontbar.php');
 ?>
  <main>
    <div class="contactt_conatiner">
      <div class="left">
        <div class="form-group">
          <h2>Get in Touch if Us</h2>
        </div>
        <div class="form-group">
          <p>500 Terry Francine Street San Francisco, CA 94158</p>
        </div>
        <div class="form-group">
          <p>123-456-7890</p>
          <p>info&#64;mysite&#46;com</p>
          <ul>
            <li class="social-links">
              <a href="#"><i class="fab fa-facebook-f"></i></a>
              <a href="#"><i class="fab fa-instagram"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
              <a href="#"><i class="fab fa-youtube"></i></a>
            </li>
          </ul>
        </div>
      </div>
      <div class="rigth">
        <form action="functions/permission.php" method="POST">
          <div class="form-group">
            <div>
              <label for="first_name">First Name</label>
              <input type="text" name="first_name" required>
            </div>
            <div>
              <label for="last_name">Last Name</label>
              <input type="text" name="last_name" required>
            </div>
          </div> 
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" required>
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <textarea rows="5" name="message" required></textarea>
          </div>
          <div class="form-group">
              <button type="submit" name="send-btn">Send</button>
              <?php
                if(isset($_SESSION['permission_message'])){ ?>
                <span class="message js-message"> <?= $_SESSION['permission_message'];?></span>
                <?php
                  unset($_SESSION['permission_message']);
                }
              ?>
          </div>
        </form>
      </div>
    </div>
  </main>
<?php
 include('components/footer.php');
?>