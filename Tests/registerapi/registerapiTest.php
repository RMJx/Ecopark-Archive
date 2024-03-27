<?php

class adminapiTest extends \PHPUnit\Framework\TestCase
{
    public function test_generaterandomAPIKeyCorrect()
    {   

        $length = 50;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

    
    $this->assertSame(strlen($randomString), 50);
}

public function test_generaterandomAPIKeyIncorrect()
{   

    $length = 50;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }


    $this->assertNotSame(strlen($randomString), 75);
}

public function test_passwordhashingCorrect()
{   
    $pass = "Test123";
    $hashresult=hash('sha256', $pass); #d9b5f58f0b38198293971865a14074f59eba3e82595becbe86ae51f1d9f1f65e



    $this->assertNotSame($pass, $hashresult);
    $this->assertSame("d9b5f58f0b38198293971865a14074f59eba3e82595becbe86ae51f1d9f1f65e", $hashresult);
}

public function test_passwordhashingIncorrect()
{   
    $pass = "Test123";
    $hashresult=hash('sha256', $pass); #d9b5f58f0b38198293971865a14074f59eba3e82595becbe86ae51f1d9f1f65e

    $this->assertNotSame("ecd71870d1963316a97e3ac3408c9835ad8cf0f3c1bc703527c30265534f75ae", $hashresult);
}



}
  