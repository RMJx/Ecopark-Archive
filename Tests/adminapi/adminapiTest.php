<?php

class adminapiTest extends \PHPUnit\Framework\TestCase
{

    public function test_getCars()
    {   
        #simulates SQL record being pulled from Database via mySQLi
        $testObject = array(array("id" => "x99rmj", "make" => "AUDI", "colour" => "BLACK", "isParked" => 0, "timeParked" => 0, "totalDue" => 0.00, "userName" =>"rj", "co2Class" =>3),array("id" => "p12uef", "make" => "AUDI", "colour" => "BLACK", "isParked" => 1, "timeParked" => 0, "totalDue" => 0.00, "userName" =>"Ryan", "co2Class" =>2) );

        #create an array to store the ids iterated through
        $ids = array();
        #simulate the iteration through all results
        for ($x = 0; $x <= sizeof($testObject)-1; $x++) {
        $response['id'] = $testObject[$x]['id'];
        $response['userName']  = $testObject[$x]['userName'];
        $response['isParked'] = $testObject[$x]['isParked'];
        $response['make'] = $testObject[$x]['make'];
        $response['colour'] = $testObject[$x]['colour'];
        $response['co2Class'] = $testObject[$x]['co2Class'];
        $response['timeParked'] = $testObject[$x]['timeParked'];
        $response['totalDue'] = $testObject[$x]['totalDue'];
        ##add the id of each record to the array above so can we fetch it for checking its true
        array_push($ids, $response['id']);
        }

        $this->assertSame($ids[1], 'p12uef' );
    }

    public function test_getlocationLogs()
    {   
        #simulates SQL record being pulled from Database via mySQLi
        $testObject = array(array("parkingID" => "20220116185736x99rmj", "location" => "Belfast Central", "baseRate" => 1.6, "inTime" => 1643580000, "outTime" => 1643587200, "userName" => 'rj', "id" =>"x99rmj", "cost" =>4.80, "weeklyCharge" =>1.2, "co2Charge" =>1.3, "exitID" =>'20220116185817x99rmj'),
        array("parkingID" => "202201236185736x99rmj", "location" => "Bangor", "baseRate" => 1.4, "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"p12uef", "cost" =>9.80, "weeklyCharge" =>1.1, "co2Charge" =>1.4, "exitID" =>'20220116199817x99rmj'));

        #create an array to store the ids iterated through
        $ids = array();
        #simulate the iteration through all results
        for ($x = 0; $x <= sizeof($testObject)-1; $x++) {
        $response['parkingID'] = $testObject[$x]['parkingID'];
        $response['userName']  = $testObject[$x]['userName'];
        $response['baseRate'] = $testObject[$x]['baseRate'];
        $response['inTime'] = $testObject[$x]['inTime'];
        $response['outTime'] = $testObject[$x]['outTime'];
        $response['userName'] = $testObject[$x]['userName'];
        $response['id'] = $testObject[$x]['id'];
        $response['cost'] = $testObject[$x]['cost'];
        $response['weeklyCharge'] = $testObject[$x]['weeklyCharge'];
        $response['co2Charge'] = $testObject[$x]['co2Charge'];
        $response['exitID'] = $testObject[$x]['exitID'];
        ##add the id of each record to the array above so can we fetch it for checking its true
        array_push($ids, $response['parkingID']);
        }

        $this->assertSame($ids[1], '202201236185736x99rmj' );
    }
}
