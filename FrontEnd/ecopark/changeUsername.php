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
      function changeUN() {
        accountNumber = <?php echo json_encode($_SESSION['name'], JSON_HEX_TAG); ?>; 

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/registerapi/?id=" + accountNumber + "&function=usernamechange&id2=" + document.getElementById("newun").value;

        var xhr = new XMLHttpRequest();
        xhr.open("PUT", url);
        xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            alert("Username changed. Please re-login.");
            window.location.replace("logout.php");
          }};

        xhr.send();
      }

    function check() {  
        //first check username isn't in use.
        var newusername = document.getElementById("newun").value;
        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/registerapi/?function=user&id=" +  newusername;
        var taken = false;
        var xhr = new XMLHttpRequest();
        xhr.open("GET", url);
        xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            const obj = JSON.parse(this.response);
            if(obj.userName != newusername) {
              document.getElementById("changeunbtn").disabled = false;
              document.getElementById('message').style.color = 'green';
              document.getElementById('message').innerHTML = 'Username is not taken.';
            }
            else {
              document.getElementById('message').style.color = 'red';
              document.getElementById('message').innerHTML = 'Username is already taken.';
              document.getElementById("changeunbtn").disabled = true;
              }
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
    .unbtn {
      background-color: #6f4cc2;
      color: white;
      padding: 16px 20px;
      margin: 8px 0;
      border: 5px;
      border-radius: 5px;
      cursor: pointer;
      width: 100%;
      opacity: 0.9;
    }

    button:disabled,
    button[disabled]{
      background-color: #cccccc;
      color: #666666;
    }

    .unbtn:hover {
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
		<title>Ecopark - Change Username</title>
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
        <h2>Change Username</h2>
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
    <h1 class="page-title">Change your Username</h1>  
    <div class="page-content"></em>
        <form action="javascript:;" onsubmit="changeUN();">
        <div class="container">
        <p>Please enter the new username you wish to change to.</p>
        <hr>

        <label for="newun"><b>Username</b><span id='message'></span></label>
        <input type="text" placeholder="New Username" name="newun" id="newun" pattern="[a-zA-Z0-9]+" maxlength = "35" onkeyup="check();" required>

        <hr>
        <button id="changeunbtn" type="submit" class="unbtn">Change Username</button>
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