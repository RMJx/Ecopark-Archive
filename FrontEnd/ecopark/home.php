<?php


session_start();
$studentno = $_SESSION['name'];



if(!isset($_SESSION['loggedin'])){
  header('Location: notloggedin.php');
  exit();


}

$APIKey = str_replace(' ', '', $_SESSION['APIKey']);

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $studentno . "&function=userscars&API-KEY=" . $APIKey;
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

if(is_array($cars) == 1) {
for ($x = 0; $x <= sizeof($cars)-1; $x++) {
    /* Convert the 0 and 1 used for SQL db to Yes or No values for clarity to user */
      if($cars[$x]['isParked']===1) {
        $cars[$x]['isParked'] = "Currently parked"; 
        $cars[$x]['timeParked'] = date("F j, Y, g:i a", $cars[$x]['timeParked']);
      }
    
      if($cars[$x]['isParked']===0) {
        $cars[$x]['isParked'] = "Not parked"; 
        $cars[$x]['timeParked'] = "Not parked";
      }
    }
}

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $studentno ."&function=co2rates&API-KEY=" . $APIKey;
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

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $studentno ."&function=weeklyrates&API-KEY=" . $APIKey;
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
$weeklyRates = json_decode($json_raw_str, true);

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $studentno ."&function=locationinfo&API-KEY=" . $APIKey;
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
$grandTotal = $user['grandTotal'];

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $_SESSION['name'] ."&function=parkinghistoryuser&API-KEY=" . $APIKey;

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
$parkinghistory = json_decode($json_raw_str, true);

$weekCounter = 0;
if(is_array($parkinghistory) == 1) {
    $weekStart = time() - 604800;
    for($x = 0; $x <= sizeof($parkinghistory)-1; $x++) {
        if($parkinghistory[$x]['inTime'] > $weekStart) $weekCounter++;
    }
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
    <script src="js/index.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"> </script>
    <script src="https://applepay.cdn-apple.com/jsapi/v1/apple-pay-sdk.js"></script>
    <script type="text/javascript">
    function deleteCar(reg) {

        var accountNumber = <?php echo json_encode($_SESSION['name'], JSON_HEX_TAG); ?>;
        var APIKey = <?php echo json_encode($_SESSION['APIKey'], JSON_HEX_TAG); ?>;
        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?id=" + reg + "&API-KEY=" + APIKey + "&username=" + accountNumber + "&function=deletecar";

        if (confirm('Are you sure you want to remove this car from your account?')) {
            var xhr = new XMLHttpRequest();
            xhr.open("DELETE", url);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    alert(xhr.responseText.substring(1, xhr.responseText.length - 1));
                    window.location.replace("home.php");
                }
            };
            xhr.send();
        }
    }

    function viewLogs(reg) {

        var url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/ecopark/parkinglogsfiltered.php?reg=" + reg;
        window.location.href = url;
    }

    function onApplePayButtonClicked(total) {

        if (!ApplePaySession) {
            return;
        }

        // Define ApplePayPaymentRequest
        const request = {
            "countryCode": "GB",
            "currencyCode": "GBP",
            "merchantCapabilities": [
                "supports3DS"
            ],
            "supportedNetworks": [
                "visa",
                "masterCard",
                "amex",
                "discover"
            ],
            "total": {
                "label": "Demo (Card is not charged)",
                "type": "final",
                "amount": total
            }
        };

        // Create ApplePaySession
        const session = new ApplePaySession(3, request);

        session.onvalidatemerchant = async event => {
            // Call your own server to request a new merchant session.
            const merchantSession = await validateMerchant();
            session.completeMerchantValidation(merchantSession);
        };

        session.onpaymentmethodselected = event => {
            // Define ApplePayPaymentMethodUpdate based on the selected payment method.
            // No updates or errors are needed, pass an empty object.
            const update = {};
            session.completePaymentMethodSelection(update);
        };

        session.onshippingmethodselected = event => {
            // Define ApplePayShippingMethodUpdate based on the selected shipping method.
            // No updates or errors are needed, pass an empty object. 
            const update = {};
            session.completeShippingMethodSelection(update);
        };

        session.onshippingcontactselected = event => {
            // Define ApplePayShippingContactUpdate based on the selected shipping contact.
            const update = {};
            session.completeShippingContactSelection(update);
        };

        session.onpaymentauthorized = event => {
            // Define ApplePayPaymentAuthorizationResult
            const result = {
                "status": ApplePaySession.STATUS_SUCCESS
            };
            session.completePayment(result);
        };

        session.oncancel = event => {
            // Payment cancelled by WebKit
        };

        session.begin();
    }
    </script>
    <title>Ecopark - Home</title>
    <link href="style/home.css" rel="stylesheet" type="text/css">
    <style> 
