<?php

class adminapiTest extends \PHPUnit\Framework\TestCase
{
    public function test_APIKeyVerificationCorrect()
    {   
    ##simulate data for user and their APIKey from db
    $APIKeydb = array("userName" => "rj", "APIKey"=>"8yyhYhHYYY887&6291221");
    
    ##simulate data for APIKey passed in API Request
    $APIKey = "8yyhYhHYYY887&6291221";


    if($APIKeydb['APIKey'] == $APIKey){
        $verified = True;
    }
    else {$verified = False;}

    
    $this->assertSame($verified, True);
}

public function test_APIKeyVerificationIncorrect()
    {   
    ##simulate data for user and their APIKey from db
    $APIKeydb = array("userName" => "rj", "APIKey"=>"8yyhYhHYYY887&6291221");
    
    ##simulate data for APIKey passed in API Request
    $APIKey = "8yyhYhHYYY887221";


    if($APIKeydb['APIKey'] == $APIKey){
        $verified = True;
    }
    else {$verified = False;}

    
    $this->assertSame($verified, False);
}



    public function test_chargecalculationCorrect()
    {   
    #simulate object of rate from API
    $testObject = array("rate" => 2.1);
    $hourlyRate = $testObject['rate'];
    
    ##simulate data for rates for co2Charges from API
    $co2rates = array(array("charge" => 0.95, "tierStart" => 0, "tierEnd"=>15, "tier"=>0),array("charge" => 1.1, "tierStart" => 0, "tierEnd"=>15, "tier"=>1), array("charge" => 1.2, "tierStart" => 0, "tierEnd"=>15, "tier"=>2), array("charge" => 0.95, "tierStart" => 0, "tierEnd"=>15, "tier"=>3));
    
    #test case for CO2 Class
    $co2Class = 2;
    
    #test case for times in/out Class
    $timeOut = 1646661800;
    $timeIn = 1646660000;
    
    #test case for hourlyRate
    $hourlyRate = 1.5;

    #calculate flat rate
    $Charge = round((($timeOut - $timeIn)/3600 * $hourlyRate),2); #0.5 * 1.5 = 0.75
  
    #calculate additional co2 charge if necessary
    $co2ChargeExtra = 0;
    for($i=0; $i<sizeof($co2rates)-1; $i++) {
      if($co2Class == $co2rates[$i]['tier']) {
         $co2RateCharge = $co2rates[$i]['charge'];
         $co2ChargeExtra = round((($Charge* $co2RateCharge) - $Charge),2); #0.75 *1.2 -0.75 = 0.15
        }
    }   
    
    $this->assertSame(0.15, $co2ChargeExtra);
    $this->assertSame(0.75, $Charge);
    $this->assertSame($co2RateCharge, 1.2);
}

public function test_chargecalculationIncorrect()
{   
#simulate object of rate from API
$testObject = array("rate" => 2.1);
$hourlyRate = $testObject['rate'];

##simulate data for rates for co2Charges from API
$co2rates = array(array("charge" => 0.95, "tierStart" => 0, "tierEnd"=>15, "tier"=>0),array("charge" => 1.1, "tierStart" => 0, "tierEnd"=>15, "tier"=>1), array("charge" => 1.2, "tierStart" => 0, "tierEnd"=>15, "tier"=>2), array("charge" => 0.95, "tierStart" => 0, "tierEnd"=>15, "tier"=>3));

#test case for CO2 Class
$co2Class = 2;

#test case for times in/out Class
$timeOut = 1646661800;
$timeIn = 1646660000;

#test case for hourlyRate
$hourlyRate = 1.5;

#calculate flat rate
$Charge = round((($timeOut - $timeIn)/3600 * $hourlyRate),2); #0.5 * 1.5 = 0.75

#calculate additional co2 charge if necessary
$co2ChargeExtra = 0;
for($i=0; $i<sizeof($co2rates)-1; $i++) {
  if($co2Class == $co2rates[$i]['tier']) {
     $co2RateCharge = $co2rates[$i]['charge'];
     $co2ChargeExtra = round((($Charge* $co2RateCharge) - $Charge),2); #0.75 *1.2 -0.75 = 0.15
    }
}   

$this->assertNotSame(0.25, $co2ChargeExtra);
$this->assertNotSame(0.5, $Charge);
$this->assertNotSame($co2RateCharge, 1.6);
}

public function test_weeklychargecalculationCorrect()
{   

##simulate value for weekly data
$weekTimes = 4;

##simulate data for rates for weeklycharges from API
$weeklyRates = array(array("charge" => 0.95, "tierStart" => 0, "tierEnd"=>2, "tier"=>0),array("charge" => 1.1, "tierStart" => 2, "tierEnd"=>5, "tier"=>1), array("charge" => 1.2, "tierStart" => 5, "tierEnd"=>8, "tier"=>2), array("charge" => 0.95, "tierStart" => 8, "tierEnd"=>11, "tier"=>3));


#simulate flat rate
$Charge = 0.75;
#simulate co2 rate
$co2ChargeExtra = 0.5;

    #calculate extra charges for extra weekly parking
    for($i=0; $i<=sizeof($weeklyRates)-1; $i++) {
        if($weekTimes > $weeklyRates[$i]['tierStart'] and $weekTimes <= $weeklyRates[$i]['tierEnd']) {
          $weeklyRateCharge = $weeklyRates[$i]['charge']; ##1.1
          $weeklyChargeExtra = round((($Charge* $weeklyRateCharge) - $Charge),2); ##0.75*1.1 - 0.75 = 0.08 (2 d.p)
        }
      }
      $Charge = $weeklyChargeExtra + $co2ChargeExtra + $Charge;
    $this->assertSame(0.08, $weeklyChargeExtra);
    $this->assertSame(1.33, $Charge);
    $this->assertSame(1.1, $weeklyRateCharge);
}

public function test_weeklychargecalculationIncorrect()
{   

##simulate value for weekly data
$weekTimes = 4;

##simulate data for rates for weeklycharges from API
$weeklyRates = array(array("charge" => 0.95, "tierStart" => 0, "tierEnd"=>2, "tier"=>0),array("charge" => 1.1, "tierStart" => 2, "tierEnd"=>5, "tier"=>1), array("charge" => 1.2, "tierStart" => 5, "tierEnd"=>8, "tier"=>2), array("charge" => 0.95, "tierStart" => 8, "tierEnd"=>11, "tier"=>3));


#simulate flat rate
$Charge = 0.75;
#simulate co2 rate
$co2ChargeExtra = 0.5;

    #calculate extra charges for extra weekly parking
    for($i=0; $i<=sizeof($weeklyRates)-1; $i++) {
        if($weekTimes > $weeklyRates[$i]['tierStart'] and $weekTimes <= $weeklyRates[$i]['tierEnd']) {
          $weeklyRateCharge = $weeklyRates[$i]['charge']; ##1.1
          $weeklyChargeExtra = round((($Charge* $weeklyRateCharge) - $Charge),2); ##0.75*1.1 - 0.75 = 0.08 (2 d.p)
        }
      }
      $Charge = $weeklyChargeExtra + $co2ChargeExtra + $Charge;
    $this->assertNotSame(0.05, $weeklyChargeExtra);
    $this->assertNotSame(1.12, $Charge);
    $this->assertNotSame(1.0, $weeklyRateCharge);
}

public function test_calculatesurplusCorrect()
{   
    #simulate object of rate from API
    $testObject = array("rate" => 2.1);
    $hourlyRate = $testObject['rate'];

    #simulate the co2Class result
    $co2Class = 1;

    #simulate the hours extra result
    $hoursExtra = 3;

    #simulate the hours extra result
    $hourlyRate = 1.2;

    #calculate flat rate
    $ChargePrior = ($hoursExtra) * $hourlyRate;

    if ($co2Class == "1") {
        $Charge = $ChargePrior*1.1;
    }
    elseif ($co2Class == "2"){
        $Charge = $ChargePrior*1.2;
    }
    elseif ($co2Class == "3") {
        $Charge = $ChargePrior*1.3;
    }
    elseif ($co2Class == "0") {
        $Charge = $ChargePrior*0.9;
    }
    elseif ($co2Class == "4") {
        $Charge = $ChargePrior*1.4;
    }

    ##co2 charge we expect to be 1.1 therefore 3.6 * 1.1 = 3.96

    $this->assertSame(3.96, $Charge);
    $this->assertSame(3.6, $ChargePrior);
}

public function test_calculatesurplusIncorrect()
{   
    #simulate object of rate from API
    $testObject = array("rate" => 2.1);
    $hourlyRate = $testObject['rate'];

    #simulate the co2Class result
    $co2Class = 1;

    #simulate the hours extra result
    $hoursExtra = 3;

    #simulate the hours extra result
    $hourlyRate = 1.2;

    #calculate flat rate
    $ChargePrior = ($hoursExtra) * $hourlyRate;

    if ($co2Class == "1") {
        $Charge = $ChargePrior*1.1;
    }
    elseif ($co2Class == "2"){
        $Charge = $ChargePrior*1.2;
    }
    elseif ($co2Class == "3") {
        $Charge = $ChargePrior*1.3;
    }
    elseif ($co2Class == "0") {
        $Charge = $ChargePrior*0.9;
    }
    elseif ($co2Class == "4") {
        $Charge = $ChargePrior*1.4;
    }

    ##co2 charge we expect to be 1.1 therefore 3.6 * 1.1 = 3.96

    $this->assertNotSame(3.71, $Charge);
    $this->assertNotSame(3.1, $ChargePrior);
}





}
  