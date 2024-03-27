<?php

session_start();


if(!isset($_SESSION['loggedin'])){
  header('Location: notloggedin.php');
  exit();


}

$APIKey = str_replace(' ', '', $_SESSION['APIKey']);

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $_SESSION['name'] ."&function=parkinghistoryreg&API-KEY=" . $APIKey . "&id=" . $_REQUEST['reg'];

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
$rows = json_decode($json_raw_str, true);


?>
<!DOCTYPE html>
<html>

<head>
    <link rel="apple-touch-icon" sizes="180x180" href="/ecopark/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/ecopark/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/ecopark/favicon-16x16.png">
    <link rel="manifest" href="/ecopark/site.webmanifest">
    <meta charset="utf-8">
    <title>Ecopark - Home</title>
    <link href="style/home.css" rel="stylesheet" type="text/css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <script src='https://use.fontawesome.com/releases/v5.0.8/js/all.js'></script>
    <script type="text/javascript">
    function deleteCar(parkingID) {
        url = "/ecopark/imagedb/" + parkingID + ".jpg";
        window.open(url, '_blank').focus();
    }

    function getReceipt(parkingID) {  
         url = "invoice.php/?parkingID=" + parkingID;
         window.open(url, '_blank').focus();
      }
    </script>
</head>
<script type="text/javascript">

</script>
</head>

<body class="sidebar-is-reduced">
    <header class="l-header">
        <div class="l-header__inner clearfix">
            <div class="c-header-icon js-hamburger">
                <div class="hamburger-toggle"><span class="bar-top"></span><span class="bar-mid"></span><span
                        class="bar-bot"></span></div>
            </div>
            <h2>Parking History - <?php echo $_REQUEST['reg']; ?></h2>
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
                    <li class="c-menu__item is-active" onclick="javascript:window.location.href = ('parkinglogs.php');" title="parkinlogs">
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
        <div class="page-content">Please find your parking history below. Photos of parking session entry and exit are
            available to access.</em>
            <?php $total = 0;
          if (is_array($rows)==0) { echo "<h4>You have no parking history, once you park you will see all parking logs here. </h4>"; }
          else {
          echo "<table><tr> <th>Registration</th><th> Location Parked</th> <th>Time Entered</th> <th>Time Exited</th> <th>Cost of Parking</th></tr>";
          for ($x = 0; $x <= sizeof($rows)-1; $x++) {
            $reg = strtoupper($rows[$x]['id']);
            $parkingID = $rows[$x]['parkingID'];
            $exitID = $rows[$x]['exitID'];  
            echo "<tr>";
            echo "<td>" . $reg . "</td>";
            echo "<td>" . $rows[$x]['location'] . "</td>";
            echo "<td>" . $rows[$x]['inTime'] = date("F j, Y, g:i a", $rows[$x]['inTime']) . "</td>";  
            if ($rows[$x]['outTime'] > 1600000000) { echo "<td>" . $rows[$x]['outTime'] = date("F j, Y, g:i a", $rows[$x]['outTime']) . "</td>"; } 
            else { echo "<td>" . "" . "</td>"; }
            echo "<td>" . "£" . $rows[$x]['cost'] . "</td>";
            echo '<td> <button class="deletebtn" onClick="deleteCar(' . "'" . $parkingID . "'" . ')"> Entry</button> </td>';
            if ($exitID != "") { 
                echo '<td> <button class="deletebtn" onClick="deleteCar(' . "'" . $exitID . "'" . ')"> Exit</button> </td>'; 
                echo '<td> <button class="deletebtn" onClick="getReceipt(' . "'" . $parkingID . "'" . ')"> Receipt</button> </td>'; 
            }
            echo "</tr>";
            }          
            echo "</table>";
          }
            ?>
        </div>
        <br></br>
        <h1 class="page-title">Historical View</h1>
        <?php 
          $carreggraph = $_REQUEST['reg'];
          $usernamegraph = $_SESSION['name'];
          include 'parkingHistoryCar.php'; ?>
        <canvas id="myChart" style="width:10%;max-width:100px"></canvas>
        <br>
    </div>
    <br></br>
    </div>
</main>
<!-- partial -->

<script>
let Dashboard = (() => {
    let global = {
        tooltipOptions: {
            placement: "right"
        },

        menuClass: ".c-menu"
    };


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

        }
    };

})();

Dashboard.init();
</script>

</body>

</html>