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
require_once(__DIR__.'/../lib.php');
require_once(__DIR__.'../../../../../user/lib.php');
class tool_passwordvalidator_password_testcase extends advanced_testcase {
    public function test_complexity_length() {
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

        // Assert error message if provided empty pw
        $this->assertNotEquals($goodresponse, complexity_checker($goodresponse, true));

        // Assert error message for too short passwords
        $this->assertNotEquals($goodresponse, complexity_checker($onlylowerstooshort, true));
        $this->assertNotEquals($goodresponse, complexity_checker($onlyupperstooshort, true));
        $this->assertNotEquals($goodresponse, complexity_checker($upperlettersandnumberstooshort, true));
        $this->assertNotEquals($goodresponse, complexity_checker($lowerlettersandnumberstooshort, true));
        $this->assertNotEquals($goodresponse, complexity_checker($upperlettersandspecialtooshort, true));
        $this->assertNotEquals($goodresponse, complexity_checker($lowerlettersandspecialtooshort, true));

        // Only numbers and special characters, length req only
        $this->assertEquals($goodresponse, complexity_checker($onlynumbersandspeciallong, true));
        $this->assertEquals($goodresponse, complexity_checker($specialcharsonlylong, true));
        $this->assertNotEquals($goodresponse, complexity_checker($onlynumbersandspecialshort, true));
        $this->assertNotEquals($goodresponse, complexity_checker($specialcharsonlytooshort, true));

        // Assert empty response for success
        $this->assertEquals($goodresponse, complexity_checker($onlylowerslong, true));
        $this->assertEquals($goodresponse, complexity_checker($onlyupperslong, true));
        $this->assertEquals($goodresponse, complexity_checker($upperlettersandnumberslong, true));
        $this->assertEquals($goodresponse, complexity_checker($lowerlettersandnumberslong, true));
        $this->assertEquals($goodresponse, complexity_checker($lowerlettersandspeciallong, true));
        $this->assertEquals($goodresponse, complexity_checker($upperlettersandspeciallong, true));
    }

    public function test_complexity_chars() {
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

        // All with letters should equal
        $this->assertEquals($goodresponse, complexity_checker($onlylowers, false));
        $this->assertEquals($goodresponse, complexity_checker($onlyuppers, false));
        $this->assertEquals($goodresponse, complexity_checker($lowersanduppers, false));
        $this->assertEquals($goodresponse, complexity_checker($lowersandnumbers, false));
        $this->assertEquals($goodresponse, complexity_checker($lowerandspecials, false));
        $this->assertEquals($goodresponse, complexity_checker($uppersandnumbers, false));
        $this->assertEquals($goodresponse, complexity_checker($uppersandspecials, false));

        // All with no letters should not equal
        $this->assertNotEquals($goodresponse, complexity_checker($onlynumbers, false));
        $this->assertNotEquals($goodresponse, complexity_checker($onlyspecials, false));
        $this->assertNotEquals($goodresponse, complexity_checker($specialsandnumbers, false));
    }

    public function test_dictionary_checking() {
        $this->resetAfterTest(true);
        set_config('dictionary_check_file', 'google-10000-english.txt', 'tool_passwordvalidator');

        $goodresponse = '';
        $onewordnumbers = '123magazine123';
        $onewordchars = '!@#magazine!@#';
        $onewordnumberandchar = '123magazine!@#';
        $onewordnumberspace = '123 magazine';
        $onewordcharspace = '!@# magazine';
        $onewordcharandnumberspace = '!@# magazine 123';
        $onewordnondictionary = 'skamandlebop';
        $nondictnumbers = '123skamandlebop123';
        $nondictchars = '!@#skamandlebop!@#';
        $twodictwords = 'magazineindividuals';
        $twowordsnumber = 'magazine123individuals';
        $twowordschars = 'magazine!@#individuals';
        $twowordsspaces = 'magazine individuals';
        $twowordsspacesnumbers = 'magazine 123individuals';
        $twowordsspaceschars = 'magazine !@#individuals';
        $allnums = '12345678';
        $allchars = '!@#$%^&*';

        // Good strings, based on multiple dictionary words or none
        $this->assertEquals($goodresponse, dictionary_checker($goodresponse));
        $this->assertEquals($goodresponse, dictionary_checker($onewordnondictionary));
        $this->assertEquals($goodresponse, dictionary_checker($nondictnumbers));
        $this->assertEquals($goodresponse, dictionary_checker($nondictchars));
        $this->assertEquals($goodresponse, dictionary_checker($twodictwords));
        $this->assertEquals($goodresponse, dictionary_checker($twowordsnumber));
        $this->assertEquals($goodresponse, dictionary_checker($twowordschars));
        $this->assertEquals($goodresponse, dictionary_checker($twowordsspaces));
        $this->assertEquals($goodresponse, dictionary_checker($twowordsspacesnumbers));
        $this->assertEquals($goodresponse, dictionary_checker($twowordsspaceschars));
        $this->assertEquals($goodresponse, dictionary_checker($allnums));
        $this->assertEquals($goodresponse, dictionary_checker($allchars));

        $this->assertNotEquals($goodresponse, dictionary_checker($onewordnumbers));
        $this->assertNotEquals($goodresponse, dictionary_checker($onewordchars));
        $this->assertNotEquals($goodresponse, dictionary_checker($onewordnumberandchar));
        $this->assertNotEquals($goodresponse, dictionary_checker($onewordnumberspace));
        $this->assertNotEquals($goodresponse, dictionary_checker($onewordcharspace));
        $this->assertNotEquals($goodresponse, dictionary_checker($onewordcharandnumberspace));
    }

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
        $this->assertEquals($goodresponse, personal_information($safestring, $user));

