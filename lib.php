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
 *
 * @package   tool_password
 * @copyright 2019 Peter Burnett <peterburnett@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__.'/../../../config.php');
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
function password_validate($password, $test) {
    // Only execute checks if user isn't admin or is test mode.c
    // Here so admin can force passwords
    if ((!(is_siteadmin()) || $test == true)) {
        $errs = '';
        global $USER;

        // ACSC Security Control 0421
        // Check for character sets.
        if (get_config('tool_password', 'irap_complexity')) {
            $errs .= complexity_checker($password, true);
        }

        // ACSC Security Control 0417
        // Not only numbers
        if (get_config('tool_password', 'irap_numbers')) {
            $errs .= complexity_checker($password, false);
        }

        // Personal Information Check.
        if (get_config('tool_password', 'personal_info')) {
            $errs .= personal_information($password);
        }

        // Check for sequential digits.
        if (get_config('tool_password', 'sequential_digits_input') > 0) {
            $errs .= sequential_digits($password);
        }

        // Check for repeated characters.
        if (get_config('tool_password', 'repeated_chars_input') > 0) {
            $errs .= repeated_chars($password);
        }

        // Check for blacklist phrases - eg Service name
        if (get_config('tool_password', 'phrase_blacklist')) {
            $errs .= phrase_blacklist($password);
        }

        // Check for password changes on the user account within lockout period.
        if (get_config('tool_password', 'time_lockout_input') > 0) {
            $errs .= lockout_period($password, $USER);
        }

        // Check against HaveIBeenPwned.com password breach API
        if (get_config('tool_password', 'password_blacklist')) {
            $errs .= password_blacklist($password);
        }

        return $errs;
    }
}

/**
 * Wrapper function for the password validation. Simply calls password validate
 * with test mode disabled.
 *
 * @param string $password The password to be validated.
 * @return string Returns a string of any errors presented by the checks, or an empty string for success.
 *
 */
function tool_password_check_password_policy($password) {
    return password_validate($password, false);
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
function complexity_checker($password, $complex) {
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
            $minchars = 10;
        } else {
            // Less than 3 charsets
            $minchars = 13;
        }
    } else {
        // Less than 3 charsets
        $minchars = 13;
    }

    if ((strlen($password) < $minchars) && $complex) {
        $return .= get_string('responseminimumlength', 'tool_password');
    }

    if (!($complex)) {
        if ($lowercase === 0 && $uppercase === 0) {
            $return .= get_string('responsenoletters', 'tool_password');
        }
    }
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
function personal_information($password) {
    // Check for fname, lname, city, username
    global $USER;
    $badstrings = array($USER->firstname, $USER->lastname,
    $USER->city, $USER->username);
    $return = '';

    foreach ($badstrings as $string) {
        if (stripos($password, $string) !== false) {
            $return .= get_string('responseidentifyinginformation', 'tool_password');
            break;
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
function sequential_digits($password) {
    // get maximum allowed number of digits, add 1 to work in the regex
    $seqdigits = get_config('tool_password', 'sequential_digits_input') + 1;
    $digitpattern = '/\d{'.$seqdigits.',}/u';
    $return = '';

    if (preg_match($digitpattern, $password) === 1) {
        $return .= get_string('responsenumericsequence', 'tool_password');
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
function repeated_chars($password) {
    $repeatchars = get_config('tool_password', 'repeated_chars_input');
    $characterpattern = '/(.)\1{'.$repeatchars.',}/';
    $return = '';

    if (preg_match($characterpattern, $password) === 1) {
        $return .= get_string('responserepeatedcharacters', 'tool_password');
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
function phrase_blacklist($password) {
    $phrasesraw = get_config('tool_password', 'phrase_blacklist_input');
    $phrases = explode(PHP_EOL, $phrasesraw);
    $return = '';

    foreach ($phrases as $string) {
        $tstring = trim($string);
        if (stripos($password, $tstring) !== false) {
            $return .= get_string('responseblacklistphrase', 'tool_password');
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
function lockout_period($password, $user) {
    $return = '';
    global $DB;
    $failedconn = false;
    try {
        $lastchanges = $DB->get_records('user_password_history', array('userid' => ($user->id)), 'timecreated DESC');
        // get first elements timecreated, order from DB query
        $timechanged = reset($lastchanges)->timecreated;
    } catch (Exception $e) {
        $return .= get_string('responsedatabaseerror', 'tool_password');
        $failedconn = true;
    }
    $currenttime = time();

    // Calculate 24 hr constant in seconds
    $day = 24 * 60 * 60;

    // Set the time modifier based on configuration
    $inputtime = get_config('tool_password', 'time_lockout_input');
    
    // check for failed connection so no errors from timechanged being unset
    if (!($failedconn)) {
        if ($timechanged >= ($currenttime - $inputtime)) {
            $return .= get_string('responselockoutperiod', 'tool_password');
        }
    }
    return $return;
}

/**
 * Checks password against the HaveIBeenPwned password breach API. No passwords are transferred.
 * Password is hashed, and only the first 5 characters are sent over the network.
 *
 * @param string $password The password to be validated.
 * @return string Returns a string of any errors presented by the check, or an empty string for success.
 *
 */
function password_blacklist($password) {
    $return = '';
    $api = 'https://api.pwnedpasswords.com/range/';
    // Get first 5 chars of hash to search API for
    $pwhash = sha1($password);
    $searchstring = substr($pwhash, 0, 5);

    // Get API response
    $url = $api .= $searchstring;
    $response = file_get_contents($url);

    // Check for presence of hash in response
    $shorthash = substr($pwhash, 5);
    if (stripos($response, $shorthash) !== false) {
        $return .= get_string('responsebreachedpassword', 'tool_password');
    }
    return $return;
}

/**
 * Checks the global moodle configuration for any settings that conflict or are relied upon by the plugin
 *
 * @return string Returns a string of any errors presented by the check, or an empty string for success.
 *
 */
function config_checker() {
    global $CFG;
    $response = '';
    $type = 'notifysuccess';

    // Check if a password policy is in place, not necessarily a fail
    if ($CFG->passwordpolicy == 1) {
        $response .= get_string('configpasswordpolicy', 'tool_password');
        // If notify is currently success
        if ($type == 'notifysuccess') {
            $type = 'notifymessage';
        }
    }

    // Minimum char enforcement is a fail
    if (($CFG->passwordpolicy == 1) && $CFG->minpassworddigits >= 1) {
        $response .= get_string('configpassworddigits', 'tool_password');
        $type = 'notifyerror';
    }
    if (($CFG->passwordpolicy == 1) && $CFG->minpasswordlower >= 1) {
        $response .= get_string('configpasswordlowerletter', 'tool_password');
        $type = 'notifyerror';
    }
    if (($CFG->passwordpolicy == 1) && $CFG->minpasswordupper >= 1) {
        $response .= get_string('configpasswordupperletter', 'tool_password');
        $type = 'notifyerror';
    }
    if (($CFG->passwordpolicy == 1) && $CFG->minpasswordnonalphanum >= 1) {
        $response .= get_string('configpasswordspecialchars', 'tool_password');
        $type = 'notifyerror';
    }

    // Password rotation not beind enabled is a fail
    if ($CFG->passwordreuselimit < 1) {
        $response .= get_string('configpasswordrotationempty', 'tool_password');
        $type = 'notifyerror';
    }

    // If no errors at end, return a good message
    if ($type == 'notifysuccess') {
        $response .= get_string('configpasswordgood', 'tool_password');
    }

    return array($response, $type);
}

