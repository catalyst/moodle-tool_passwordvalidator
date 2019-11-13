<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *  Password Policy Checker Unit Tests
 *
 * @package    tool_passwordvalidator
 * @copyright  Peter Burnett <peterburnett@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once(__DIR__.'/../locallib.php');
require_once(__DIR__.'../../../../../user/lib.php');
class tool_passwordvalidator_password_testcase extends advanced_testcase {

    // ===========================COMPLEXITY LENGTH TESTS============================
    public static function complexity_length_provider() {
        return [
            // Data array [Password, passes validation]
            'goodresponse' => ['', false],
            'onlylowerstooshort' => ['abcdefg', false],
            'onlylowerslong' => ['aabbccddeeffgg', true],
            'onlyupperstooshort' => ['ABCDEFG', false],
            'onlyupperslong' => ['AABBCCDDEEFFGG', true],
            'specialcharsonlytooshort' => ['!@!@#$', false],
            'specialcharsonlylong' => ['!@#$%^&*()!@#$%^&*()', true],
            'upperlettersandnumberstooshort' => ['TESTPASS1', false],
            'upperlettersandnumberslong' => ['TESTPASS11111', true],
            'lowerlettersandnumberstooshort' => ['testpass1', false],
            'lowerlettersandnumberslong' => ['testpass11111', true],
            'lowerlettersandspecialtooshort' => ['tester!@#', false],
            'lowerlettersandspeciallong' => ['testerpass!@#', true],
            'upperlettersandspecialtooshort' => ['TESTER!@#', false],
            'upperlettersandspeciallong' => ['TESTERPASS@#@%', true],
            'onlynumbersandspeciallong' => ['1234567!@#$%%^', true],
            'onlynumbersandspecialshort' => ['1234567!@#', false]
        ];
    }

    /**
     * @dataProvider complexity_length_provider
     */
    public function test_complexity_length($password, $good) {
        $goodresponse = '';

        // test the data provider strings against the expected response
        $this->assertEquals($good, tool_passwordvalidator_complexity_checker($password, true) == $goodresponse );
    }

    // ===========================COMPLEXITY CHARS TESTS============================
    public static function complexity_chars_provider() {
        return [
            // Data array [Password, passes validation]
            'goodresponse' => ['', false],
            'onlylowers' => ['abcdefg', true],
            'onlyuppers' => ['ABCDEFG', true],
            'onlynumbers' => ['1234567', false],
            'onlyspecials' => ['!@#$%^&', false],
            'lowersanduppers' => ['abcDEFG', true],
            'lowersandnumbers' => ['abcd123', true],
            'lowerandspecials' => ['abcd!@#', true],
            'uppersandnumbers' => ['ABCD123', true],
            'uppersandspecials' => ['ABCD!@#', true],
            'specialsandnumbers' => ['123$%^&', false]
        ];
    }

    /**
     * @dataProvider complexity_chars_provider
     */
    public function test_complexity_chars($password, $good) {
        $goodresponse = '';

        // test the data provider strings against the expected response
        $this->assertEquals($good, tool_passwordvalidator_complexity_checker($password, false) == $goodresponse );
    }

    // ===========================DICTIONARY CHECKING TESTS============================
    public static function dictionary_checking_provider() {
        return [
            // Data array [Password, passes validation]
            'goodresponse' => ['', true],
            'onewordnumbers' => ['123magazine123', false],
            'onewordchars' => ['!@#magazine!@#', false],
            'onewordnumberandchar' => ['123magazine!@#', false],
            'onewordnumberspace' => ['123 magazine', false],
            'onewordcharspace' => ['!@# magazine', false],
            'onewordcharandnumberspace' => ['!@# magazine 123', false],
            'onewordnondictionary' => ['skamandlebop', true],
            'nondictnumbers' => ['123skamandlebop123', true],
            'nondictchars' => ['!@#skamandlebop!@#', true],
            'twodictwords' => ['magazineindividuals', true],
            'twowordsnumber' => ['magazine123individuals', true],
            'twowordschars' => ['magazine!@#individuals', true],
            'twowordsspaces' => ['magazine individuals', true],
            'twowordsspacesnumbers' => ['magazine 123individuals', true],
            'twowordsspaceschars' => ['magazine !@#individuals', true],
            'allnums' => ['12345678', true],
            'allchars' => ['!@#$%^&*', true]
        ];
    }

