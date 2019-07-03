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

function password_validate($password, $test) {
    // Only execute checks if user isn't admin or is test mode.
    if ((!(is_siteadmin()) || $test == true)) {
        $errs = '';
        global $USER;
        // =====IRAP Certification checks=========
        // Complexity reqs X
        // Not only numbers X
        // no personal information X
        // no repeated characters X
        // no numeric sequences X

        // a single dictionary word
        // No repeat passwords

        // NIST Recommendations - beyond above IRAP
        // Black list of compromised passwords - HARD
        // No service name in password
        // =========================================

        // IRAP Complexity Reqs
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
        if (get_config('tool_password', 'sequential_digits')) {
            $errs .= sequential_digits($password);
        }

        // Check for repeated characters.
        if (get_config('tool_password', 'repeated_chars')) {
            $errs .= repeated_chars($password);
        }

        // Check for blacklist phrases - eg Service name
        if (get_config('tool_password', 'phrase_blacklist')) {
            $errs .= phrase_blacklist($password);
        }

        // Check for password changes on the user account within lockout period.
        if (get_config('tool_password', 'time_lockout')) {
            $errs .= lockout_period($password, $USER);
        }

        // Check against HaveIBeenPwned.com password breach API
        if (get_config('tool_password', 'password_blacklist')) {
            $errs .= password_blacklist($password);
        }

        return $errs;
    }
}

// Wrapper function
function tool_password_check_password_policy($password) {
    return password_validate($password, false);
}

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
        if ($lowercase === 0 && $uppercase === 0){
            $return .= get_string('responsenoletters', 'tool_password');
        }
    }
    return $return;
}

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

function repeated_chars($password) {
    $repeatchars = get_config('tool_password', 'repeated_chars_input');
    $characterpattern = '/(.)\1{'.$repeatchars.',}/';
    $return = '';

    if (preg_match($characterpattern, $password) === 1) {
        $return .= get_string('responserepeatedcharacters', 'tool_password');
    }
    return $return;
}

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

function lockout_period($password, $user) {
    $return = '';
    global $DB;
    try {
        $lastchanges = $DB->get_records('user_password_history', array('userid' => ($user->id)), 'timecreated DESC');
    } catch (Exception $e) {
        $return .= get_string('responsedatabaseerror', 'tool_password');
    }
    $currenttime = time();

    // get first elements timecreated, order from DB query
    $timechanged = reset($lastchanges)->timecreated;
    // Calculate 24 hr constant in seconds
    $day = 24 * 60 * 60;

    // Set the time modifier based on configuration
    $inputtime = get_config('tool_password', 'time_lockout_input');
    if ($inputtime <= 0) {
        $modifier = $day;
    } else {
        $modifier = $inputtime;
    }

    if ($timechanged >= ($currenttime - $modifier)) {
        $return .= get_string('responselockoutperiod', 'tool_password');
    }
    return $return;
}

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

function config_checker() {
    global $CFG;
    $response = '';
    $type = 'notifysuccess';

    //Check if a password policy is in place, not necessarily a fail
    if ($CFG->passwordpolicy == 1) {
        $response .= get_string('configpasswordpolicy', 'tool_password');
        //if notify is currently success
        if ($type == 'notifysuccess') {
            $type = 'notifymessage';
        }
    }

    //minimum char enforcement is a fail
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

