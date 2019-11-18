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
 * A tool to validate passwords against particular password policies.
 *
 * @package   tool_passwordvalidator
 * @copyright 2019 Peter Burnett <peterburnett@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Validates the password provided against the password policy configured in the plugin admin
 * settings menu. Calls all of the individual checks
 *
 * @param string $password The password to be validated.
 * @param bool $test Testmode. If true, the checks will run even if the executing account is an administrator.
 *             Used for tester validation in the settings menu.
 * @return string Returns a string of any errors presented by the checks, or an empty string for success.
 *
 */
function tool_passwordvalidator_password_validate($password, $user) {
    // Only execute checks if user isn't admin or is test mode.
    // Here so admin can force passwords
    $errs = '';

    // ACSC Security Control 0421
    // Check for character sets.
    if (get_config('tool_passwordvalidator', 'irap_complexity')) {
        $errs .= tool_passwordvalidator_complexity_checker($password, true);
    }

    // ACSC Security Control 0417
    // Not only numbers
    if (get_config('tool_passwordvalidator', 'irap_numbers')) {
        $errs .= tool_passwordvalidator_complexity_checker($password, false);
    }

    if (get_config('tool_passwordvalidator', 'dictionary_check')) {
        $errs .= tool_passwordvalidator_dictionary_checker($password);
    }

    // Checks based on user object
    if (!empty($user->id)) {
        // Personal Information Check.
        if (get_config('tool_passwordvalidator', 'personal_info')) {
            $errs .= tool_passwordvalidator_personal_information($password, $user);
        }

        // Check for password changes on the user account within lockout period.
        if (get_config('tool_passwordvalidator', 'time_lockout_input') > 0) {
            // If siteadmin, ignore this check
            if (!is_siteadmin()) {
                $errs .= tool_passwordvalidator_lockout_period($password, $user);
            }
        }
    }

    // Check for sequential digits.
    if (get_config('tool_passwordvalidator', 'sequential_digits_input') > 0) {
        $errs .= tool_passwordvalidator_sequential_digits($password);
    }

    // Check for repeated characters.
    if (get_config('tool_passwordvalidator', 'repeated_chars_input') > 0) {
        $errs .= tool_passwordvalidator_repeated_chars($password);
    }

    // Check for blacklist phrases - eg Service name
    if (get_config('tool_passwordvalidator', 'phrase_blacklist')) {
        $errs .= tool_passwordvalidator_phrase_blacklist($password);
    }

    // Check against HaveIBeenPwned.com password breach API
    if (get_config('tool_passwordvalidator', 'password_blacklist')) {
        $errs .= tool_passwordvalidator_password_blacklist($password);
    }

    return $errs;
}

/**
 * Complexity Checker. Checks character sets in the password, and returns if password
 * is not long enough based on the character sets found, and the length minimums set in the admin settings menu.
 *
 * @param string $password The password to be validated.
 * @param bool $complex A boolean flag for whether function is checking complexity, or the presence of letters.
 *             Used for function reuse.
 * @return string Returns a string of any errors presented by the check, or an empty string for success.
 *
 */
function tool_passwordvalidator_complexity_checker($password, $complex) {
    $return = '';
    $lowercasepattern = '/[a-z]/';
    $lowercase = preg_match($lowercasepattern, $password);

    $uppercasepattern = '/[A-Z]/';
    $uppercase = preg_match($uppercasepattern, $password);

    $numberspattern = '/[0-9]/';
    $numbers = preg_match($numberspattern, $password);

    $specialcharspattern = '/[^a-z,A-Z,0-9]/';
    $specialchars = preg_match($specialcharspattern, $password);

    // Minimum length checks based on character sets used
    if (($lowercase === 1) && ($uppercase === 1)) {
        if (($specialchars === 1) || ($numbers === 1)) {
            // At least 3 character sets found
            $minchars = get_config('tool_passwordvalidator', 'complex_length_input');
        } else {
            // Less than 3 charsets
            $minchars = get_config('tool_passwordvalidator', 'simple_length_input');
        }
    } else {
        // Less than 3 charsets
        $minchars = 13;
    }

    if ((strlen($password) < $minchars) && $complex) {
        $return .= get_string('responseminimumlength', 'tool_passwordvalidator', $minchars).'<br>';
    }

    if (!($complex)) {
        if ($lowercase === 0 && $uppercase === 0) {
            $return .= get_string('responsenoletters', 'tool_passwordvalidator').'<br>';
        }
    }
    return $return;
}

/**
 * Checks the password composition for dictionary words. Splits based on spaces, then checks the number of occurances against a dictionary
 *
 * @param string $password The password to be validated.
 * @return string Returns a string of any errors presented by the check, or an empty string for success.
 *
 */
