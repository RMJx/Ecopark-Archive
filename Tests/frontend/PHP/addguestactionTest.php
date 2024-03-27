<?php

class adminapiTest extends \PHPUnit\Framework\TestCase
{

    public function test_regexistsTrue()
    {   
      ## we want to check the function run on the results from the DVLA API Call works correctly
      ##MOCK DATA FROM DVLA API
      ##RESULTS RETURNED CASE

      #simulates SQL record being pulled from Database via mySQLi
      $testObject = array("make" => "AUDI", "co2Emissions" => 142, "colour" => "BLACK", "fuelType" => "PETROL", "engineCapacity" => 1900, "motStatus" => "VALID", "taxStatus" =>"VALID", "yearOfManufacture" =>2004);
      if(sizeof($testObject) > 2) { 
        $regValid = True;
      }
      else {
        $regValid = False;
      }
      $this->assertSame(True, $regValid);   
    }

    public function test_regexistFalse()
    {   
      ## we want to check the function run on the results from the DVLA API Call works correctly
      ##MOCK DATA FROM DVLA API
      ##RESULTS RETURNED CASE

      #simulates SQL record being pulled from Database via mySQLi
      $testObject = array("detail" => "Invalid format for field - vehicle registration number");
      if(sizeof($testObject) > 2) { 
        $regValid = True;
      }
      else {
        $regValid = False;
      }
      $this->assertSame(False, $regValid);   
    }

    
}