    /**
     * @dataProvider dictionary_checking_provider
     */
    public function test_dictionary_checking($password, $good) {
        $this->resetAfterTest(true);
        set_config('dictionary_check_file', 'google-10000-english.txt', 'tool_passwordvalidator');
        $goodresponse = '';

         // test the data provider strings against the expected response
         $this->assertEquals($good, tool_passwordvalidator_dictionary_checker($password) == $goodresponse );

    }

    // ===========================PERSONAL INFORMATION TESTS============================
    public function test_personal_information() {
        $this->resetAfterTest(true);
        // Generate user account to test against
        $user = $this->getDataGenerator()->create_user(array('username' => 'phpunit', 'firstname' => 'test',
                         'lastname' => 'user', 'city' => 'testcity'));
        $this->setUser($user);

        $goodresponse = '';
        $safestring = 'noinformationhere';
        $badfname = 'abcTestabc';
        $badlname = 'abcUserabc';
        $badusername = 'abcPHPUnitabc';
        $badcity = 'abctestcityabc';

        // Empty password, no data to be found
        $this->assertEquals($goodresponse, tool_passwordvalidator_personal_information($safestring, $user));

        // Safe strings
        $this->assertEquals($goodresponse, tool_passwordvalidator_personal_information($safestring, $user));

        // Bad strings
        $this->assertNotEquals($goodresponse, tool_passwordvalidator_personal_information($badfname, $user));
        $this->assertNotEquals($goodresponse, tool_passwordvalidator_personal_information($badlname, $user));
        $this->assertNotEquals($goodresponse, tool_passwordvalidator_personal_information($badcity, $user));
        $this->assertNotEquals($goodresponse, tool_passwordvalidator_personal_information($badusername, $user));

        // Extra Unit tests for malformed user account with empty strings
        $baduser = $this->getDataGenerator()->create_user(array('username' => 'baduser', 'firstname' => '',
                            'lastname' => '', 'city' => ''));
        $this->setUser($baduser);
        global $USER;

        // Verify logged in as the bad user
        $this->assertEquals('', $USER->firstname);

        $badusername2 = '123baduser123';
        $badfirstname = 'firstname';
        $badlastname = 'lastname';
        $badusercity = 'badcity';

        // Check for empty string data from user account
        $this->assertNotEquals($goodresponse, tool_passwordvalidator_personal_information($badusername2, $baduser));

        $this->assertEquals($goodresponse, tool_passwordvalidator_personal_information($badfirstname, $baduser));
        $this->assertEquals($goodresponse, tool_passwordvalidator_personal_information($badlastname, $baduser));
        $this->assertEquals($goodresponse, tool_passwordvalidator_personal_information($badusercity, $baduser));

        // Manually null user values
        $baduser->firstname = null;
        $baduser->lastname = null;
        $baduser->username = null;
        $baduser->city = null;

        // Verify account values are nulled.
        $this->assertEquals(null, $baduser->firstname);
        $this->assertEquals(null, $baduser->lastname);
        $this->assertEquals(null, $baduser->username);
        $this->assertEquals(null, $baduser->city);

        // If user account data is null, it should return a good response, as null shouldnt be found inside the password
        $this->assertEquals($goodresponse, tool_passwordvalidator_personal_information($badusername2, $baduser));
        $this->assertEquals($goodresponse, tool_passwordvalidator_personal_information($badfirstname, $baduser));
        $this->assertEquals($goodresponse, tool_passwordvalidator_personal_information($badlastname, $baduser));
        $this->assertEquals($goodresponse, tool_passwordvalidator_personal_information($badusercity, $baduser));

        // Create user with single char values
        $singleuser = $this->getDataGenerator()->create_user(array('username' => 'aa', 'firstname' => 'b',
                                'lastname' => 'c', 'city' => '  '));

        $singleusername = 'contains aa';
        $singlefirstname = 'contains b';
        $singlelastname = 'contains c';
        $singlecity = 'contains space';

        // strings with multiple chars should be checked and fail
        $this->assertNotEquals($goodresponse, tool_passwordvalidator_personal_information($singleusername, $singleuser));

        // string with single chars shouldnt be checked, and pass
        $this->assertEquals($goodresponse, tool_passwordvalidator_personal_information($singlefirstname, $singleuser));
        $this->assertEquals($goodresponse, tool_passwordvalidator_personal_information($singlelastname, $singleuser));
        $this->assertEquals($goodresponse, tool_passwordvalidator_personal_information($singlecity, $singleuser));
    }