function tool_passwordvalidator_dictionary_checker($password) {
    $return = '';
    // Strip special chars and numbers from password, to get raw words in array
    $strippedpw = trim(preg_replace("/[^a-zA-Z ]/", "", $password));
    $wordarray = explode(' ', $strippedpw);
    $wordcount = count($wordarray);

    // Read in dictionary file
    $dictpath = __DIR__.'/dictionary/'. get_config('tool_passwordvalidator', 'dictionary_check_file');
    try {
        $dict = fopen($dictpath, 'r');
    } catch (Exception $e) {
        $return .= 'Error opening file';
    }

    // Check every line of file for exact match, then reset file pointer to start
    $foundcount = 0;
    $lastword = '';
    foreach ($wordarray as $word) {
        while (!feof($dict)) {
            $dictword = trim(fgets($dict));

            if ($dictword == $word) {
                $foundcount++;
                $lastword = $word;
            }
        }
        rewind($dict);
    }
    $wordsreq = get_config('tool_passwordvalidator', 'dictionary_check');

    // If the amount of dictionary words found is 1, and there is only one word in the password
    if (($foundcount == 1) && ($wordcount == 1) && ($strippedpw != '')) {
        $return .= get_string('responsedictionaryfailoneword', 'tool_passwordvalidator', $lastword) . '<br>';
    }

    fclose($dict);
    return $return;
}

/**
 * Checks the password for known personal information supplied by the user. Any additional checks can
 * be added into the $badstrings array.
 *
 * @param string $password The password to be validated.
 * @return string Returns a string of any errors presented by the check, or an empty string for success.
 *
 */

function  tool_passwordvalidator_personal_information($password, $user) {
    // Protection from malformed accounts, if they have an id but no data
    // Check for fname, lname, city, username
    $badstrings = array();
    if (!empty($user->firstname)) {
        array_push($badstrings, $user->firstname);
    }
    if (!empty($user->lastname)) {
        array_push($badstrings, $user->lastname);
    }
    if (!empty($user->city)) {
        array_push($badstrings, $user->city);
    }
    if (!empty($user->username)) {
        array_push($badstrings, $user->username);
    }

    $return = '';

    foreach ($badstrings as $string) {
        $string = trim($string);
        // Ignore strings if they are too short
        if (strlen($string) > 1) {
            if (stripos($password, $string) !== false) {
                $return .= get_string('responseidentifyinginformation', 'tool_passwordvalidator', $string).'<br>';
                break;
            }
        }
    }
    return $return;
}

/**
 * Checks the password for sequential numeric characters, to avoid number sequences such as dates.
 * Number to check against is specified in the admin settings menu.
 *
 * @param string $password The password to be validated.
 * @return string Returns a string of any errors presented by the check, or an empty string for success.
 *
 */
function tool_passwordvalidator_sequential_digits($password) {
    // get maximum allowed number of digits, add 1 to work in the regex
    $seqdigits = get_config('tool_passwordvalidator', 'sequential_digits_input') + 1;
    $digitpattern = '/\d{'.$seqdigits.',}/u';
    $return = '';

    if (preg_match($digitpattern, $password) === 1) {
        $return .= get_string('responsenumericsequence', 'tool_passwordvalidator', ($seqdigits - 1)).'<br>';
    }

    return $return;
}

/**
 * Checks the password for repeated characters such as 'AAAAA'. Number of allowed sequential characters
 * is specified in the admin settings menu.
 *
 * @param string $password The password to be validated.
 * @return string Returns a string of any errors presented by the check, or an empty string for success.
 *
 */
function tool_passwordvalidator_repeated_chars($password) {
    $repeatchars = get_config('tool_passwordvalidator', 'repeated_chars_input');
    $characterpattern = '/(.)\1{'.$repeatchars.',}/';
    $return = '';

    if (preg_match($characterpattern, $password) === 1) {
        $return .= get_string('responserepeatedcharacters', 'tool_passwordvalidator', $repeatchars).'<br>';
    }
    return $return;
}

/**
 * Checks password for any blacklisted phrase such as service name. Blacklisted phrases are
 * specified in the admin settings menu.
 *
 * @param string $password The password to be validated.
 * @return string Returns a string of any errors presented by the check, or an empty string for success.
 *
 */
function tool_passwordvalidator_phrase_blacklist($password) {
    $phrasesraw = get_config('tool_passwordvalidator', 'phrase_blacklist_input');
    $phrases = explode(PHP_EOL, $phrasesraw);
    $return = '';

    foreach ($phrases as $string) {
        $tstring = trim($string);
        if (stripos($password, $tstring) !== false) {
            $return .= get_string('responseblacklistphrase', 'tool_passwordvalidator', $tstring).'<br>';
            break;
        }
    }
    return $return;
}

/**
 * Checks account database settings for the last time password was changed. If time is within a period specified
 * in the admin settings menu from the last password change, error returned.
 *
 * @param string $password The password to be validated.
 * @param object $user The user account to check the database for time changes on. Should always be the current user accoun.
 *               Used only for testing purposes.
 * @return string Returns a string of any errors presented by the check, or an empty string for success.
 *
 */
