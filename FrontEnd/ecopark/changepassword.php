<?php

session_start();


if(!isset($_SESSION['loggedin'])){
  header('Location: notloggedin.php');
  exit();


}

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

    function changePW() {  
      if (npass == npass2) {
        var cpass = document.getElementById("cpass").value;
        var npass = document.getElementById("npass").value;
        var npass2 = document.getElementById("npass2").value;
        accountNumber = <?php echo json_encode($_SESSION['name'], JSON_HEX_TAG); ?>; 

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/registerapi/?id=" + accountNumber + "&function=passchange&newp=" + npass;

        var xhr = new XMLHttpRequest();
        xhr.open("PUT", url);
        xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            alert("Password changed.");
            window.location.replace("home.php");
          }};

        xhr.send();
      }
        }

    var check = function() {
      if (document.getElementById('npass').value ==
      document.getElementById('npass2').value) {
      document.getElementById('message').style.color = 'green';
      document.getElementById('message').innerHTML = 'Passwords match';
      document.getElementById("changepwbtn").disabled = false;
      } else {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Passwords do not match';
        document.getElementById("changepwbtn").disabled = true;
      }
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
      background-color: #04AA6D;
      color: white;
      padding: 16px 20px;
      margin: 8px 0;
      border: none;
      cursor: pointer;
      width: 100%;
      opacity: 0.9;
      disabled: true;
    }

    button:disabled,
    button[disabled]{
      border: 1px solid #999999;
      background-color: #cccccc;
      color: #666666;
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
		<title>Ecopark - Change Password</title>
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
        <h2>Change Password</h2>
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
    <h1 class="page-title">Change your Password</h1>  
    <div class="page-content"></em>
        <form action="javascript:;" onsubmit="changePW();">
        <div class="container">
        <p>Please enter your current password below as well as your new password.</p>
        <hr>

        <label for="cpass"><b>Current Password</b></label>
        <input type="password" placeholder="Current Password" name="cpass" id="cpass" required>

        <label for="npass"><b>New Password</b><span id='message'></span></label>
        <input type="password" placeholder="New Password" name="npass" id="npass" onkeyup="check();" required>

        <label for="npass2"><b>Confirm New Password</b><span id='message'></span></label>
        <input type="password" placeholder="Confirm New Password" name="npass2" id="npass2" onkeyup="check();" required>

        <hr>
        <button id="changepwbtn" type="submit" class="pwbtn">Change Password</button>
        </div>
  </div>
</form>
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