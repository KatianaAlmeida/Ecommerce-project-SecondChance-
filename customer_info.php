<?php
  session_start();
  include('config/dbcon.php');
  if(!isset($_SESSION['auth'])){
    header('Location: login.php');
  };
  include('components/header.php');
  include('components/navbar.php');
  include('components/frontbar.php');
 ?>
  <main class="user_main">
    <div class="customer_info_container">
      <div class="tab-container">
        <div class="customer_info">
          <h1>Welcome Back</h1>
          <?php
            $full_name = $_SESSION['auth_user']['full_name'];
          ?>
          <p><?= $full_name;?></p>
        </div>
        <div class="tab-buttons">
          <button class="tab-link active" onclick="openTab(event, 'cust_page1')">Customer Info</button>
          <button class="tab-link" onclick="openTab(event, 'cust_page2')">Order History</button>
          <button class="tab-link" onclick="openTab(event, 'cust_page3')">Address Book</button>
          <button class="tab-link" onclick="openTab(event, 'cust_page4')">Card Details</button>
          <button class="tab-link" onclick="openTab(event, 'cust_page5')">Wishlist List</button>
        </div>
        <div id="cust_page1" class="tab-content active">
          <div class="contentt_container">
            <div class="profile-container">
              <?php
              $user_id = $_SESSION['auth_user']['id'];
              $user_detail_query = "SELECT * FROM users WHERE id = '$user_id' LIMIT 1";
              $user_detail_query_run =  mysqli_query($connection, $user_detail_query);

              if ($user_detail_query_run && mysqli_num_rows($user_detail_query_run) > 0) {
                $user_data = mysqli_fetch_array($user_detail_query_run);
                $id = $user_data['id'];
                $username = $user_data['username'];
                $full_name = $user_data['full_name'];
                $email = $user_data['email'];
                $password = $user_data['password'];
                $role = $user_data['role'];
              }else{
                ?>
                <p class="message_order">No user details found in this account!</p>
                <?php
              }
              ?>
              <h2>User Profile</h2>
              <form id="profileForm" action="functions/update_user_info.php" method="POST" onsubmit="return validateForm()">
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?= $username; ?>" required>
                
                <label for="full_name">full_name:</label>
                <input type="text" name="full_name" value="<?= $full_name; ?>" required>
                
                <label for="email">Email:</label>
                <input id="email" type="email" name="email" value="<?= $email; ?>" required>
                
                <label for="password">Password:</label>
                <input id="password" type="password" name="password" value="<?= $password; ?>">

                <label for="password">Confirm Password:</label>
                <input type="password" name="confirmed_password" value="">
                
                <label for="role">Role:</label>
                <input type="text" id="role" name="role" value="<?= $role; ?>" readonly>
                
                <input type="hidden" name="user_id" value="<?= $id; ?>">
                <button type="submit" name="update_user_info_btn">Update Profile</button>
              </form>
              <?php
                if(isset($_SESSION['message'])){ ?>
                <p class="message"> <?= $_SESSION['message'];?></p>
                <?php
                  unset($_SESSION['message']);
                }
              ?>
              <span id="emailError"></span>
              <span id="passwordError"></span>
            </div>         
          </div> 
        </div>
        <div id="cust_page2" class="tab-content">
          <div class="contentt_container">
            <div class="customer_info_title">
              <h3>My Orders</h3>
              <span>View your order history or check the status of a recent order.</span>
            </div>
            <?php
            $user_id = $_SESSION['auth_user']['id'];
            $sql = "SELECT * FROM orders WHERE userd_id = '$user_id'";
            $result = mysqli_query($connection, $sql);

            if ($result) {
              if (mysqli_num_rows($result) > 0) {
                ?>
                <div class=""></div>
                <table class="styled_table1">
                  <tr>
                    <th>Date</th>
                    <th>Order Number</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th>Order Details</th>
                  </tr>
                  <?php
                  foreach ($result as $index => $items) {
                    $tracking_no = $items["tracking_no"];
                    $total_price = $items["total_price"];
                  ?>
                    <tr>
                      <td class="user_row"><?= date('Y-m-d', strtotime($items["created_at"])); ?></td>
                      <td class="user_row">#<?= $tracking_no; ?></td>
                      <td class="user_row"><?= $items["status"]; ?></td>
                      <td class="user_row">R<?= $items["total_price"]; ?></td>
                      <td class="user_row"><button class="new_address expand_btn" data-index="<?= $index; ?>">Expand</button></td>
                    </tr>
                    <tr class="order_detail details_off" id="details-<?= $index; ?>">
                      <td colspan="5">
                        <?php
                        $order_detail = "SELECT 
                                        p.image_1 AS image,
                                        p.product_name,
                                        p.incremented_name AS SKU,
                                        p.price,
                                        oi.qty AS product_quantity,
                                        o.delivery_fee,
                                        ab.recipient_name,
                                        ab.address_type,
                                        ab.complex_name,
                                        ab.address_street, 
                                        ab.suburb,
                                        ab.town,
                                        ab.province,
                                        ab.postal_code,
                                        ab.phone_number
                                        FROM order_items oi 
                                        JOIN orders o ON oi.order_id = o.id 
                                        JOIN address_book ab ON o.address_id = ab.id
                                        JOIN products p ON oi.product_id = p.id
                                        WHERE o.id = oi.order_id AND o.tracking_no = '$tracking_no'";
                        $order_detail_run = mysqli_query($connection, $order_detail);

                        if ($order_detail_run && mysqli_num_rows($order_detail_run) > 0) {
                          ?>
                          <div class="all_products_order">
                            <?php
                            $subtotal = 0;
                            foreach ($order_detail_run as $items) {
                            ?>
                              <div class="div1">
                                <img src="admin/uploads/<?= $items["image"]; ?>" alt="<?= $items["image"]; ?>">
                                <div>
                                  <p><?= $items["product_name"]; ?></p>
                                  <p>SKU: <?= $items["SKU"]; ?></p>
                                </div>
                                <div>
                                  <p>QTY: <?= $items["product_quantity"]; ?></p>
                                </div>
                                <div>
                                  <p>R <?= $items["price"]; ?></p>
                                </div>
                              </div>
                            <?php
                              $subtotal += $items["price"] * $items["product_quantity"];
                              $delivery_fee = $items["delivery_fee"];
                              $address_type = $items["address_type"];
                              $recipient_name = $items["recipient_name"];
                              $complex_name = $items["complex_name"];
                              $address_street = $items["address_street"];
                              $suburb = $items["suburb"];
                              $town = $items["town"];
                              $postal_code = $items["postal_code"];
                              $province = $items["province"];
                              $phone_number = $items["phone_number"];
                            }
                            ?>
                            <div class="div2 each_address">
                              <div class="div_1">
                                <h4>Shipping Information</h4>
                                <p class="name_number"><?= $address_type; ?></p>
                                <p><?= $recipient_name; ?></p>
                                <p><?= $complex_name; ?></p>
                                <p><?= $address_street; ?></p>
                                <p><?= $suburb; ?>, <?= $town; ?>, <?= $postal_code; ?></p>
                                <p><?= $province; ?></p>
                                <p class="name_number"><?= $phone_number; ?></p>
                              </div>
                              <div class="div_2">
                                <div>
                                  <p>Subtotal</p>
                                  <p>R<?= $subtotal; ?></p>
                                </div>
                                <div>
                                  <p>Delivery Fee</p>
                                  <p>R<?= $delivery_fee; ?></p>
                                </div>
                                <hr>
                                <div>
                                  <p>Total</p>
                                  <p>R<?= $total_price; ?></p>
                                </div>
                              </div>
                            </div>
                          </div>
                        <?php
                        }else {
                          $order_detail_collect = "SELECT 
                            p.image_1 AS image,
                            p.product_name,
                            p.incremented_name AS SKU,
                            p.price,
                            oi.qty AS product_quantity,
                            o.delivery_fee
                            FROM order_items oi 
                            JOIN orders o ON oi.order_id = o.id 
                            JOIN products p ON oi.product_id = p.id
                            WHERE o.id = oi.order_id AND o.tracking_no = '$tracking_no'";
                          $order_detail_collect_run =  mysqli_query($connection, $order_detail_collect);

                          if ($order_detail_collect_run && mysqli_num_rows($order_detail_collect_run) > 0) {
                            ?>
                            <div class="all_products_order">
                              <?php
                              $subtotal = 0;
                              foreach ($order_detail_collect_run as $items) { 
                                ?>
                                <div class="div1">
                                  <img src="admin/uploads/<?= $items["image"]; ?>" alt="<?= $items["image"]; ?>">
                                  <div>
                                    <p><?= $items["product_name"]; ?></p>
                                    <p>SKU: <?= $items["SKU"]; ?></p>
                                  </div>
                                  <div><p>QTY: <?= $items["product_quantity"]; ?></p></div>
                                  <div><p>R <?= $items["price"]; ?></p></div>
                                </div>
                                <?php
                                  $subtotal += $items["price"] * $items["product_quantity"];
                                  $delivery_fee =  $items["delivery_fee"];
                              }
                              ?>
                              <div class="div2 each_address">
                                <div class="div_1">
                                  <h4>Store Location (Pick Up)</h4>
                                  <p class="name_number">Business</p>
                                  <p>Second Chance Emperium</p>
                                  <p>53 Main Rd</p>
                                  <p>Claremont, Cape Town, 7700</p>
                                  <p>Western Cape</p>
                                  <p class="name_number">123-456-7890</p>
                                </div>
                                <div class="div_2">
                                  <div>
                                    <p>Subtotal</p>
                                    <p>R<?= $subtotal; ?></p>
                                  </div>
                                  <div>
                                    <p>VAT</p>
                                    <p>R<?= $delivery_fee; ?></p>
                                  </div>
                                  <hr>
                                  <div>
                                    <p>Total</p>
                                    <p>R<?= $total_price; ?></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <?php
                          }else{
                            ?>
                            <p class="message_order">No products found in this order!</p>
                            <?php
                          }
                        }
                        ?>
                      </td>
                    </tr>
                  <?php
                  }
                  ?>
                </table>
              <?php
              } else {
              ?>
                <p class="message_order">No orders found!</p>
              <?php
              }
            } else {
              ?>
              <p class="message_order">Query failed: <?= mysqli_error($connection); ?></p>
            <?php
            }
            ?>
          </div>
        </div>
        <div id="cust_page3" class="tab-content">
          <div class="contentt_container">
            <div class="delivery_address">
              <div class="address1">
                <div class="customer_info_title">
                  <h3>My Addresses</h3>
                  <span>Add and manage the addresses you use often.</span>
                </div>
                <button onclick="insert_form();" class="new_address">New Address</button>
              </div>
              <!----><div class="address_info">
                <?php
                $address_sql = "SELECT * FROM address_book WHERE user_id = '$user_id'";
                $address_sql_run =  mysqli_query($connection, $address_sql);
                if ($address_sql_run) {
                  if (mysqli_num_rows($address_sql_run) > 0) {
                    $count = 0;
                    foreach ($address_sql_run as $items) {
                      $count++; // Increment count at the beginning of the loop
                      ?>
                      <div class="radio_container">
                        <input type="radio" id="user_address<?= $count; ?>" value="<?= $items["id"]; ?>" name="choosen_address" class="radio_input" required>
                        <label for="user_address<?= $count; ?>" class="radio_label">
                          <div class="each_address">
                            <div class="name_close_container">
                              <p class="name_number"><?= $items["address_type"]; ?></p>
                              <form class="close_btn_container" action="functions/place_order.php" method="post">
                                <input type="hidden"  name="address_id" value="<?= $items["id"]; ?>">
                                <input type="hidden"  name="page" value="customer_info">
                                <button type="submit" name="delete_address_btn" class="product-name1"><img src="https://img.icons8.com/ios/50/delete-sign--v1.png" alt="delete-sign--v1"/></button>
                              </form>
                            </div>
                            <p><?= $items["complex_name"]; ?></p>
                            <p><?= $items["address_street"]; ?></p>
                            <p><?= $items["suburb"]; ?>, <?= $items["town"]; ?>, <?= $items["postal_code"]; ?></p>
                            <p class="name_number"><?= $items["recipient_name"]; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?= $items["phone_number"]; ?></p>
                          </div>
                        </label>
                      </div>
                      <?php
                    }
                  }
                } else {
                  $_SESSION['adress_added'] = 'Execution Error: '. $connection->error;
                  header('Location: ../adress_added.php');
                }
                ?>
              </div>
              <?php
                if(isset($_SESSION['adress_added'])){ 
                  ?>
                  <p class="message_address"> <?= $_SESSION['adress_added'];?></p>
                  <?php
                  unset($_SESSION['adress_added']);
                }
              ?>
            </div>
            <!----><div class="insert_address_info insert_address_off">
              <form enctype="multipart/form-data" action="functions/place_order.php" method="POST">
                <p>Add New Address</p>
                <div class="form-group">
                  <div class="radio-container">
                    <input type="radio" id="residency" value="Residential" name="address_type" class="radio-input" required>
                    <label for="residency" class="radio-label">Residential</label>
                  </div>
                  <div class="radio-container">
                    <input type="radio" id="business" value="Business" name="address_type" class="radio-input">
                    <label for="business" class="radio-label">Business</label>
                  </div>
                </div>  
                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" name="name" required>
                </div>    
                <div class="form-group">
                  <label for="phone_number">Mobile Number</label>
                  <input type="number" name="phone_number" required>
                </div>
                <div class="form-group">
                  <label for="complex_name">Complex / Buiding Name</label>
                  <input type="text" name="complex_name" required>
                </div>  
                <div class="form-group">
                  <label for="address_street">Address Street</label>
                  <input type="text" name="address_street" required>
                </div>          
                <div class="form-group">
                  <label for="suburb">Suburb</label>
                  <input type="text" name="suburb" required>
                </div> 
                <div class="form-group">
                  <label for="town">City / Town</label>
                  <input type="text" name="town" required>
                </div> 
                <div class="form-group">
                  <label for="name">Province</label>
                  <select name="province" class="categotySelect" required>
                    <option disabled selected hidden>Select Province</option>
                    <option value="Eastern Cape">Eastern Cape</option>
                    <option value="Free State">Free State</option>
                    <option value="Gauteng">Gauteng</option>
                    <option value="KwaZulu-Natal">KwaZulu-Natal</option>
                    <option value="Limpopo">Limpopo</option>
                    <option value="Mpumalanga">Mpumalanga</option>
                    <option value="Northern Cape">Northern Cape</option>
                    <option value="North West">North West</option>
                    <option value="Western Cape">Western Cape</option>
                  </select>
                </div> 
                <div class="form-group">
                  <label for="postal_code">Postal Code</label>
                  <input type="number" name="postal_code" required>
                </div> 
                <div class="form-group">
                  <input type="hidden"  name="page" value="customer_info">
                  <button type="submit" name="save_address_btn">Save Address</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div id="cust_page4" class="tab-content">
          <div class="contentt_container">
            <div class="delivery_address">
              <div class="address1">
              <div class="customer_info_title">
              <h3>Card Details</h3>
              <span>Save your credit and debit card details for faster checkout.</span>
            </div>
                <button onclick="insert_form1();" class="new_address new_address1">New Card</button>
              </div>
              <!----><div class="address_info">
                <?php
                $card_sql = "SELECT * FROM card_details WHERE user_id = '$user_id'";
                $card_sql_run =  mysqli_query($connection, $card_sql);
                if ($card_sql_run) {
                  if (mysqli_num_rows($card_sql_run) > 0) {
                    $count = 0;
                    foreach ($card_sql_run as $items) {
                      $count++; 
                      $last_4_digits = substr($items["card_number"], -4);
                      ?>
                      <div class="radio_container">
                        <input type="radio" id="user_address<?= $count; ?>" value="<?= $items["id"]; ?>" name="choosen_address" class="radio_input" required>
                        <label for="user_address<?= $count; ?>" class="radio_label">
                          <div class="each_address">
                            <div class="name_close_container">
                              <p class="name_number">Card Ending with <?=  $last_4_digits; ?></p>
                              <form class="close_btn_container" action="functions/place_order.php" method="post">
                                <input type="hidden"  name="card_id" value="<?= $items["id"]; ?>">
                                <input type="hidden"  name="page" value="customer_info">
                                <button type="submit" name="delete_card_btn" class="product-name1"><img src="https://img.icons8.com/ios/50/delete-sign--v1.png" alt="delete-sign--v1"/></button>
                              </form>
                            </div>
                            <p><?= $items["name_on_card"]; ?></p>
                            <p>Visa ****<?= $last_4_digits; ?></p>
                            <p>Expires <?= $items["expiry_month"]; ?>, <?= $items["expiry_year"]; ?></p>
                          </div>
                        </label>
                      </div>
                      <?php
                    }
                  }
                } else {
                  $_SESSION['adress_added'] = 'Execution Error: '. $connection->error;
                  header('Location: ../adress_added.php');
                }
                ?>
              </div>
              <?php
                if(isset($_SESSION['card_added'])){ 
                  ?>
                  <p class="message_address"> <?= $_SESSION['card_added'];?></p>
                  <?php
                  unset($_SESSION['card_added']);
                }
              ?>
            </div>
            <!----><div class="insert_address_info insert_address1_off">
              <form id="add_card" enctype="multipart/form-data" action="functions/place_order.php" method="POST">
                <p>Add Card Details</p>  
                <div class="form-group">
                  <label for="name_on_card">Name on Card</label>
                  <input type="text" name="name_on_card" required>
                </div>    
                <div class="form-group">
                  <label for="card_number">Card Number</label>
                  <input type="number" id="card_number" name="card_number" required>
                </div>
                <div class="form-group">
                  <label for="name">Expiry Month</label>
                  <select name="expiry_month" class="categotySelect" required>
                    <option disabled selected hidden>Select Month</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                  </select>
                </div>  
                <div class="form-group">
                  <label for="expiry_year">Expiry Year</label>
                  <input type="number" id="expiry_year" name="expiry_year" required>
                </div> 
                <div class="form-group">
                  <label for="cvv">CVV</label>
                  <input type="number" name="cvv" required>
                </div> 
                <div class="form-group">
                  <input type="hidden"  name="page" value="customer_info">
                  <button type="submit" name="save_card_btn">Save Card</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div id="cust_page5" class="tab-content">
          <div class="contentt_container horiz_ruler">
            <div class="customer_info_title">
              <h3>My Wishlist</h3>
              <span>View favorite products you&#39;ve saved to your wishlist.</span>
            </div>
            <hr>
            <div class="product-containerr" id="product-container">
              <?php
                $wishlist_query = "SELECT 
                        p.image_1,
                        p.product_name,
                        p.incremented_name,
                        p.id,
                        p.price,
                        p.quantitty,
                        w.id
                        FROM wishlist w
                        JOIN products p ON w.product_id = p.id
                        WHERE w.user_id = '$user_id';";
                $wishlist_query_run = mysqli_query($connection, $wishlist_query);

                if ($wishlist_query_run) {
                  if (mysqli_num_rows($wishlist_query_run) > 0) {
                    foreach ($wishlist_query_run as $items) {
                      ?>
                      <div class="productt">
                        <a href="">
                          <div class="image_container2 stay_on_top_container">
                            <img src="admin/uploads/<?= $items["image_1"]; ?>" alt="<?= $items["product_name"]; ?>">
                            <div class="name_close_container">
                              <form class="close_btn_container some_shadow stay_on_top" action="functions/handle_cart.php" method="post">
                                <input type="hidden"  name="wishlist_id" value="<?= $items["id"]; ?>">
                                <button type="submit" name="delete_wishlist_btn" class="product-name1"><img src="https://img.icons8.com/ios/50/delete-sign--v1.png" alt="delete-sign--v1"/></button>
                              </form>
                            </div>
                          </div>
                          <p class="product-name1"><?= $items["product_name"]; ?></p>
                        </a>
                        <?php
                          if($items["quantitty"] != 0){
                            ?>
                            <span class="product-price1">R<?= $items["price"]; ?>.00</span>
                            <form action="functions/handle_cart.php" method="post">
                              <input type="hidden" value="1" name="quantity" id="quantity" min="1">
                              <input type="hidden" name="stock_qty" value="<?= $items["quantitty"]; ?>">
                              <input type="hidden"  name="product_id" value="<?=$items["id"];?>">
                              <input type="hidden"  name="SKU" value="<?=$items["incremented_name"];?>">
                              <input type="hidden"  name="page" value="customer_info">
                              <button class="add-to-cart" type="submit" name="add_to_cart-btn" data-product-id="<?= $items["id"]; ?>">Add to Cart</button>
                            </form>
                            <?php
                          }else{
                            ?>
                            <span class="old-price">Out of Stock</span>
                            <button class="add-to-cart" disabled data-product-id="<?= $items["id"]; ?>">Maybe Next Time</button>
                            <?php
                          }
                        ?>
                      </div>
                      <?php
                    }
                  } else {
                    ?>
                    <div class="each_category">
                      <p>No Products Available!</p>
                    </div>
                    <?php
                  }
                } else {
                  ?>
                  <div class="each_category">
                    <p>Something Went Wrong! Sorry About that.</p>
                  </div>
                  <?php
                }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
<?php
 include('components/footer.php');
?>