<?php

defined('MOODLE_INTERNAL') || die();
require_once __DIR__.'/../lib.php';
class tool_password_password_testcase extends advanced_testcase {
    function test_complexity_length(){
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
        
        //Only numbers and special characters, length req only
        $this->assertEquals($goodresponse, complexity_checker($onlynumbersandspeciallong, true));
        $this->assertEquals($goodresponse, complexity_checker($specialcharsonlylong, true));
        $this->assertNotEquals($goodresponse, complexity_checker($onlynumbersandspecialshort, true));
        $this->assertNotEquals($goodresponse, complexity_checker($specialcharsonlytooshort, true));
        
        //Assert empty response for success
        $this->assertEquals($goodresponse, complexity_checker($onlylowerslong, true));
        $this->assertEquals($goodresponse, complexity_checker($onlyupperslong, true));
        $this->assertEquals($goodresponse, complexity_checker($upperlettersandnumberslong, true));
        $this->assertEquals($goodresponse, complexity_checker($lowerlettersandnumberslong, true));
        $this->assertEquals($goodresponse, complexity_checker($lowerlettersandspeciallong, true));
        $this->assertEquals($goodresponse, complexity_checker($upperlettersandspeciallong, true));
    }

    function test_complexity_chars(){
        $goodresponse = '';
        $onlylowers = 'abcdefg';
        $onlyuppers = 'ABCDEFG';
        $onlynumbers = '1234567';
        $onlyspecials = '!@#$%^&';
        $lowersanduppers = 'abcDEFG';
        $lowersandnumbers = 'abcd123';
        $lowerandspecials = 'abcd!@#';
        $uppersandnumbers = 'ABCD123';
        $uppersandspecials = 'ABCD!@#';
        $specialsandnumbers = '123$%^&';

        //All with letters should equal
        $this->assertEquals($goodresponse, complexity_checker($onlylowers, false));
        $this->assertEquals($goodresponse, complexity_checker($onlyuppers, false));
        $this->assertEquals($goodresponse, complexity_checker($lowersanduppers, false));
        $this->assertEquals($goodresponse, complexity_checker($lowersandnumbers, false));
        $this->assertEquals($goodresponse, complexity_checker($lowerandspecials, false));
        $this->assertEquals($goodresponse, complexity_checker($uppersandnumbers, false));
        $this->assertEquals($goodresponse, complexity_checker($uppersandspecials, false));

        //All with no letters should not equal
        $this->assertNotEquals($goodresponse, complexity_checker($onlynumbers, false));
        $this->assertNotEquals($goodresponse, complexity_checker($onlyspecials, false));
        $this->assertNotEquals($goodresponse, complexity_checker($specialsandnumbers, false));
    }

    function test_personal_information() {
        $this->resetAfterTest(true);
        //generate user account to test against
        $user = $this->getDataGenerator()->create_user(array('username' => 'phpunit', 'firstname' => 'test',
                         'lastname' => 'user', 'city' => 'testcity'));
        $this->setUser($user);

        $goodresponse = '';
        $safestring = 'noinformationhere';
        $badfname = 'abcTestabc';
        $badlname = 'abcUserabc';
        $badusername = 'abcPHPUnitabc';
        $badcity = 'abctestcityabc';

        //Empty password, no data to be found
        $this->assertEquals($goodresponse, personal_information($safestring));
        
        //safestrings
        $this->assertEquals($goodresponse, personal_information($safestring));
        
        //Bad strings
        $this->assertNotEquals($goodresponse, personal_information($badfname));
        $this->assertNotEquals($goodresponse, personal_information($badlname));
        $this->assertNotEquals($goodresponse, personal_information($badcity));
        $this->assertNotEquals($goodresponse, personal_information($badusername));
    }

    function test_sequential_digits() {
        $this->resetAfterTest(true);
        set_config('sequential_digits_input', 5,'tool_password');

        $goodresponse = '';
        $noseqdigits = 'a1b2c3d4';
        $safeseqdigits = 'a11b22c33';
        $maxseqdigits = 'a11111b22222';
        $overseqdigits = 'a111111b222222';
        $nodigits = 'abcd!@#$';

        //Safe variables
        $this->assertEquals($goodresponse, sequential_digits($goodresponse));
        $this->assertEquals($goodresponse, sequential_digits($noseqdigits));
        $this->assertEquals($goodresponse, sequential_digits($safeseqdigits));
        $this->assertEquals($goodresponse, sequential_digits($maxseqdigits));
        $this->assertEquals($goodresponse, sequential_digits($nodigits));

        //Over the limit
        $this->assertNotEquals($goodresponse, sequential_digits($overseqdigits));
    }
}

