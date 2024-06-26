 <!-- frontbar/phone -->
  <section class="frontbar js-frontbar">
    <div class="frontbar_top"><img class="close_button js-close" onclick="showSidebar('js-close');" src="https://img.icons8.com/ios/50/close-window--v1.png" alt="delete-sign"/></div>
    <div class="account js-account2">
      <!-- -------------------------- -->
      <?php
      if(isset($_SESSION['auth']) && $_SESSION['auth'] == true){ 
        $user_name = $_SESSION['auth_user']['full_name'];
      ?>
      <div class="signin_Container js-signin_Container">
        <img class="user_icon" src="https://img.icons8.com/material-sharp/24/user-male-circle.png" alt="user-male-circle"/>
        <span class="sign_up_in"><?= $user_name?></span>
        <div class="dropdown-content">
            <a class="dropdown-item"  href="../customer_info.php#cust_page1">My Account</a>
            <a class="dropdown-item" href="../customer_info.php#cust_page2">Order History</a>
            <a class="dropdown-item" href="../customer_info.php#cust_page3">Address Book</a>
            <a class="dropdown-item"  href="../customer_info.php#cust_page4">Card Details</a>
          <a class="dropdown-item" href="../functions/logout.php">Logout</a>
          <!--logout-->
        </div>
      </div>
      <?php
      }else{
        ?>
        <a class="user_account" href="register.php">
          <img class="user_icon" src="https://img.icons8.com/material-sharp/24/user-male-circle.png" alt="user-male-circle"/>
          <div class="signin_Container">
            <span class="sign_up_in">Sign Up</span>
          </div>
        </a>
        <?php
      }?>
        <a href="../customer_info.php#cust_page5"><img class="favorite_icon" src="https://img.icons8.com/fluency-systems-filled/48/hearts.png" alt="hearts"/></a>
        <div class="notification_Container">
          <a href="../cart_page.php"><img class="cart_icon" src="https://img.icons8.com/windows/32/shopping-cart.png" alt="shopping-cart"/></a>
          <div class="notificationCount"><?= $cart_count; ?></div>
        </div>
        <!-- -------------------------- -->
    </div>
    <div class="frontbar_bottom">
      <div class="service js-service2">
        <!-- loadHTMLItems(); -->
      </div>
      <div class="supporr js-support2"> 
        <!-- loadHTMLItems(); -->
      </div>
    
    </div>
  </section>