<?php
session_start();
include('config/dbcon.php');
 include('components/header.php');
 include('components/navbar.php');
 include('components/frontbar.php');
 ?>
  <main class="user_main">
    <div class="physical_store_container">
      <div class="our_vision">
        <h1>Services Offered</h1>
        <p>Welcome to our Help Page! Here you will find answers to common questions and guidance on using our website. Explore the sections below to learn about account setup, browsing products, making purchases, tracking orders, and managing your account settings. If you need further assistance, please contact our support team via email or through our FAQ section.</p>
      </div>
      <div class="tab-container">
        <div class="tab-buttons">
            <button class="tab-link active" onclick="openTab(event, 'service_page1')">Sell</button>
            <button class="tab-link" onclick="openTab(event, 'service_page2')">Trade-Ins</button>
            <button class="tab-link" onclick="openTab(event, 'service_page3')">Repair</button>
            <button class="tab-link" onclick="openTab(event, 'service_page4')">Layaway</button>
        </div>
        <div id="service_page1" class="tab-content active">
          <div class="contentt_container">
            <P>Hello There</P>
          </div>
        </div>
        <div id="service_page2" class="tab-content">
          <div class="contentt_container">
            <P>Hello There</P>
          </div>
        </div>
        <div id="service_page3" class="tab-content">
          <div class="contentt_container">
            <P>Hello There</P>
          </div>
        </div>
        <div id="service_page4" class="tab-content">
          <div class="contentt_container">
            <P>Hello There</P>
          </div>
        </div>

    </div>
    </div>
  </main>
<?php
 include('components/footer.php');
?>