function tool_passwordvalidator_lockout_period($password, $user) {
    global $DB;

    $lastchanges = $DB->get_records('user_password_history', array('userid' => ($user->id)), 'timecreated DESC', 'timecreated', 0, 1);
    // get first elements timecreated, order from DB query
    if (!empty($lastchanges)) {
        $timechanged = reset($lastchanges)->timecreated;
    } else {
        // No timechange found, return passed validation
        return '';
    }

    $currenttime = time();

    // Set the time modifier based on configuration
    $inputtime = get_config('tool_passwordvalidator', 'time_lockout_input');

    // Default to 1 day if time not set
    if ($inputtime == '') {
        $inputtime = DAYSECS;
    }

    // Check if currenttime is within the lockout period
    $timeleft = $inputtime - ($currenttime - $timechanged);
    if ($timeleft > 0) {
        $timerem = format_time($timeleft);
        return get_string('responselockoutperiod', 'tool_passwordvalidator', $timerem).'<br>';
    }

    return '';
}

/**
 * Checks password against the HaveIBeenPwned password breach API. No passwords are transferred.
 * Password is hashed, and only the first 5 characters are sent over the network.
 *
 * @param string $password The password to be validated.
 * @return string Returns a string of any errors presented by the check, or an empty string for success.
 *
 */
function tool_passwordvalidator_password_blacklist($password) {
    global $CFG;
    require_once($CFG->libdir.'/filelib.php');
    $return = '';
    $api = 'https://api.pwnedpasswords.com/range/';
    // Get first 5 chars of hash to search API for
    $pwhash = sha1($password);
    $searchstring = substr($pwhash, 0, 5);

    // Get API response
    $url = $api .= $searchstring;
    $response = download_file_content($url, null, null, false, 5, 5);
    if ($response == false) {
        // Logged in user object for failed web request event trigger
        global $USER;

        // Create error event, and log it
        $failmessage = get_string('responseapierror', 'tool_passwordvalidator');
        $event = \core\event\webservice_login_failed::create(array('other' => array('reason' => $failmessage, 'method' => '')));
        $event->trigger();
        return '';
    } else {
        // Check for presence of hash in response
        $shorthash = substr($pwhash, 5);
        if (stripos($response, $shorthash) !== false) {
            $return .= get_string('responsebreachedpassword', 'tool_passwordvalidator').'<br>';
        }
    }
    return $return;
}

/**
 * Checks the global moodle configuration for any settings that conflict or are relied upon by the plugin
 *
 * @return string Returns a string of any errors presented by the check, or an empty string for success.
 *
 */
function tool_passwordvalidator_config_checker() {
    global $CFG;
    $response = '';
    $type = 'notifysuccess';

    // Check if a password policy is in place, inform users of visibility of password policy
    if ($CFG->passwordpolicy != 1) {
        $response .= get_string('configpasswordpolicy', 'tool_passwordvalidator').'<br>';
        // If notify is currently success
        if ($type == 'notifysuccess') {
            $type = 'notifymessage';
        }
    }

    // Minimum length enforcement is a fail
    if (($CFG->passwordpolicy == 1) && $CFG->minpasswordlength >= 1) {
        $response .= get_string('configpasswordminlength', 'tool_passwordvalidator').'<br>';
        $type = 'notifyerror';
    }

    // Minimum char enforcement is a fail
    if (($CFG->passwordpolicy == 1) && $CFG->minpassworddigits >= 1) {
        $response .= get_string('configpassworddigits', 'tool_passwordvalidator').'<br>';
        $type = 'notifyerror';
    }
    if (($CFG->passwordpolicy == 1) && $CFG->minpasswordlower >= 1) {
        $response .= get_string('configpasswordlowerletter', 'tool_passwordvalidator').'<br>';
        $type = 'notifyerror';
    }
    if (($CFG->passwordpolicy == 1) && $CFG->minpasswordupper >= 1) {
        $response .= get_string('configpasswordupperletter', 'tool_passwordvalidator').'<br>';
        $type = 'notifyerror';
    }
    if (($CFG->passwordpolicy == 1) && $CFG->minpasswordnonalphanum >= 1) {
        $response .= get_string('configpasswordspecialchars', 'tool_passwordvalidator').'<br>';
        $type = 'notifyerror';
    }

    // Password rotation not beind enabled is a fail
    if ($CFG->passwordreuselimit < 1) {
        $response .= get_string('configpasswordrotationempty', 'tool_passwordvalidator').'<br>';
        $type = 'notifyerror';
    }

    // If no errors at end, return a good message
    if ($type == 'notifysuccess') {
        $response .= get_string('configpasswordgood', 'tool_passwordvalidator').'<br>';
    }

    return array($response, $type);
}