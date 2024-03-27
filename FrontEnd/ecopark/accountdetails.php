<?php

session_start();


if(!isset($_SESSION['loggedin'])){
  header('Location: notloggedin.php');
  exit();


}
$studentno = $_SESSION['name'];
$APIKey = str_replace(' ', '', $_SESSION['APIKey']);

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/registerapi/?id=" . $studentno . "&function=user";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Accept: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$resp = curl_exec($curl);
curl_close($curl);
$json_raw_str = ltrim($resp, chr(239).chr(187).chr(191));
$user = json_decode($json_raw_str, true);
$fname = $user['fname'];
$lname = $user['lname'];
$email = $user['email'];
$pcode = $user['postcode'];
$total = $user['grandTotal'];

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $studentno ."&function=userscars&API-KEY=" . $APIKey;

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Accept: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$resp = curl_exec($curl);
curl_close($curl);
$json_raw_str = ltrim($resp, chr(239).chr(187).chr(191));
$numberCars = json_decode($json_raw_str, true);

if(is_array($numberCars) == 1) {
$numberCars = sizeof($numberCars);
}
else $numberCars = 0;


?>
<!DOCTYPE html>
<html>
	<head>
    <link rel="apple-touch-icon" sizes="180x180" href="/ecopark/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/ecopark/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/ecopark/favicon-16x16.png">
    <link rel="manifest" href="/ecopark/site.webmanifest">
		<meta charset="utf-8">
    <script type="text/javascript">


    function deleteCar(reg) {  

    var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?id=" + reg;
    
    if (confirm('Are you sure you want to remove this car from your account?')) {
      var xhr = new XMLHttpRequest();
      xhr.open("DELETE", url);

      xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
          console.log(xhr.status);
          console.log(xhr.responseText);
          alert(xhr.responseText.substring(1, xhr.responseText.length-1));
          window.location.replace("home.php");
        }};
      xhr.send();
      }
    }

    </script>
		<title>Ecopark - Account Details</title>
		<link href="style/home.css" rel="stylesheet" type="text/css">
		<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <script src='https://use.fontawesome.com/releases/v5.0.8/js/all.js'></script>		</head>
	</head>
  <body class="sidebar-is-reduced">
    <header class="l-header">
      <div class="l-header__inner clearfix">
        <div class="c-header-icon js-hamburger">
          <div class="hamburger-toggle"><span class="bar-top"></span><span class="bar-mid"></span><span class="bar-bot"></span></div>
        </div>
        <h2>Account Settings</h2>
        <div class="header-icons-group">
          <div class="c-header-icon logout"><a href="logout.php"><i class="fa fa-power-off"></i></a></div>
        </div>
      </div>
    </header>
    <div class="l-sidebar">
      <div class="logo">
      <img src="white-32x32.png" alt="EP">
      </div>
      <div class="l-sidebar__content">
        <nav class="c-menu js-menu">
        <ul class="u-list">
                    <li class="c-menu__item has-submenu" onclick="javascript:window.location.href = ('home.php');" title="Home">
                        <div class="c-menu__item__inner"><i class="fa fa-home"></i>
                            <div class="c-menu-item__title"><span>Home Dashboard</span></div>
                        </div>
                    </li>
                    <li class="c-menu__item has-submenu" onclick="javascript:window.location.href = ('addcar.php');" title="Car">
                        <div class="c-menu__item__inner"><i class="fa fa-car"></i>
                            <div class="c-menu-item__title"><span>Add New Car</span></div>
                        </div>
                    <li class="c-menu__item has-submenu" onclick="javascript:window.location.href = ('addguest.php');" title="Guest">
                        <div class="c-menu__item__inner"><i class="fa fa-user"></i>
                            <div class="c-menu-item__title"><span>Add new Guest Parking</span></div>
                        </div>
                    <li class="c-menu__item has-submenu" onclick="javascript:window.location.href = ('parkinglogs.php');" title="parkinlogs">
                        <div class="c-menu__item__inner"><i class="fa fa-history"></i>
                            <div class="c-menu-item__title"><span>Parking Logs</span></div>
                        </div>
                    <li class="c-menu__item has-submenu" onclick="javascript:window.location.href = ('guestparkinglogs.php');"  title="Guestlogs">
                        <div class="c-menu__item__inner"><i class="fa fa-history"></i>
                            <div class="c-menu-item__title"><span>Guest Parking Logs</span></div>
                        </div>
                    <li class="c-menu__item is-active" onclick="javascript:window.location.href = ('accountdetails.php');" title="Account">
                        <div class="c-menu__item__inner"><i class="fa fa-cog"></i>
                            <div class="c-menu-item__title"><span>Account Settings</span></div>
                        </div>
                    <li class="c-menu__item has-submenu" onclick="javascript:window.location.href = ('logout.php');" title="Logout">
                        <div class="c-menu__item__inner"><i class="fa fa-sign-out-alt"></i>
                            <div class="c-menu-item__title"><span>Logout</span></div></a>
                        </div>
                </ul>
        </nav>
      </div>
    </div>
  </body>

  <main class="l-main">
    <div class="content-wrapper content-wrapper--with-bg">
      <h1 class="page-title">Account Details</h1>
      <div class="page-content">Please find your account information and details below.
      <br>
      <?php
            echo "<table><tr> <th>Username</th> <th>First Name</th> <th>Last Name</th> <th>Post Code</th> <th>Email</th> <th>Cars Registered</th> <th>Total Due on Account</th> </tr>";  
            echo "<tr>";
            echo "<td>" . $studentno . "</td>";
            echo "<td>" . $fname . "</td>";
            echo "<td>" . $lname . "</td>";
            echo "<td>" . $pcode . "</td>";
            echo "<td>" . $email  . "</td>";
            echo "<td>" . $numberCars  . "</td>";
            echo "<td>" . "£" . $total  . "</td>";
            echo "</tr>";
            echo "</table>";
            ?>
        </div>
      <br>
      <h1 class="page-title">Account Options</h1>
      <div class="page-content"><p>Select from one of the following options below.</p>
            <button id="changepwbtn" type="submit" class="pwbtn"><a href="changepassword.php"style="text-decoration: none">Change Password</button>
            <br>
            <button id="changepwbtn" type="submit" class="pwbtn"><a href="changeUsername.php"style="text-decoration: none">Change Username</button>
            <br>
            <?php if ($total != 0.00) { echo '<button id="paybtn" type="submit" class="pwbtn"><a href="changepassword.php"style="text-decoration: none">Pay Balance Due</button>'; } ?>
    </div>
  </main>

  <!-- partial -->
   
  <script>
      let Dashboard = (() => {
  let global = {
    tooltipOptions: {
      placement: "right" },

    menuClass: ".c-menu" };


  let menuChangeActive = el => {
    let hasSubmenu = $(el).hasClass("has-submenu");
    $(global.menuClass + " .is-active").removeClass("is-active");
    $(el).addClass("is-active");
  };

  let sidebarChangeWidth = () => {
    let $menuItemsTitle = $("li .menu-item__title");

    $("body").toggleClass("sidebar-is-reduced sidebar-is-expanded");
    $(".hamburger-toggle").toggleClass("is-opened");


  };

  return {
    init: () => {
      $(".js-hamburger").on("click", sidebarChangeWidth);

      $(".js-menu li").on("click", e => {
        menuChangeActive(e.currentTarget);
      });

    } };

})();

Dashboard.init();
  </script>

		  </body>
</html>