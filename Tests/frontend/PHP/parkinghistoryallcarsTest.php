<?php

class guestparkinglogsTest extends \PHPUnit\Framework\TestCase
{

    public function test_parkingHistoryTallysCorrect()
    {   
        
        #simulates SQL record being pulled from Database via API, this case it is the cars under one username
        $testObject = array(array("id" => "x99rmj", "make" => "AUDI", "colour" => "BLACK", "isParked" => 0, "timeParked" => 0, "totalDue" => 0.00, "userName" =>"rj", "co2Class" =>3), 
        array("id" => "P12UEX", "make" => "AUDI", "colour" => "BLACK", "isParked" => 0, "timeParked" => 0, "totalDue" => 0.00, "userName" =>"rj", "co2Class" =>3), 
        array("id" => "P12UEF", "make" => "AUDI", "colour" => "BLACK", "isParked" => 0, "timeParked" => 0, "totalDue" => 0.00, "userName" =>"rj", "co2Class" =>3));

        #simulates SQL record being pulled from Database via API, this case it is the parking history under one username
        $testObject2 = array(array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'),
        array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"P12UEX", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'),
        array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"P12UEX", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'),
        array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"P12UEF", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'));
        
        ##amount of times that car has parked in the history results pulled
        $carTallys = array_fill(0, sizeof($testObject), 0); #(1,2,1)
        ##cars registrations associated with user.
        $carRegistrations = array_fill(0, sizeof($testObject), "Null"); #3 (x99rmj,p12uex,p12uef)

        for ($x = 0; $x <= sizeof($testObject)-1; $x++) {
            $carRegistrations[$x] = strtoupper($testObject[$x]['id']);
            for ($y = 0; $y <= sizeof($testObject2)-1; $y++){
                if($testObject2[$y]['id'] == $testObject[$x]['id']) {
                    $carTallys[$x] = $carTallys[$x] + 1;
            }
            
            }
        }

        $this->assertSame(sizeof($carRegistrations), 3 );
        $this->assertSame(sizeof($carTallys), 3 );
        $this->assertSame($carRegistrations[1], "P12UEX" );
        $this->assertSame($carTallys[1], 2 );
    }

    public function test_parkingHistoryTallysIncorrect()
    {   
        
        #simulates SQL record being pulled from Database via API, this case it is the cars under one username
        $testObject = array(array("id" => "x99rmj", "make" => "AUDI", "colour" => "BLACK", "isParked" => 0, "timeParked" => 0, "totalDue" => 0.00, "userName" =>"rj", "co2Class" =>3), 
        array("id" => "P12UEX", "make" => "AUDI", "colour" => "BLACK", "isParked" => 0, "timeParked" => 0, "totalDue" => 0.00, "userName" =>"rj", "co2Class" =>3), 
        array("id" => "P12UEF", "make" => "AUDI", "colour" => "BLACK", "isParked" => 0, "timeParked" => 0, "totalDue" => 0.00, "userName" =>"rj", "co2Class" =>3));

        #simulates SQL record being pulled from Database via API, this case it is the parking history under one username
        $testObject2 = array(array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'),
        array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"P12UEX", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'),
        array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"P12UEX", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'),
        array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"P12UEF", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj'));
        
        ##amount of times that car has parked in the history results pulled
        $carTallys = array_fill(0, sizeof($testObject), 0); #(1,2,1)
        ##cars registrations associated with user.
        $carRegistrations = array_fill(0, sizeof($testObject), "Null"); #3 (x99rmj,p12uex,p12uef)

        for ($x = 0; $x <= sizeof($testObject)-1; $x++) {
            $carRegistrations[$x] = strtoupper($testObject[$x]['id']);
            for ($y = 0; $y <= sizeof($testObject2)-1; $y++){
                if($testObject2[$y]['id'] == $testObject[$x]['id']) {
                    $carTallys[$x] = $carTallys[$x] + 1;
            }
            
            }
        }

        $this->assertNotSame(sizeof($carRegistrations), 2 );
        $this->assertNotSame(sizeof($carTallys), 5 );
        $this->assertNotSame($carRegistrations[1], "P12UEF" );
        $this->assertNotSame($carTallys[1], 3 );
    }

}

