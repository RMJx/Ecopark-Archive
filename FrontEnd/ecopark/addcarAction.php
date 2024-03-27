<?php

session_start();


if(!isset($_SESSION['loggedin'])){
  header('Location: notloggedin.php');
  exit();


}

$newReg = $_REQUEST['reg'];
$APIKey = str_replace(' ', '', $_SESSION['APIKey']);


$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?id=" . $newReg . "&function=cardetails";

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
$car = json_decode($json_raw_str, true);

?>
<!DOCTYPE html>
<html>
	<head>
  <link rel="apple-touch-icon" sizes="180x180" href="/ecopark/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/ecopark/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/ecopark/favicon-16x16.png">
    <link rel="manifest" href="/ecopark/site.webmanifest">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script type="text/javascript">

    function addCar() {  

    var reg = document.getElementById("reg").getAttribute("placeholder");
    var make = document.getElementById("make").getAttribute("placeholder");
    var colour = document.getElementById("colour").getAttribute("placeholder");
    var co2 = document.getElementById("co2").getAttribute("placeholder");
    accountNumber = <?php echo json_encode($_SESSION['name'], JSON_HEX_TAG); ?>; 
    var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?id=" + reg + "&make=" + make + "&colour=" + colour + "&username=" + accountNumber + "&co2Class=" + co2 + "&function=newcar&API-KEY=" + <?php echo "'$APIKey'" ?>;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", url);

    xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
        console.log(xhr.status);
        console.log(xhr.responseText);
        alert(xhr.responseText.slice(1,-1));
        window.location.replace("home.php");
      }};

    xhr.send();
}

    </script>
<style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      background-color: black;
    }

    * {
      box-sizing: border-box;
    }

    /* Add padding to containers */
    .container {
      padding: 16px;
      background-color: white;
    }

    /* Full-width input fields */
    input[type=text], input[type=password] {
      width: 100%;
      padding: 15px;
      margin: 5px 0 22px 0;
      display: inline-block;
      border: none;
      background: #f1f1f1;
    }

    input[type=text]:focus, input[type=password]:focus {
      background-color: #ddd;
      outline: none;
    }

    /* Overwrite default styles of hr */
    hr {
      border: 1px solid #f1f1f1;
      margin-bottom: 25px;
    }

    /* Set a style for the submit button */
    .registerbtn {
      background-color: #6f4cc2;
      color: white;
      padding: 16px 20px;
      margin: 8px 0;
      border: none;
      cursor: pointer;
      width: 100%;
      opacity: 0.9;
      border-radius: 5px;
    }

    .registerbtn:hover {
      opacity: 1;
    }

    /* Add a blue text color to links */
    a {
      color: dodgerblue;
    }

    /* Set a grey background color and center the text of the "sign in" section */
    .signin {
      background-color: #f1f1f1;
      text-align: center;
    }
    </style>
</head>
		<meta charset="utf-8">
		<title>Ecopark - Confirm Car Details</title>
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
        <h2>Add Car</h2>
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
                    <li class="c-menu__item is-active" onclick="javascript:window.location.href = ('addcar.php');" title="Car">
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
                    <li class="c-menu__item has-submenu" onclick="javascript:window.location.href = ('accountdetails.php');" title="Account">
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
      <h1 class="page-title">Confirm Car Details</h1>
        <div class="page-content">In order to add a new car please fill out the form below and confirm details regarding the vehicle.</em>
        <div class="container">
        <?php
          if(is_array($car) == 1) {
          echo "<h4>" . "Error: Registration " . strtoupper($newReg) .  " already exists in system." . "</h4>";
          exit;
          }
          else {
            include 'checkCarDetails.php';
            if(count($json) < 2) { 
              echo "<h3> Registration Number '" . $newReg .  "' not found in DVLA Database. </h3>"; 
            }
            else {
            $make = $json['make'];
            $year = $json['yearOfManufacture'];
            $colour = $json['colour'];
            if(!array_key_exists('co2Emissions', $json)) $co2 = 200;
            else $co2 = $json['co2Emissions'];
            $engine = $json['engineCapacity'];

            
            echo '<form action="javascript:;" onsubmit="addCar();">';
            echo '<div class="container">';
            echo "<h2>Car Details</h2>";
            echo '<p>Please read through the car details and confirm they match the vehicle registration.</p>';
            echo "<hr>";

            echo '<label for="Reg"><b>Registration</b></label>';
            echo '<input type="text" id="reg" placeholder=' . $newReg .  ' readonly>';

            echo '<label for="Make"><b>Make</b></label>';
            echo '<input type="text" id="make" placeholder=' . $make . ' readonly>';

            echo '<label for="Colour"><b>Colour</b></label>';
            echo '<input type="text" id="colour" placeholder=' . $colour .' readonly>';
            
            echo '<label for="CO2"><b>CO2 Emissions (g/km)</b></label>';
            echo '<input type="text" id="co2" placeholder='. $co2 . ' readonly>';
            
            echo '<label for="Year"><b>Model Year</b></label>';
            echo '<input type="text" id="year" placeholder='. $year . ' readonly>';

            echo '<label for="Engine"><b>Engine Size</b></label>';
            echo '<input type="text" id="engine" placeholder=' . $engine . "cc" . ' readonly>';
            
            echo "<hr>";

            echo '<p>By adding a new vehicle you are agreeing to responsibility of said cars parking within Ecopark premises and accept liability for parking payments.</p>';
            echo '<button type="submit" class="registerbtn">Add Car to Account</button>';
            echo '</div>';

          echo '</div>';
        echo '</form>';
            }
          }
        ?>
        </div>
      </div>
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

    if ($("body").hasClass("sidebar-is-expanded")) {
      $('[data-toggle="tooltip"]').tooltip("destroy");
    } else {
      $('[data-toggle="tooltip"]').tooltip(global.tooltipOptions);
    }

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