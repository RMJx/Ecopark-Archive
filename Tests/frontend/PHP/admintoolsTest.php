<?php

class adminapiTest extends \PHPUnit\Framework\TestCase
{

    public function test_userverifieddataCorrect()
    {   
      ##simulate more data of all users being pulled from DB
      $testObject = array(array("userName" => "x99rmj", "password" => "3432axczxqdqtd7asd98asu8djas", "email" => "test@gmail.com", "verified" => 0, "postcode" => "BT215OA", "firstName" => "Steve", "lastName" =>"Archie", "grandTotal" =>3.12, "APIKEY" => "3432axczxqdqtd7asd98asu8djas"),
                    array("userName" => "r9john", "password" => "3432axczxqdqtd7asd98asu8djas", "email" => "test2@gmail.com", "verified" => 1, "postcode" => "BT215OA", "firstName" => "Steve", "lastName" =>"Archie", "grandTotal" =>3.12, "APIKEY" => "3432axczxqdqtd7asd98asu8djas"));

      if (is_array($testObject)==0) {$validArray = False;}
            else { $validArray = True;
                for ($x = 0; $x <= sizeof($testObject)-1; $x++) {
                    if($testObject[$x]['verified'] == '1') $testObject[$x]['verified'] = "Yes";
                    else {$testObject[$x]['verified'] = "No";}
                }
              }
      $this->assertSame($validArray, True );
      $this->assertSame($testObject[0]['verified'], "No" );
      $this->assertSame($testObject[1]['verified'], "Yes" );
    }

    
    public function test_userverifieddataIncorrect()
    {   
      ##simulate more data of all users being pulled from DB
      $testObject = "";
      if (is_array($testObject)==0) {$validArray = False;}
            else { $validArray = True;
                for ($x = 0; $x <= sizeof($testObject)-1; $x++) {
                    if($testObject[$x]['verified'] == '1') $testObject[$x]['verified'] = "Yes";
                    else {$testObject[$x]['verified'] = "No";}
                }
              }
      $this->assertSame($validArray, False );
    }
    
}