        // Safe strings
        $this->assertEquals($goodresponse, personal_information($safestring, $user));

        // Bad strings
        $this->assertNotEquals($goodresponse, personal_information($badfname, $user));
        $this->assertNotEquals($goodresponse, personal_information($badlname, $user));
        $this->assertNotEquals($goodresponse, personal_information($badcity, $user));
        $this->assertNotEquals($goodresponse, personal_information($badusername, $user));
    }

    public function test_sequential_digits() {
        $this->resetAfterTest(true);
        set_config('sequential_digits_input', 5, 'tool_passwordvalidator');

        $goodresponse = '';
        $noseqdigits = 'a1b2c3d4';
        $safeseqdigits = 'a11b22c33';
        $maxseqdigits = 'a11111b22222';
        $overseqdigits = 'a111111b222222';
        $nodigits = 'abcd!@#$';
        $nodigitsrepeatsafe = 'aaabbb';
        $nodigitsrepeatmax = 'aaaaabbbbb';
        $nodigitsrepeatover = 'aaaaaabbbbbb';

        // Safe variables
        $this->assertEquals($goodresponse, sequential_digits($goodresponse));
        $this->assertEquals($goodresponse, sequential_digits($noseqdigits));
        $this->assertEquals($goodresponse, sequential_digits($safeseqdigits));
        $this->assertEquals($goodresponse, sequential_digits($maxseqdigits));
        $this->assertEquals($goodresponse, sequential_digits($nodigits));
        $this->assertEquals($goodresponse, sequential_digits($nodigitsrepeatsafe));
        $this->assertEquals($goodresponse, sequential_digits($nodigitsrepeatmax));
        $this->assertEquals($goodresponse, sequential_digits($nodigitsrepeatover));

        // Over the limit
        $this->assertNotEquals($goodresponse, sequential_digits($overseqdigits));
    }

    public function test_repeated_chars() {
        $this->resetAfterTest(true);
        set_config('repeated_chars_input', 4, 'tool_passwordvalidator');

        $goodresponse = '';
        $norepeatchars = 'a1b2c3d4';
        $saferepeatchars = 'aa1bb2cc3';
        $maxrepeatchars = 'aaaa1bbbb2cccc3';
        $overrepeatchars = 'aaaaa1bbbbb2ccccc3';
        $noletterssafe = '1122';
        $nolettersmax = '11112222';
        $nolettersover = '1111122222';

        // Safe variables
        $this->assertEquals($goodresponse, repeated_chars($goodresponse));
        $this->assertEquals($goodresponse, repeated_chars($norepeatchars));
        $this->assertEquals($goodresponse, repeated_chars($saferepeatchars));
        $this->assertEquals($goodresponse, repeated_chars($maxrepeatchars));
        $this->assertEquals($goodresponse, repeated_chars($noletterssafe));
        $this->assertEquals($goodresponse, repeated_chars($nolettersmax));

        // Over the limit, with letters or without
        $this->assertNotEquals($goodresponse, repeated_chars($overrepeatchars));
        $this->assertNotEquals($goodresponse, repeated_chars($nolettersover));

    }

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
        $this->assertEquals($goodresponse, phrase_blacklist($goodresponse));
        $this->assertEquals($goodresponse, phrase_blacklist($tooshort));
        $this->assertEquals($goodresponse, phrase_blacklist($safephrase));

        // Contains bad phrases
        $this->assertNotEquals($goodresponse, phrase_blacklist($badphrase1));
        $this->assertNotEquals($goodresponse, phrase_blacklist($badphrase2));
        $this->assertNotEquals($goodresponse, phrase_blacklist($badphrase3));
    }

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
        $this->assertNotEquals($goodresponse, lockout_period($testpassword, $user));

        // Wait 3 seconds then test again
        sleep(3);
        $this->assertEquals($goodresponse, lockout_period($testpassword, $user));

        // Repeat with a slightly longer period
        set_config('time_lockout_input', 4 , 'tool_passwordvalidator');
        user_add_password_history($user->id, 'passwordhistory2');
        $this->assertNotEquals($goodresponse, lockout_period($testpassword, $user));
        sleep(5);
        $this->assertEquals($goodresponse, lockout_period($testpassword, $user));

        // Then set to 24hrs (86400 seconds) ensure it takes values that large
        set_config('time_lockout_input', 86400 , 'tool_passwordvalidator');
        user_add_password_history($user->id, 'passwordhistory2');
        $this->assertNotEquals($goodresponse, lockout_period($testpassword, $user));
        sleep(2);
        $this->assertNotEquals($goodresponse, lockout_period($testpassword, $user));
    }

    public function test_password_blacklist() {
        // Due to constant data breaches etc, there is a chance one day these tests
        // may fail, as the passwords chosen as the safe test version may actually
        // become leaked

        $goodresponse = '';
        $badpassword = 'password';
        $safepassword = 'hopefully this password remains safe $&!@#*(%(&!@*(%';

        // Safe variables
        $this->assertEquals($goodresponse, password_blacklist($goodresponse));
        $this->assertEquals($goodresponse, password_blacklist($safepassword));

        // verified leaked password
        $this->assertNotEquals($goodresponse, password_blacklist($badpassword));
    }
}

