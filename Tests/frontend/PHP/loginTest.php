<?php

class adminapiTest extends \PHPUnit\Framework\TestCase
{

    public function test_LoginPass()
    {   
        

        #simulates SQL a matched record being pulled from Database via mySQLi (SELECT userName, password, verified, APIKey, email FROM users WHERE userName='$studentno' AND password='$pw')
        $testObject = array("userName" => "rj", "password" => "9f86d081884c7d659a2", "verified" => 1, "APIKey" => "55fd7084106bd4c65e4c8f85cd2491cc2a8", "email" => "rjohnston111@gmail.com");

        #simulate check of credentials
        if(sizeof($testObject)>0) {
            if($testObject["verified"] == 1) {
              #session_regenerate_id();
              #$_SESSION['loggedin'] = TRUE;
              #$_SESSION['name'] = $_POST['studentno'];
              #$_SESSION['email'] = $row['email'];
              #$_SESSION['APIKey'] = $row['APIKey'];
              #header('Location: home.php');
              $accessGranted = True;
            }
            else {
              $accessGranted = False;
            }
          }
          ##if no record is return, testObject will be size 0 as no match for username and password is found.
          else {
            $accessGranted = False;
          }

        


        $this->assertSame($accessGranted, True );
    }

    public function test_LoginFail()
    {   
        

        #simulates SQL a matched record being pulled from Database via mySQLi (SELECT userName, password, verified, APIKey, email FROM users WHERE userName='$studentno' AND password='$pw')
        $testObject = array("userName" => "rj", "password" => "9f86d081884c7d659a2", "verified" => 0, "APIKey" => "55fd7084106bd4c65e4c8f85cd2491cc2a8", "email" => "rjohnston111@gmail.com");

        #simulate check of credentials
        if(sizeof($testObject)>0) {
            if($testObject["verified"] == 1) {
              #session_regenerate_id();
              #$_SESSION['loggedin'] = TRUE;
              #$_SESSION['name'] = $_POST['studentno'];
              #$_SESSION['email'] = $row['email'];
              #$_SESSION['APIKey'] = $row['APIKey'];
              #header('Location: home.php');
              $accessGranted = True;
            }
            else {
              $accessGranted = False;
            }
          }
          ##if no record is return, testObject will be size 0 as no match for username and password is found.
          else {
            $accessGranted = False;
          }

        


        $this->assertNotSame($accessGranted, True );
    }
}
