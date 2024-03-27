<?php

class guestparkinglogsTest extends \PHPUnit\Framework\TestCase
{
    ##test the PHP function for printing the last 30 days for graph usage are correct.
    public function test_parkinglogstimeoutvalidCorrect()
    {   

        #simulates SQL record being pulled from Database via API, this case it is the parking history under one username
        $testObject2 = array(array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'),
        array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"P12UEX", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'),
        array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"P12UEX", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'),
        array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"P12UEF", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'));

        for ($x = 0; $x <= sizeof($testObject2)-1; $x++) {
            ##check there is a valid outTime on the logs else don't print it.
        if ($testObject2[$x]['outTime'] > 1600000000) { 
            $testObject2[$x]['outTime'] = date("M j, Y, g:i a", $testObject2[$x]['outTime']);
            $noOut = False;
          } 
          else { 
              $noOut = True;
          }
        }

        $this->assertSame($noOut, False);
        $this->assertSame($testObject2[2]['outTime'], "Jan 30, 2022, 10:58 pm");
    }

    
    public function test_parkinglogstimeoutvalidIncorrect()
    {   

        #simulates SQL record being pulled from Database via API, this case it is the parking history under one username
        $testObject2 = array(array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1600000000, "userName" => 'ryan', "id" =>"x99rmj", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'),
        array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1600000000, "userName" => 'ryan', "id" =>"P12UEX", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'),
        array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1600000000, "userName" => 'ryan', "id" =>"P12UEX", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'),
        array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1600000000, "userName" => 'ryan', "id" =>"P12UEF", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'));

        for ($x = 0; $x <= sizeof($testObject2)-1; $x++) {
            ##check there is a valid outTime on the logs else don't print it.
        if ($testObject2[$x]['outTime'] > 1600000000) { 
            $testObject2[$x]['outTime'] = date("M j, Y, g:i a", $testObject2[$x]['outTime']); 
          } 
          else { 
              $noOut = True;
          }
        }
        
        $this->assertSame($noOut, True);
        ##ouTime wont be formatted as it doesn't exist
        $this->assertSame($testObject2[1]['outTime'], 1600000000);
    }


}

