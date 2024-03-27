<?php

session_start();


if(!isset($_SESSION['adminloggedin'])){
  header('Location: notloggedin.php');
  exit();


}

$APIKey = str_replace(' ', '', $_SESSION['APIKey']);
$adminUsername = $_SESSION['adminusername'];

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/?function=cars&adminusername=".$adminUsername."&API-KEY=" . $APIKey;
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
$cars = json_decode($json_raw_str, true);

for ($x = 0; $x <= sizeof($cars)-1; $x++) {
/* Convert the 0 and 1 used for SQL db to Yes or No values for clarity to user */
  if($cars[$x]['isParked']===1) {
    $cars[$x]['isParked'] = "Currently parked"; 
    $cars[$x]['timeParked'] = date("F j, Y, g:i a", $cars[$x]['timeParked']);
  }

  if($cars[$x]['isParked']===0) {
    $cars[$x]['isParked'] = "Not parked"; 
    $cars[$x]['timeParked'] = "N/A";
  }
}

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $adminUsername ."&function=locationinfo&API-KEY=" . $APIKey;
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
$locations = json_decode($json_raw_str, true);

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/?adminusername=" . $adminUsername . "&API-KEY=" . $APIKey . "&function=co2rates";
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
$co2rates = json_decode($json_raw_str, true);

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/?adminusername=" . $adminUsername . "&API-KEY=" . $APIKey . "&function=weeklyrates";
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
$weeklyrates = json_decode($json_raw_str, true);

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/?function=guestparking&adminusername=".$adminUsername."&API-KEY=" . $APIKey;
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
$guests = json_decode($json_raw_str, true);


?>
<!DOCTYPE html>
<html>

