<?php

defined('MOODLE_INTERNAL') || die();
require_once __DIR__.'/../lib.php';
class tool_password_password_testcase extends advanced_testcase {
    function test_complexity(){
        $goodresponse = '';
        $onlylowerstooshort = 'abcdefg';
        $onlylowerslong = 'aabbccddeeffgg';
        $onlyupperstooshort = 'ABCDEFG';
        $onlyupperslong = 'AABBCCDDEEFFGG';
        $specialcharsonlytooshort = '!@!@#$';
        $specialcharsonlylong = '!@#$%^&*()!@#$%^&*()';
        $upperlettersandnumberstooshort = 'TESTPASS1';
        $upperlettersandnumberslong = 'TESTPASS11111';
        $lowerlettersandnumberstooshort = 'testpass1';
        $lowerlettersandnumberslong = 'testpass11111';
        $lowerlettersandspecialtooshort = 'tester!@#';
        $lowerlettersandspeciallong = 'testerpass!@#';
        $upperlettersandspecialtooshort = 'TESTER!@#';
        $upperlettersandspeciallong = 'TESTERPASS@#@%';
        $onlynumbersandspeciallong = '1234567!@#$%%^';
        $onlynumbersandspecialshort = '1234567!@#';

        //Assert error message if provided empty pw
        $this->assertNotEquals($goodresponse, complexity_checker($goodresponse, true));

        //Assert error message for too short passwords
        $this->assertNotEquals($goodresponse, complexity_checker($onlylowerstooshort, true));
        $this->assertNotEquals($goodresponse, complexity_checker($onlyupperstooshort, true));
        $this->assertNotEquals($goodresponse, complexity_checker($upperlettersandnumberstooshort, true));
        $this->assertNotEquals($goodresponse, complexity_checker($lowerlettersandnumberstooshort, true));
        $this->assertNotEquals($goodresponse, complexity_checker($upperlettersandspecialtooshort, true));
        $this->assertNotEquals($goodresponse, complexity_checker($lowerlettersandspecialtooshort, true));
        
        //Only numbers and special characters, requires letters
        $this->assertNotEquals($goodresponse, complexity_checker($onlynumbersandspeciallong, true)); //FAILED
        $this->assertNotEquals($goodresponse, complexity_checker($onlynumbersandspecialshort, true));
        $this->assertNotEquals($goodresponse, complexity_checker($specialcharsonlytooshort, true));
        $this->assertNotEquals($goodresponse, complexity_checker($specialcharsonlylong, true));

        //Assert empty response for success
        $this->assertEquals($goodresponse, complexity_checker($onlylowerslong, true));
        $this->assertEquals($goodresponse, complexity_checker($onlyupperslong, true));
        $this->assertEquals($goodresponse, complexity_checker($upperlettersandnumberslong, true));
        $this->assertEquals($goodresponse, complexity_checker($lowerlettersandnumberslong, true));
        $this->assertEquals($goodresponse, complexity_checker($lowerlettersandspeciallong, true));
        $this->assertEquals($goodresponse, complexity_checker($upperlettersandspeciallong, true));
    }


}