<?php

class adminapiTest extends \PHPUnit\Framework\TestCase
{

    public function test_numberCars2()
    {   
      ## we want to check the function run on the results from this API Call works correctly "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $studentno ."&function=userscars&API-KEY=" . $APIKey;
      $testObject = array(array("id" => "x99rmj", "make" => "AUDI", "colour" => "BLACK", "isParked" => 0, "timeParked" => 0, "totalDue" => 0.00, "userName" =>"rj", "co2Class" =>3),array("id" => "ov20hdz", "make" => "polestar", "colour" => "BLUE", "isParked" => 0, "timeParked" => 0, "totalDue" => 0.00, "userName" =>"rj", "co2Class" =>2) );
      #In order to calculate the number of cars we simply get the size of the array returned from the call.
      if(is_array($testObject) == 1) {
        $numberCars = sizeof($testObject);
        }
        else $numberCars = 0;

      $this->assertSame($numberCars, 2 );
    }

    
    public function test_numberCars0()
    {   
      ## we want to check the function run on the results from this API Call works correctly "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $studentno ."&function=userscars&API-KEY=" . $APIKey;
      $testObject = array();
      #In order to calculate the number of cars we simply get the size of the array returned from the call.
      if(is_array($testObject) == 1) {
        $numberCars = sizeof($testObject);
        }
        else $numberCars = 0;

      $this->assertSame($numberCars, 0);
    }
    
}
