<?php

class homeTest extends \PHPUnit\Framework\TestCase
{

    public function test_dateConversionandStatusConversionPass()
    {   
        #simulates SQL record being pulled from Database via API $url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $studentno . "&function=userscars&API-KEY=" . $APIKey;
        $testObject = array(array("id" => "x99rmj", "make" => "AUDI", "colour" => "BLACK", "isParked" => 1, "timeParked" => 1646504454, "totalDue" => 0.00, "userName" =>"rj", "co2Class" =>3),
        array("id" => "p12uef", "make" => "AUDI", "colour" => "BLACK", "isParked" => 1, "timeParked" => 1646504454, "totalDue" => 0.00, "userName" =>"Ryan", "co2Class" =>2) );

        if(is_array($testObject) == 1) {
            for ($x = 0; $x <= sizeof($testObject)-1; $x++) {
                /* Convert the 0 and 1 used for SQL db to Yes or No values for clarity to user */
                  if($testObject[$x]['isParked']===1) {
                    $testObject[$x]['isParked'] = "Currently parked"; 
                    $testObject[$x]['timeParked'] = date("F j, Y, g:i a", $testObject[$x]['timeParked']);
                  }
                
                  if($testObject[$x]['isParked']===0) {
                    $testObject[$x]['isParked'] = "Not parked"; 
                    $testObject[$x]['timeParked'] = "Not parked";
                  }
                }
            }

        $this->assertSame($testObject[1]['timeParked'], 'March 5, 2022, 6:20 pm' );
        $this->assertSame($testObject[1]['isParked'], 'Currently parked' );
    }

    public function test_dateConversionandStatusConversionFail()
    {   
        #simulates SQL record being pulled from Database via API $url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $studentno . "&function=userscars&API-KEY=" . $APIKey;
        $testObject = array(array("id" => "x99rmj", "make" => "AUDI", "colour" => "BLACK", "isParked" => 1, "timeParked" => 1646504454, "totalDue" => 0.00, "userName" =>"rj", "co2Class" =>3),
        array("id" => "p12uef", "make" => "AUDI", "colour" => "BLACK", "isParked" => 1, "timeParked" => 1646504454, "totalDue" => 0.00, "userName" =>"Ryan", "co2Class" =>2) );

        if(is_array($testObject) == 1) {
            for ($x = 0; $x <= sizeof($testObject)-1; $x++) {
                /* Convert the 0 and 1 used for SQL db to Yes or No values for clarity to user */
                  if($testObject[$x]['isParked']===1) {
                    $testObject[$x]['isParked'] = "Currently parked"; 
                    $testObject[$x]['timeParked'] = date("F j, Y, g:i a", $testObject[$x]['timeParked']);
                  }
                
                  if($testObject[$x]['isParked']===0) {
                    $testObject[$x]['isParked'] = "Not parked"; 
                    $testObject[$x]['timeParked'] = "Not parked";
                  }
                }
            }

        $this->assertNotSame($testObject[1]['timeParked'], 'March 6, 2022, 6:20 pm' );
        $this->assertNotSame($testObject[1]['isParked'], 'Not parked' );
    }

    public function test_percentageFilledcalcTestPass()
    {   
        #simulates SQL record being pulled from Database via API $url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $studentno . "&function=userscars&API-KEY=" . $APIKey;
        $testObject = array(array("location" => "Bangor", "rate" => "2.1", "locationid" => "1", "capacity" => 100,"free" => 90),
        array("location" => "Belfast Central", "rate" => "1.55", "locationid" => "2", "capacity" => 200,"free" => 150) );

        ##test the percentageFilledcalculation function
        $percentFilled = 0;
        for ($x = 0; $x <= sizeof($testObject)-1; $x++) {
            $percentFilled = round(abs((($testObject[$x]['free']/$testObject[$x]['capacity'])-1) * 100 ),1);
        }
        $this->assertSame($percentFilled, 25.0);
    }
    public function test_percentageFilledcalcTestFail()
    {   
        #simulates SQL record being pulled from Database via API $url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $studentno . "&function=userscars&API-KEY=" . $APIKey;
        $testObject = array(array("location" => "Belfast Central", "rate" => "1.55", "locationid" => "2", "capacity" => 200,"free" => 150),
                    array("location" => "Bangor", "rate" => "2.1", "locationid" => "1", "capacity" => 100,"free" => 90));

        ##test the percentageFilledcalculation function
        $percentFilled = 0;
        for ($x = 0; $x <= sizeof($testObject)-1; $x++) {
            $percentFilled = round(abs((($testObject[$x]['free']/$testObject[$x]['capacity'])-1) * 100 ),1);
        }
        $this->assertNotSame($percentFilled, 15.0);
    }
}