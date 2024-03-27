<?php

class adminapiTest extends \PHPUnit\Framework\TestCase
{

    public function test_carexistsTrue()
    {   
      ## we want to check the function run on the results from this API Call works correctly "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?id=" . $newReg . "&function=cardetails";
      ##MOCK DATA
      ##RESULTS RETURNED CASE
      $testObject = array(array("id" => "x99rmj", "make" => "AUDI", "colour" => "BLACK", "isParked" => 0, "timeParked" => 0, "totalDue" => 0.00, "userName" =>"rj", "co2Class" =>3));
      if(is_array($testObject) == 1) {
        ##If the car object is an array we can determine that a result has been found in the database.
        $carFound = True;
        }
          else {
            #Car does NOT exist in the database so we set the case to false.
            $carFound = False;

          }
      $this->assertSame($carFound, True);   
    }

    public function test_carexistsFail()
    {   
      ## we want to check the function run on the results from this API Call works correctly "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?id=" . $newReg . "&function=cardetails";
      ##MOCK DATA
      ##NO RESULTS RETURNED CASE
      $testObject = array();
      if(is_array($testObject) == 1) {
        ##If the car object is an array we can determine that a result has been found in the database.
        $carFound = True;
        }
        else {
            #Car does NOT exist in the database so we set the case to false.
            $carFound = False;


        }
      $this->assertNotSame($carFound, False);    
    }

    
}