    // ===========================SEQUENTIAL DIGITS TESTS============================
    public static function sequential_digits_provider() {
        return [
            // Data array [Password, passes validation]
            'goodresponse' => ['', true],
            'noseqdigits' => ['a1b2c3d4', true],
            'safeseqdigits' => ['a11b22c33', true],
            'maxseqdigits' => ['a11111b22222', true],
            'overseqdigits' => ['a111111b222222', false],
            'nodigits' => ['abcd!@#$', true],
            'nodigitsrepeatsafe' => ['aaabbb', true],
            'nodigitsrepeatmax' => ['aaaaabbbbb', true],
            'nodigitsrepeatover' => ['aaaaaabbbbbb', true]
        ];
    }

    /**
     * @dataProvider sequential_digits_provider
     */
    public function test_sequential_digits($password, $good) {
        $this->resetAfterTest(true);
        set_config('sequential_digits_input', 5, 'tool_passwordvalidator');
        $goodresponse = '';

         // test the data provider strings against the expected response
         $this->assertEquals($good, tool_passwordvalidator_sequential_digits($password) == $goodresponse );
    }

    // ===========================REPEATED CHARS TESTS============================
    public static function repeated_chars_provider() {
        return [
            'goodresponse' => ['', true],
            'norepeatchars' => ['a1b2c3d4', true],
            'saferepeatchars' => ['aa1bb2cc3', true],
            'maxrepeatchars' => ['aaaa1bbbb2cccc3', true],
            'overrepeatchars' => ['aaaaa1bbbbb2ccccc3', false],
            'noletterssafe' => ['1122', true],
            'nolettersmax' => ['11112222', true],
            'nolettersover' => ['1111122222', false]
        ];
    }

    /**
     * @dataProvider repeated_chars_provider
     */
    public function test_repeated_chars($password, $good) {
        $this->resetAfterTest(true);
        set_config('repeated_chars_input', 4, 'tool_passwordvalidator');
        $goodresponse = '';

        // test the data provider strings against the expected response
        $this->assertEquals($good, tool_passwordvalidator_repeated_chars($password) == $goodresponse);
    }

    // ===========================PHRASE BLACKLISTING TESTS============================
    public function test_phrase_blacklisting() {
        $this->resetAfterTest(true);
        set_config('phrase_blacklist_input', "badphrase\nphrasetwo\nphrase with space", 'tool_passwordvalidator');

        $goodresponse = '';
        $tooshort = 'abc123';
        $safephrase = 'nophraseisbad';
        $badphrase1 = 'badphrasehere';
        $badphrase2 = 'phrasetwohere';
        $badphrase3 = 'phrase with space here';

        // Safe Variables
        $this->assertEquals($goodresponse, tool_passwordvalidator_phrase_blacklist($goodresponse));
        $this->assertEquals($goodresponse, tool_passwordvalidator_phrase_blacklist($tooshort));
        $this->assertEquals($goodresponse, tool_passwordvalidator_phrase_blacklist($safephrase));

        // Contains bad phrases
        $this->assertNotEquals($goodresponse, tool_passwordvalidator_phrase_blacklist($badphrase1));
        $this->assertNotEquals($goodresponse, tool_passwordvalidator_phrase_blacklist($badphrase2));
        $this->assertNotEquals($goodresponse, tool_passwordvalidator_phrase_blacklist($badphrase3));
    }

