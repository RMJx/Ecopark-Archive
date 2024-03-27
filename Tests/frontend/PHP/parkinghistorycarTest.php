<?php

class guestparkinglogsTest extends \PHPUnit\Framework\TestCase
{
    ##test the PHP function for printing the last 30 days for graph usage are correct.
    public function test_printlast30daysCorrect()
    {   
        $rows3 = array();
        for ($x = 0; $x <= 30; $x++) {
            $rows3[$x] = '{date:"' . date('d/m/Y', strtotime('today - ' . $x . 'days')) . '"},';
        }     

        ##ON DATE OF TESTING THIS IT WAS 07/03/2022 THEREFORE THE DATES WILL NEED CHANGING IF TESTED IN FUTURE.
        $this->assertSame($rows3[4], '{date:"03/03/2022"},'  );
        $this->assertSame($rows3[5], '{date:"02/03/2022"},'  );
        $this->assertSame($rows3[3], '{date:"04/03/2022"},'  );
    }

    
    public function test_printlast30daysIncorrect()
    {   
        $rows3 = array();
        for ($x = 0; $x <= 30; $x++) {
            $rows3[$x] = '{date:"' . date('d/m/Y', strtotime('today - ' . $x . 'days')) . '"},';
        }     

        ##ON DATE OF TESTING THIS IT WAS 07/03/2022 THEREFORE THE DATES WILL NEED CHANGING IF TESTED IN FUTURE.
        $this->assertNotSame($rows3[4], '{date:"06/03/2022"},'  );
        $this->assertNotSame($rows3[5], '{date:"01/03/2022"},'  );
        $this->assertNotSame($rows3[3], '{date:"02/03/2022"},'  );
    }


}

