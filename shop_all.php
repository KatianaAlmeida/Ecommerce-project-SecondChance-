<?php
session_start();
include('config/dbcon.php');
 include('components/header.php');
 include('components/navbar.php');
 include('components/frontbar.php');
 ?>
  <main class="shop_all_page">
    <div>
      <h1>Search Results</h1>
    </div>
    <div class="shop_all_container">
      <div class="shop_category">
        <p>Category</p>
        <div class="shop_category_container">
          <?php
            $sql = "SELECT categories.id, categories.name, categories.status, COUNT(products.id) as product_count 
                    FROM categories 
                    LEFT JOIN products ON categories.id = products.category_id 
                    GROUP BY categories.id, categories.name";
            $result =  mysqli_query($connection, $sql);
            if ($result) {
              if (mysqli_num_rows($result) > 0) {
                foreach ($result as $items) {
                  if ($items["status"] != "Hidden") {
                    ?>
                    <div class="checkbox-container">
                      <input type="checkbox" name="categories[]" value="<?= $items["id"]; ?>" id="category_<?= $items["id"]; ?>" class="styled-checkbox">
                      <label for="category_<?= $items["id"]; ?>"><?= $items["name"]; ?> (<?= $items["product_count"]; ?>)</label>
                    </div>
                    <?php
                  }
                }
              } else {
                $_SESSION['message'] = 'No category found!';
                header('Location: ../add_products.php');
              }
            } else {
              $_SESSION['message'] = 'Execution Error: '. $connection->error;
              header('Location: ../add_products.php');
            }
          ?>
        </div>
      </div>
      <div class="shop_products">
        <div class="product-containerr" id="product-container">
          <?php
            $sql = "SELECT * FROM products";
            $result = mysqli_query($connection, $sql);

            if ($result) {
              if (mysqli_num_rows($result) > 0) {
                $count = 0;
                foreach ($result as $items) {
                  if ($count % 5 == 0) {
                    echo '<div class="product-row" style="display:none;">';
                  }
                  ?>
                  <div class="productt">
                    <a href="#"><div class="image_container2"><img src="admin/uploads/<?= $items["image_1"]; ?>" alt="<?= $items["product_name"]; ?>"></div></a>
                    <a href="#"><p class="product-name1"><?= $items["product_name"]; ?></p></a>
                    <span class="product-price1">R<?= $items["price"]; ?>.00</span>
                    <a href="#"><button class="add-to-cart" data-product-id="<?= $items["id"]; ?>">Add to Cart</button></a>
                  </div>
                  <?php
                  $count++;
                  if ($count % 5 == 0) {
                    echo '</div>';
                  }
                }
                if ($count % 5 != 0) {
                  echo '</div>'; // Close the last row if it's not a full row
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
        <div class="pagination">
          <button id="prev-button" disabled>&lt; previous</button>
          <span id="page-info">1</span>
          <button id="next-button">next &gt;</button>
        </div>
      </div>
    </div>
  </main>
<?php
 include('components/footer.php');
?>