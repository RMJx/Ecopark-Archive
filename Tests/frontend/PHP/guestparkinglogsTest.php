<?php

class guestparkinglogsTest extends \PHPUnit\Framework\TestCase
{

    public function test_guestlocationlogs3()
    {   
        
        #simulates SQL record being pulled from Database via mySQLi "$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $username . "&function=guestparkinglogs&API-KEY=" . $APIKey;"
        $testObject = array(
        array("parkingID" => "202201236185732x99rmj", "location" => "Bangor",  "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "exitID" =>'20220116199812x99rmj'),
        array("parkingID" => "202201236185736x99rmj", "location" => "Bangor",  "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "exitID" =>'20220116199890x99rmj'),
        array("parkingID" => "202201236185756x99rmj", "location" => "Bangor",  "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "exitID" =>'20220116199878x99rmj') );
        ##if testObject is empty then there is no parking history.
        if (is_array($testObject)==0) { 
            
            $parkingHistory = False;
        } ##if testObject isn't empty then there is parking history.
        else 
        {

            $parkingHistory = True;
        }

        $this->assertSame($parkingHistory, True );
    }

    public function test_guestlocationlogsNone()
    {   
        #simulates SQL record being pulled from Database via mySQLi "$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $username . "&function=guestparkinglogs&API-KEY=" . $APIKey;"
        $testObject = "";
        ##if testObject is empty then there is no parking history.
        if (is_array($testObject)==0) { 
            
            $parkingHistory = False;
        } ##if testObject isn't empty then there is parking history.
        else{
        
            $parkingHistory = True;
        }
        $this->assertSame($parkingHistory, False );
    }

    public function test_dataformattingCorrect()
    {   
        #simulates SQL record being pulled from Database via mySQLi "$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $username . "&function=guestparkinglogs&API-KEY=" . $APIKey;"
        $testObject = array(
            array("parkingID" => "202201236185732x99rmj", "location" => "Bangor",  "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "exitID" =>'20220116199812x99rmj'),
            array("parkingID" => "202201236185736x99rmj", "location" => "Bangor",  "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "exitID" =>'20220116199890x99rmj'),
            array("parkingID" => "202201236185756x99rmj", "location" => "Bangor",  "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "exitID" =>'20220116199878x99rmj') );

            #test that the object can be iterated through and date changed accordingly. 202201236185756x99rmj --> 
            for ($x = 0; $x <= sizeof($testObject)-1; $x++) {

                $testObject[$x]['inTime'] = date("F j, Y, g:i a", $testObject[$x]['inTime']);
                }
        
        ##check that the third parking log entry's inTime of 202201236185756x99rmj is converted to January 30, 2022, 10:51 pm
        $this->assertSame($testObject[2]['inTime'], "January 30, 2022, 10:51 pm" );
    }

    public function test_dataformattingIncorrect()
    {   
        #simulates SQL record being pulled from Database via mySQLi "$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $username . "&function=guestparkinglogs&API-KEY=" . $APIKey;"
        $testObject = array(
            array("parkingID" => "202201236185732x99rmj", "location" => "Bangor",  "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "exitID" =>'20220116199812x99rmj'),
            array("parkingID" => "202201236185736x99rmj", "location" => "Bangor",  "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "exitID" =>'20220116199890x99rmj'),
            array("parkingID" => "202201236185756x99rmj", "location" => "Bangor",  "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "exitID" =>'20220116199878x99rmj') );

            #test that the object can be iterated through and date changed accordingly. 202201236185756x99rmj --> 
            for ($x = 0; $x <= sizeof($testObject)-1; $x++) {

                $testObject[$x]['inTime'] = date("F j, Y, g:i a", $testObject[$x]['inTime']);
                }
        
        ##check that the third parking log entry's inTime of 202201236185756x99rmj is converted to January 30, 2022, 10:51 pm
        $this->assertNotSame($testObject[2]['inTime'], "January 29, 2022, 10:51 pm" );
    }

    public function test_regformattingCorrect()
    {   
        #simulates SQL record being pulled from Database via mySQLi "$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $username . "&function=guestparkinglogs&API-KEY=" . $APIKey;"
        $testObject = array(
            array("parkingID" => "202201236185732x99rmj", "location" => "Bangor",  "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "exitID" =>'20220116199812x99rmj'),
            array("parkingID" => "202201236185736x99rmj", "location" => "Bangor",  "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "exitID" =>'20220116199890x99rmj'),
            array("parkingID" => "202201236185756x99rmj", "location" => "Bangor",  "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "exitID" =>'20220116199878x99rmj') );

            #test that the object can be iterated through and date changed accordingly. 202201236185756x99rmj --> 
            for ($x = 0; $x <= sizeof($testObject)-1; $x++) {

                $reg = strtoupper($testObject[$x]['id']);
                }
        
        ##check that the third parking log entry's inTime of 202201236185756x99rmj is converted to January 30, 2022, 10:51 pm
        $this->assertSame($reg, "X99RMJ" );
    }

    public function test_regformattingIncorrect()
    {   
        #simulates SQL record being pulled from Database via mySQLi "$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $username . "&function=guestparkinglogs&API-KEY=" . $APIKey;"
        $testObject = array(
            array("parkingID" => "202201236185732x99rmj", "location" => "Bangor",  "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "exitID" =>'20220116199812x99rmj'),
            array("parkingID" => "202201236185736x99rmj", "location" => "Bangor",  "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "exitID" =>'20220116199890x99rmj'),
            array("parkingID" => "202201236185756x99rmj", "location" => "Bangor",  "inTime" => 1643583100, "outTime" => 1643583500, "userName" => 'ryan', "id" =>"x99rmj", "exitID" =>'20220116199878x99rmj') );

            #test that the object can be iterated through and date changed accordingly. 202201236185756x99rmj --> 
            for ($x = 0; $x <= sizeof($testObject)-1; $x++) {

                $reg = strtoupper($testObject[$x]['id']);
                }
        
        ##check that the third parking log entry's inTime of 202201236185756x99rmj is converted to January 30, 2022, 10:51 pm
        $this->assertNotSame($reg, "x99rmj" );
    }
}
