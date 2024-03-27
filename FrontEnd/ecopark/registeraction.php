<?php

$captchaValue = $_POST['g-recaptcha-response'];
$googleCaptchaKey = '6LeWFWceAAAAAEuWWHfkXszdFC7yqp143UYzoPyA';

if (isset($_POST['register']) && $captchaValue != "") {

    $captchaResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $googleCaptchaKey . '&response=' . $captchaValue);

    $response = json_decode($captchaResponse);
    if ($response->success) {
        
        $username = $_POST['username'];
        $email = $_POST['email'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $pcode = $_POST['pcode'];
        $psw = $_POST['psw'];

        $url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/registerapi/?id=" . $username . "&email=" . $email . "&pass=" . $psw . "&fname=" . $fname . "&lname=" . $lname . "&pcode=" . $pcode;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
           "Accept: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, ["id"=>$username, "email"=>$email, "pass"=>$psw, "fname"=>$fname, "lname"=>$lname, "pcode"=>$pcode]);
        $resp = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if($httpcode == 400) {header("Location: accountexists.php");}
        else {header('Location: registrationcomplete.php');}
    }
}

?>