<head>
    <link rel="apple-touch-icon" sizes="180x180" href="/ecopark/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/ecopark/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/ecopark/favicon-16x16.png">
    <link rel="manifest" href="/ecopark/site.webmanifest">
    <meta charset="utf-8">
    <script src="js/index.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"> </script>
    <script type="text/javascript">
    var username = <?php echo json_encode($_SESSION['adminusername'], JSON_HEX_TAG); ?>;
    var APIKey = <?php echo json_encode($_SESSION['APIKey'], JSON_HEX_TAG); ?>;

    function deleteCar(reg) {

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?id=" + reg + "&API-KEY=" + APIKey +
            "&username=" + username;

        if (confirm('Are you sure you want to remove this car from your account?')) {
            var xhr = new XMLHttpRequest();
            xhr.open("DELETE", url);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    alert(xhr.responseText.substring(1, xhr.responseText.length - 1));
                    window.location.replace("adminhome.php");
                }
            };
            xhr.send();
        }
    }

    function deleteLocation(location) {

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/?location=" + location + "&API-KEY=" +
            APIKey + "&adminusername=" + username;

        if (confirm('Are you sure you want to remove this location from Ecopark?')) {
            var xhr = new XMLHttpRequest();
            xhr.open("DELETE", url);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    alert(xhr.responseText.substring(1, xhr.responseText.length - 1));
                    window.location.replace("adminhome.php");
                }
            };
            xhr.send();
        }
    }

    function viewParkingLogs(location) {

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/ecopark/admin/adminlocationlogs.php?location=" +
            location;
        window.location.href = url;
    }

    function viewParkedCars(location) {

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/ecopark/admin/adminparkedlogs.php?location=" +
            location;
        window.location.href = url;
    }

    function changeCapacity(location) {

        var capacity = prompt("Enter the amount you wish to increase capacity by: ");
        if (capacity === null) {
            return;
        }

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/?capacity=" + capacity +
            "&function=capacitychange&location=" + location + "&API-KEY=" + APIKey + "&adminusername=" + username;

        var xhr = new XMLHttpRequest();
        xhr.open("PUT", url);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                alert("Capacity changed.");
                window.location.replace("adminhome.php");
            }
        };

        xhr.send();
    }

    function changeRate(location) {

        var rate = prompt("Enter the new rate: ");
        if (rate === null) {
            return;
        }

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/?rate=" + rate +
            "&function=ratechange&location=" + location + "&API-KEY=" + APIKey + "&adminusername=" + username;

        var xhr = new XMLHttpRequest();
        xhr.open("PUT", url);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                alert("Rate changed.");
                window.location.replace("adminhome.php");
            }
        };

        xhr.send();
    }

    function changeCO2(tier) {

        var rate = prompt("Enter the new rate: ");
        if (rate === null) {
            return;
        }

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/?charge=" + rate +
            "&function=co2ratechange&tier=" + tier + "&API-KEY=" + APIKey + "&adminusername=" + username;

        var xhr = new XMLHttpRequest();
        xhr.open("PUT", url);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                alert("Rate changed.");
                window.location.replace("adminhome.php");
            }
        };

        xhr.send();
    }

    function changeCO2starttier(tier) {

        var rate = prompt("Enter the new start tier value: ");
        if (rate === null) {
            return;
        }

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/?tierstart=" + rate +
            "&function=co2starttier&tier=" + tier + "&API-KEY=" + APIKey + "&adminusername=" + username;

        var xhr = new XMLHttpRequest();
        xhr.open("PUT", url);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                alert("Rate changed.");
                window.location.replace("adminhome.php");
            }
        };

        xhr.send();
    }

    function changeCO2endtier(tier) {

        var rate = prompt("Enter the new end tier value: ");
        if (rate === null) {
            return;
        }

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/?tierend=" + rate +
            "&function=co2endtier&tier=" + tier + "&API-KEY=" + APIKey + "&adminusername=" + username;

        var xhr = new XMLHttpRequest();
        xhr.open("PUT", url);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                alert("Rate changed.");
                window.location.replace("adminhome.php");
            }
        };

        xhr.send();
    }

    function changeWeeklystarttier(tier) {

        var rate = prompt("Enter the new start tier value: ");
        if (rate === null) {
            return;
        }

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/?tierstart=" + rate +
            "&function=weeklystarttier&tier=" + tier + "&API-KEY=" + APIKey + "&adminusername=" + username;

        var xhr = new XMLHttpRequest();
        xhr.open("PUT", url);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                alert("Rate changed.");
                window.location.replace("adminhome.php");
            }
        };

        xhr.send();
    }

    function changeWeeklyendtier(tier) {

        var rate = prompt("Enter the new end tier value: ");
        if (rate === null) {
            return;
        }

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/?tierend=" + rate +
            "&function=weeklyendtier&tier=" + tier + "&API-KEY=" + APIKey + "&adminusername=" + username;

        var xhr = new XMLHttpRequest();
        xhr.open("PUT", url);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                alert("Rate changed.");
                window.location.replace("adminhome.php");
            }
        };

        xhr.send();
    }

    function changeWeekly(tier) {

        var rate = prompt("Enter the new rate: ");
        if (rate === null) {
            return;
        }

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/?charge=" + rate +
            "&function=weeklyratechange&tier=" + tier + "&API-KEY=" + APIKey + "&adminusername=" + username;

        var xhr = new XMLHttpRequest();
        xhr.open("PUT", url);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                alert("Rate changed.");
                window.location.replace("adminhome.php");
            }
        };

        xhr.send();
    }


    function viewLogs(reg, username) {

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/ecopark/admin/adminregparkinglogs.php?reg=" + reg +
            "&username=" + username;
        window.location.href = url;
    }
    </script>
    <title>Ecopark - Admin UI</title>
    <link href="../style/home.css" rel="stylesheet" type="text/css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <script src='https://use.fontawesome.com/releases/v5.0.8/js/all.js'></script>
</head>
</head>

