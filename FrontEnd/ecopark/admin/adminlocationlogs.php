<?php

session_start();

if(!isset($_SESSION['adminloggedin'])){
  header('Location: notloggedin.php');
  exit();


}

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/?function=locationlogs&location=" . str_replace(' ', '%20', $_REQUEST['location']) . "&adminusername=" . $_SESSION['adminusername'] . "&API-KEY=" . $_SESSION['APIKey'];
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
$locationlogs = json_decode($json_raw_str, true);


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
    <link href="../style/home.css" rel="stylesheet" type="text/css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <script src='https://use.fontawesome.com/releases/v5.0.8/js/all.js'></script>
    <script type="text/javascript">

    </script>
</head>
<script type="text/javascript">
      function deleteCar(parkingID) {  
         url = "/ecopark/imagedb/" + parkingID + ".jpg";
         window.open(url, '_blank').focus();
      }

</script>
</head>

<body class="sidebar-is-reduced">
    <header class="l-header">
        <div class="l-header__inner clearfix">
            <div class="c-header-icon js-hamburger">
                <div class="hamburger-toggle"><span class="bar-top"></span><span class="bar-mid"></span><span
                        class="bar-bot"></span></div>
            </div>
            <h2>Parking History - <?php echo $_REQUEST['location']; ?></h2>
        </div>
    </header>
    <div class="l-sidebar">
        <div class="logo">
            <img src="../white-32x32.png" alt="EP">
        </div>
        <div class="l-sidebar__content">
            <nav class="c-menu js-menu">
            <ul class="u-list">
                    <li class="c-menu__item has-submenu" onclick="javascript:window.location.href = ('adminhome.php');"
                        title="Home">
                        <div class="c-menu__item__inner"><i class="fa fa-home"></i>
                            <div class="c-menu-item__title"><span>Admin Dashboard</span></div>
                        </div>
                    </li>
                    <li class="c-menu__item is-active"
                        onclick="javascript:window.location.href = ('adminparkinglogs.php');" title="parkinlogs">
                        <div class="c-menu__item__inner"><i class="fa fa-history"></i>
                            <div class="c-menu-item__title"><span>Parking Logs</span></div>
                        </div>
                    <li class="c-menu__item has-submenu"
                        onclick="javascript:window.location.href = ('adminguestparkinglogs.php');" title="Guestlogs">
                        <div class="c-menu__item__inner"><i class="fa fa-history"></i>
                            <div class="c-menu-item__title"><span>Guest Parking Logs</span></div>
                        </div>
                    <li class="c-menu__item has-submenu" onclick="javascript:window.location.href = ('admintools.php');"
                        title="Account">
                        <div class="c-menu__item__inner"><i class="fa fa-cog"></i>
                            <div class="c-menu-item__title"><span>Admin Tools</span></div>
                        </div>
                    <li class="c-menu__item has-submenu"
                        onclick="javascript:window.location.href = ('adminlogout.php');" title="Logout">
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
        <div class="page-content">Please find the parking history for <?php echo $_REQUEST['location']; ?> below.</em>
            <?php $total = 0;
          if (is_array($locationlogs) == 0) { echo "<h4>There is no parking history, once parking occurs here you will see all parking logs here for this location. </h4>"; }
          else {
          echo "<table><tr> <th>Registration</th><th> Location Parked</th> <th>Time Entered</th> <th>Time Exited</th> <th>Cost of Parking</th></tr>";
          for ($x = 0; $x <= sizeof($locationlogs)-1; $x++) {
            $reg = strtoupper($locationlogs[$x]['id']);
            $parkingID = $locationlogs[$x]['parkingID'];
            $exitID = $locationlogs[$x]['exitID'];  
            echo "<tr>";
            echo "<td>" . $reg . "</td>";
            echo "<td>" . $locationlogs[$x]['location'] . "</td>";
            echo "<td>" . $locationlogs[$x]['inTime'] = date("F j, Y, g:i a", $locationlogs[$x]['inTime']) . "</td>";  
            if ($locationlogs[$x]['outTime'] > 1600000000) { echo "<td>" . $locationlogs[$x]['outTime'] = date("F j, Y, g:i a", $locationlogs[$x]['outTime']) . "</td>"; } 
            else { echo "<td>" . "" . "</td>"; }
            echo "<td>" . "£" . $locationlogs[$x]['cost'] . "</td>";
            echo '<td> <button class="deletebtn" onClick="deleteCar(' . "'" . $parkingID . "'" . ')"> Entry</button> </td>';
            if ($exitID != "") { echo '<td> <button class="deletebtn" onClick="deleteCar(' . "'" . $exitID . "'" . ')"> Exit</button> </td>'; }
            echo "</tr>";
            }          
            echo "</table>";
          }
            ?>
        </div>
        <br></br>
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