tr {
  border-bottom: 0.5px solid #ddd;
}
    </style>
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
            <h2>Dashboard</h2>
        </div>
    </header>
    <div class="l-sidebar">
        <div class="logo">
            <img src="white-32x32.png" alt="EP">
        </div>
        <div class="l-sidebar__content">
            <nav class="c-menu js-menu">
                <ul class="u-list">
                    <li class="c-menu__item is-active" onclick="javascript:window.location.href = ('home.php');"
                        title="Home">
                        <div class="c-menu__item__inner"><i class="fa fa-home"></i>
                            <div class="c-menu-item__title"><span>Home Dashboard</span></div>
                        </div>
                    </li>
                    <li class="c-menu__item has-submenu" onclick="javascript:window.location.href = ('addcar.php');"
                        title="Car">
                        <div class="c-menu__item__inner"><i class="fa fa-car"></i>
                            <div class="c-menu-item__title"><span>Add New Car</span></div>
                        </div>
                    <li class="c-menu__item has-submenu" onclick="javascript:window.location.href = ('addguest.php');"
                        title="Guest">
                        <div class="c-menu__item__inner"><i class="fa fa-user"></i>
                            <div class="c-menu-item__title"><span>Add new Guest Parking</span></div>
                        </div>
                    <li class="c-menu__item has-submenu"
                        onclick="javascript:window.location.href = ('parkinglogs.php');" title="parkinlogs">
                        <div class="c-menu__item__inner"><i class="fa fa-history"></i>
                            <div class="c-menu-item__title"><span>Parking Logs</span></div>
                        </div>
                    <li class="c-menu__item has-submenu"
                        onclick="javascript:window.location.href = ('guestparkinglogs.php');" title="Guestlogs">
                        <div class="c-menu__item__inner"><i class="fa fa-history"></i>
                            <div class="c-menu-item__title"><span>Guest Parking Logs</span></div>
                        </div>
                    <li class="c-menu__item has-submenu"
                        onclick="javascript:window.location.href = ('accountdetails.php');" title="Account">
                        <div class="c-menu__item__inner"><i class="fa fa-cog"></i>
                            <div class="c-menu-item__title"><span>Account Settings</span></div>
                        </div>
                    <li class="c-menu__item has-submenu" onclick="javascript:window.location.href = ('logout.php');"
                        title="Logout">
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
        <h1 class="page-title">Your Parking</h1>
        <div class="page-content">Welcome back <em><?= $fname ?>! Please find your parking information and details
                below.</em>
            <br></br>
            <table>
                <tr>
                    <th>Registration</th>
                    <th>Make</th>
                    <th>Colour</th>
                    <th>Current Status</th>
                    <th>Time Parked</th>
                    <th>Total Due</th>
                </tr>
                <?php 
          $total = 0;
          if(is_array($cars) == 1) {
          for ($x = 0; $x <= sizeof($cars)-1; $x++) {
            $reg = strtoupper($cars[$x]['id']);
            echo "<tr>";
            echo "<td>" . $reg . "</td>";
            echo "<td>" . $cars[$x]['make'] . "</td>";
            echo "<td>" . $cars[$x]['colour'] . "</td>";
            echo "<td>" . $cars[$x]['isParked'] . "</td>";
            echo "<td>" . $cars[$x]['timeParked']  . "</td>";
            echo "<td> £" . $cars[$x]['totalDue'] . "</td>";
            echo '<td> <button class="deletebtn" onClick="deleteCar(' . "'" . $reg . "'" . ')"> Remove Car </button> </td>';
            echo '<td> <button class="deletebtn" onClick="viewLogs(' . "'" . $reg . "'" . ')"> View Logs </button> </td>';
            echo "</tr>";
            $total = $total + $cars[$x]['totalDue'];
            $guestExtras = $grandTotal - $total;
            }
        }
            echo "</table>";
            echo "<h3> Total Due for Parking : £" . $total . "</h3>";
            if ($total > 0.00) echo '<apple-pay-button buttonstyle="black" type="plain" locale="en" onClick="onApplePayButtonClicked(' . "'" . $total . "'" . ')"></apple-pay-button>';
            ?>
        </div>
        <br>
        <h1 class="page-title">Your Car(s) Details</h1>
        <?php
        if(is_array($cars) == 1) {
        include 'APICall.php';
        }
        ?>
        <br>
        <h1 class="page-title">Historical View</h1>
        <?php 
            $usernamegraph = $studentno;
            include 'parkingHistoryUser.php'; ?>
        <canvas id="myChart" style="width:10%;max-width:100px"></canvas>
        <h1 class="page-title">Our locations</h1>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
        <style>
        /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
        #map {
            height: 100%;
        }
        </style>
        <script>
        // Initialize and add the map
        function initMap() {
            const marker = {
                lat: 54.602436,
                lng: -5.921604
            };
            const marker2 = {
                lat: 54.601436,
                lng: -5.941604
            };
            const marker3 = {
                lat: 54.663811,
                lng: -5.667494
            };
            const marker4 = {
                lat: 54.626767,
                lng: -5.913112
            };
            const marker5 = {
                lat: 54.602641,
                lng: -5.921452
            };
            const marker6 = {
                lat: 54.6059,
                lng: -5.910151
            };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 11,
                center: marker,
            });

            new google.maps.Marker({
                position: marker,
                map,
                title: "Belfast City Centre",
            });
            new google.maps.Marker({
                position: marker2,
                map,
                title: "Belfast Victoria Square",
            });
            new google.maps.Marker({
                position: marker3,
                map,
                title: "Bangor",
            });
            new google.maps.Marker({
                position: marker4,
                map,
                title: "Belfast Quayside",
            });
            new google.maps.Marker({
                position: marker5,
                map,
                title: "Belfast Obel Towers",
            });
            new google.maps.Marker({
                position: marker6,
                map,
                title: "Belfast Titanic Quarter",
            });

            map.setOptions({
                draggable: false,
                zoomControl: false,
                scrollwheel: false,
                disableDoubleClickZoom: true
            });

        }
        </script>
        <div id="map"></div>

        <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
        <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyu1-81iwqafSseuLx9TxZMa9T4EPj00s&callback=initMap&v=weekly&channel=2"
            async></script>

        <br></br>
        <h1 class="page-title">Capacity of Locations</h1>
        <div class="page-content">All locations listed below for Ecopark with their capacity.</em>
            <br>
            <table>
                <tr>
                    <th>Location</th>
                    <th>Capacity</th>
                    <th>Spaces Free</th>
                    <th>Percentage Filled</th>
                    <th>Rate</th>
                </tr>
                <?php
        for ($x = 0; $x <= sizeof($locations)-1; $x++) {
            $percentFilled = number_format(abs((($locations[$x]['free']/$locations[$x]['capacity'])-1) * 100 ),2);
            echo "<tr>";
            echo "<td>" . $locations[$x]['location'] . "</td>";
            echo "<td>" . $locations[$x]['capacity'] . "</td>";
            echo "<td>" . $locations[$x]['free'] . "</td>";
            if($percentFilled > 70) echo "<td><class= style='color:red;'>" . $percentFilled . "%" . "</td>";
            elseif($percentFilled >35 and $percentFilled <=70) echo "<td>< class= style='color:orange;'>" . $percentFilled . "%" . "</td>";
            else echo "<td><class= style='color:green;'>" . $percentFilled . "%" . "</td>";
            echo "<td>" . "£" . $locations[$x]['rate'] . " /hr" . "</td>";
            }
            echo "</tr>";
            ?>
            </table>
        </div>
        <br>
        <h1 class="page-title">Current CO2 Rates</h1>
        <div class="page-content"></em>
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
            }
            echo "</tr>";
            ?>
            </table>
        </div>
        <br>
        <h1 class="page-title">Current Weekly Rates</h1>
        <div class="page-content"></em>
            <table>
                <tr>
                    <th>Tier</th>
                    <th>Tier Start</th>
                    <th>Tier End</th>
                    <th>Charge Mulitplier</th>
                </tr>
                <?php
        for ($x = 0; $x <= sizeof($weeklyRates)-1; $x++) {
            echo "<tr>";
            echo "<td>" . $weeklyRates[$x]['tier'] . "</td>";
            echo "<td>" . $weeklyRates[$x]['tierStart'] . " days" . "</td>";
            echo "<td>" . $weeklyRates[$x]['tierEnd'] . " days" . "</td>";
            echo "<td>" . $weeklyRates[$x]['charge'] . " x" . "</td>";
            }
            echo "</tr>";
            ?>
            </table>
            <br>
            <h3> Parking Tally for the Week: <?php echo $weekCounter ?> </h3>
        </div>
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