<body class="sidebar-is-reduced">
    <header class="l-header">
        <div class="l-header__inner clearfix">
            <div class="c-header-icon js-hamburger">
                <div class="hamburger-toggle"><span class="bar-top"></span><span class="bar-mid"></span><span
                        class="bar-bot"></span></div>
            </div>
            <h2>Admin - Dashboard</h2>
        </div>
    </header>
    <div class="l-sidebar">
        <div class="logo">
            <img src="../white-32x32.png" alt="EP">
        </div>
        <div class="l-sidebar__content">
            <nav class="c-menu js-menu">
                <ul class="u-list">
                    <li class="c-menu__item is-active" onclick="javascript:window.location.href = ('adminhome.php');"
                        title="Home">
                        <div class="c-menu__item__inner"><i class="fa fa-home"></i>
                            <div class="c-menu-item__title"><span>Admin Dashboard</span></div>
                        </div>
                    </li>
                    <li class="c-menu__item has-submenu"
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
        <h1 class="page-title">Registered Customers</h1>
        <div class="page-content">Registered cars below can be found and their current status.</em>
            <br></br>
            <?php 

            if(is_array($cars) == 0) echo "<h3> No guest parking information available. </h3>";
            else {
            echo   "<table>
                     <tr>
                <th>Registration</th>
                <th>Make</th>
                <th>Colour</th>
                <th>Parking Status</th>
                <th>Username</th>
                <th>Time Parked</th>
                <th>Total Due</th>
                </tr>";
            }
          $total = 0;
          for ($x = 0; $x <= sizeof($cars)-1; $x++) {
            $reg = strtoupper($cars[$x]['id']);
            echo "<tr>";
            echo "<td>" . $reg . "</td>";
            echo "<td>" . $cars[$x]['make'] . "</td>";
            echo "<td>" . $cars[$x]['colour'] . "</td>";
            echo "<td>" . $cars[$x]['isParked'] . "</td>";
            echo "<td>" . $cars[$x]['userName'] . "</td>";
            echo "<td>" . $cars[$x]['timeParked']  . "</td>";
            echo "<td> £" . $cars[$x]['totalDue'] . "</td>";
            echo '<td> <button class="deletebtn" onClick="deleteCar(' . "'" . $reg . "'" . ')"> Remove Car </button> </td>';
            echo '<td> <button class="deletebtn" onClick="viewLogs(' . "'" . $reg . "'" . ',' . "'" . $cars[$x]['userName'] . "'" . ')"> View Logs </button> </td>';
            echo "</tr>";
            $total = $total + $cars[$x]['totalDue'];
            }
            echo "</table>";
            echo "<h3> Total Owed : £" . $total . "</h3>";
            ?>
        </div>
        <br>
        <h1 class="page-title">Currently Registered Guest Parking</h1>
        <div class="page-content">All registered guests can be found below and their details associated.</em>
            <br>
            <?php $total = 0;
                if(is_array($guests) == 0) echo "<h3> No guest parking information available. </h3>";
                else {
                    echo "            <table>
                    <tr>
                        <th>Registration</th>
                        <th>Hours Available</th>
                        <th>CO2 Classification</th>
                        <th>Location</th>
                    </tr>";
          for ($x = 0; $x <= sizeof($guests)-1; $x++) {
            $reg = strtoupper($guests[$x]['id']);
            echo "<tr>";
            echo "<td>" . $reg . "</td>";
            echo "<td>" . $guests[$x]['hours'] . "</td>";
            echo "<td>" . $guests[$x]['co2Class'] . "</td>";
            echo "<td>" . $guests[$x]['location'] . "</td>";
            }
            echo "</tr>";
            echo "</table>";
        }
            ?>
        </div>
        <br></br>
        <h1 class="page-title">Capacity of Locations</h1>
        <div class="page-content">All locations listed below for Ecopark, rates and capacity can be modified.</em>
            <br>
            <table>
                <tr>
                    <th>Location</th>
                    <th>Capacity</th>
                    <th>Spaces Free</th>
                    <th>Rate</th>
                    <th>Percentage Filled</th>
                </tr>
                <?php
        for ($x = 0; $x <= sizeof($locations)-1; $x++) {
            $percentFilled = abs((($locations[$x]['free']/$locations[$x]['capacity'])-1) * 100 );
            echo "<tr>";
            echo "<td>" . $locations[$x]['location'] . "</td>";
            echo "<td>" . $locations[$x]['capacity'] . "</td>";
            echo "<td>" . $locations[$x]['free'] . "</td>";
            echo "<td>" . "£" . $locations[$x]['rate'] . " /hr" . "</td>";
            if($percentFilled > 70) echo "<td><class= style='color:red;'>" . $percentFilled . "%" . "</td>";
            elseif($percentFilled >35 and $percentFilled <=70) echo "<td>< class= style='color:orange;'>" . $percentFilled . "%" . "</td>";
            else echo "<td><class= style='color:green;'>" . $percentFilled . "%" . "</td>";
            echo '<td> <button class="deletebtn" onClick="changeRate(' . "'" . $locations[$x]['location'] . "'" . ')"> Update Rate </button> </td>';
            echo '<td> <button class="deletebtn" onClick="changeCapacity(' . "'" . $locations[$x]['location'] . "'" . ')"> Update Capacity </button> </td>';
            echo '<td> <button class="deletebtn" onClick="deleteLocation(' . "'" . $locations[$x]['location'] . "'" . ')"> Delete Location </button> </td>';
            echo '<td> <button class="deletebtn" onClick="viewParkingLogs(' . "'" . $locations[$x]['location'] . "'" . ')"> View Logs </button> </td>';
            echo '<td> <button class="deletebtn" onClick="viewParkedCars(' . "'" . $locations[$x]['location'] . "'" . ')"> View Parked Cars </button> </td>';
            }
            echo "</tr>";
            ?>
            </table>
        </div>
        <br></br>
        <h1 class="page-title">CO2 Rates</h1>
        <div class="page-content">All rates for CO2 Charges can be displayed below and their associated tiers.</em>
            <br>
            <table>
                <tr>
                    <th>Tier</th>
                    <th>Tier Start</th>
                    <th>Tier End</th>
                    <th>Charge Mulitplier</th>
                </tr>
                <?php
        for ($x = 0; $x <= sizeof($co2rates)-1; $x++) {
            echo "<tr>";
            echo "<td>" . $co2rates[$x]['tier'] . "</td>";
            echo "<td>" . $co2rates[$x]['tierStart'] . " g/km" . "</td>";
            echo "<td>" . $co2rates[$x]['tierEnd'] . " g/km" . "</td>";
            echo "<td>" . $co2rates[$x]['charge'] . " x" . "</td>";
            echo '<td> <button class="deletebtn" onClick="changeCO2(' . "'" . $co2rates[$x]['tier'] . "'" . ')"> Update Rate </button> </td>';
            echo '<td> <button class="deletebtn" onClick="changeCO2starttier(' . "'" . $co2rates[$x]['tier'] . "'" . ')"> Update Start Tier </button> </td>';
            echo '<td> <button class="deletebtn" onClick="changeCO2endtier(' . "'" . $co2rates[$x]['tier'] . "'" . ')"> Update End Tier </button> </td>';
            }
            echo "</tr>";
            ?>
            </table>
        </div>
        <br></br>
        <h1 class="page-title">Weekly Bias Rates</h1>
        <div class="page-content">All rates for Weekly Bias Charges can be displayed below and their associated
            tiers.</em>
            <br>
            <table>
                <tr>
                    <th>Tier</th>
                    <th>Tier Start</th>
                    <th>Tier End</th>
                    <th>Charge Mulitplier</th>
                </tr>
                <?php
        for ($x = 0; $x <= sizeof($weeklyrates)-1; $x++) {
            echo "<tr>";
            echo "<td>" . $weeklyrates[$x]['tier'] . "</td>";
            echo "<td>" . $weeklyrates[$x]['tierStart'] . " days" . "</td>";
            echo "<td>" . $weeklyrates[$x]['tierEnd'] . " days" . "</td>";
            echo "<td>" . $weeklyrates[$x]['charge'] . " x" . "</td>";
            echo '<td> <button class="deletebtn" onClick="changeWeekly(' . "'" . $weeklyrates[$x]['tier'] . "'" . ')"> Update Rate </button> </td>';
            echo '<td> <button class="deletebtn" onClick="changeWeeklystarttier(' . "'" . $weeklyrates[$x]['tier'] . "'" . ')"> Update Start Tier </button> </td>';
            echo '<td> <button class="deletebtn" onClick="changeWeeklyendtier(' . "'" . $weeklyrates[$x]['tier'] . "'" . ')"> Update End Tier </button> </td>';
            }
            echo "</tr>";
            ?>
            </table>
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