    // ===========================LOCKOUT PERIOD TESTS============================
    public function test_lockout_period() {
        $this->resetAfterTest(true);
        global $CFG;
        global $DB;
        $CFG->passwordreuselimit = 3;
        $goodresponse = '';
        $testpassword = 'testpassword';

        // Set timelock to 1 second
        set_config('time_lockout_input', 2 , 'tool_passwordvalidator');

        // Create a user then 'fake add' a password to trigger the timelock
        $user = $this->getDataGenerator()->create_user(array('username' => 'phpunit', 'firstname' => 'test',
                         'lastname' => 'user', 'city' => 'testcity'));
        $this->setUser($user);
        user_add_password_history($user->id, 'passwordhistory1');

        // Now test that you are unable to change password
        $this->assertNotEquals($goodresponse, tool_passwordvalidator_lockout_period($testpassword, $user));

        // Wait 3 seconds then test again
        sleep(3);
        $this->assertEquals($goodresponse, tool_passwordvalidator_lockout_period($testpassword, $user));

        // Repeat with a slightly longer period
        set_config('time_lockout_input', 4 , 'tool_passwordvalidator');
        user_add_password_history($user->id, 'passwordhistory2');
        $this->assertNotEquals($goodresponse, tool_passwordvalidator_lockout_period($testpassword, $user));
        sleep(5);
        $this->assertEquals($goodresponse, tool_passwordvalidator_lockout_period($testpassword, $user));

        // Then set to 24hrs (86400 seconds) ensure it takes values that large
        set_config('time_lockout_input', 86400 , 'tool_passwordvalidator');
        user_add_password_history($user->id, 'passwordhistory2');
        $this->assertNotEquals($goodresponse, tool_passwordvalidator_lockout_period($testpassword, $user));
        sleep(2);
        $this->assertNotEquals($goodresponse, tool_passwordvalidator_lockout_period($testpassword, $user));
    }

    // ===========================PASSWORD BLACKLIST TESTS============================
    public function test_password_blacklist() {
        // Due to constant data breaches etc, there is a chance one day these tests
        // may fail, as the passwords chosen as the safe test version may actually
        // become leaked

        $goodresponse = '';
        $badpassword = 'password';
        $safepassword = 'hopefully this password remains safe $&!@#*(%(&!@*(%';

        // Safe variables
        $this->assertEquals($goodresponse, tool_passwordvalidator_password_blacklist($goodresponse));
        $this->assertEquals($goodresponse, tool_passwordvalidator_password_blacklist($safepassword));

        // verified leaked password
        $this->assertNotEquals($goodresponse, tool_passwordvalidator_password_blacklist($badpassword));
    }

    // ===========================PASSWORD VALIDATE TESTS============================
    public function test_password_validate() {
        $this->resetAfterTest(true);
        $goodresponse = '';
        $badpassword = 'password';
        $admindatapassword = 'admin password check';

        // set account to admin account
        $this->setAdminUser();
        global $USER;

        // Test that check for user data fails
        $this->assertNotEquals($goodresponse, tool_passwordvalidator_password_validate($admindatapassword, $USER));

        // Create a new user, with different information
        $newuser = $this->getDataGenerator()->create_user(array('username' => 'phpunit', 'firstname' => 'test',
        'lastname' => 'user', 'city' => 'testcity'));

        // Check that check for user data passes with a different user
        $this->assertEquals($goodresponse, tool_passwordvalidator_password_validate($admindatapassword, $newuser));
    }

    // This test ensures that the end to end flow of check_password_policy is working
    public function test_password_change_api() {
        $this->resetAfterTest(true);
        global $CFG;

        // Require strong config to test with
        require(__DIR__.'/../config_policies/NIST_ISM_2019.php');
        $CFG->passwordpolicy = true;
        $CFG->minpasswordlength = 0;
        $CFG->minpassworddigits = 0;
        $CFG->minpasswordlower = 0;
        $CFG->minpasswordupper = 0;
        $CFG->minpasswordnonalphanum = 0;
        $CFG->maxconsecutiveidentchars = 0;

        // Setup user to test against
        $user = $this->getDataGenerator()->create_user(array('username' => 'phpunit', 'firstname' => 'test',
                         'lastname' => 'user', 'city' => 'testcity'));
        $this->setUser($user);

        $badpassword = 'testpassword';
        $goodpassword = 'tree grass bush shrub';

        // Test good password
        $errors = '';
        $result = check_password_policy($goodpassword, $errors);

        $this->assertTrue($result);
        $this->assertEmpty($errors);

        // Test bad password
        $result = check_password_policy($badpassword, $errors);
        $this->assertFalse($result);
        $this->assertNotEmpty($errors);
    }
}

