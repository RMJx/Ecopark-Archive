<?php

class homeTest extends \PHPUnit\Framework\TestCase
{

    public function test_invoiceFormattingTestCorrect()
    {   
        #simulates singular SQL record being pulled from Database via API $url = $url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $_SESSION['name'] ."&function=singlehistory&API-KEY=" . $_SESSION['APIKey'] . "&parkingID=" . $parkingRef;
        $testObject = array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj');

        $inTime = $testObject['inTime']; ##1643583100
        $outTime = $testObject['outTime']; ##1643583500
        $duration = ($outTime - $inTime)/3600; ##should equal 0.1111111111111

        ##calculate base cost
        $baseCost = round($testObject['baseRate'] * $duration,2); ##1.6 x 0.111111111111 = 0.18
        ##calculate weekly bias cost
        $weeklyChargeCost = round(($baseCost* $testObject['weeklyCharge']) - $baseCost,2); #0.18 * 1.2 - 0.18 = 0.04
        $co2ChargeCost = round(($baseCost * $testObject['co2Charge']) - $baseCost,2); #0.18 * 1.6 - 0.18 = 0.11

        $this->assertSame($testObject['baseRate'], 1.6 );
        $this->assertSame($testObject['weeklyCharge'], 1.2 );
        $this->assertSame($testObject['co2Charge'], 1.6 );
        $this->assertSame($weeklyChargeCost, 0.04);
        $this->assertSame($co2ChargeCost, 0.11);
        $this->assertSame($baseCost, 0.18);
    }

    
    public function test_invoiceFormattingTestIncorrect()
    {   
        #simulates singular SQL record being pulled from Database via API $url = $url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $_SESSION['name'] ."&function=singlehistory&API-KEY=" . $_SESSION['APIKey'] . "&parkingID=" . $parkingRef;
        $testObject = array("parkingID" => "202201236185732x99rmj", "location" => "Bangor", "baseRate" => 1.6, "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "cost" => 4.80, "weeklyCharge" => 1.2, "co2Charge" => 1.6,"exitID" =>'20220116199812x99rmj');

        $inTime = $testObject['inTime']; ##1643583100
        $outTime = $testObject['outTime']; ##1643583500
        $duration = ($outTime - $inTime)/3600; ##should equal 0.1111111111111

        ##calculate base cost
        $baseCost = round($testObject['baseRate'] * $duration,2); ##1.6 x 0.111111111111 = 0.18
        ##calculate weekly bias cost
        $weeklyChargeCost = round(($baseCost* $testObject['weeklyCharge']) - $baseCost,2); #0.18 * 1.2 - 0.18 = 0.04
        $co2ChargeCost = round(($baseCost * $testObject['co2Charge']) - $baseCost,2); #0.18 * 1.6 - 0.18 = 0.11

        $this->assertNotSame($testObject['baseRate'], 1.5 );
        $this->assertNotSame($testObject['weeklyCharge'], 1.0 );
        $this->assertNotSame($testObject['co2Charge'], 1.1 );
        $this->assertNotSame($weeklyChargeCost, 0.02);
        $this->assertNotSame($co2ChargeCost, 0.9);
        $this->assertNotSame($baseCost, 0.11);
    }
}