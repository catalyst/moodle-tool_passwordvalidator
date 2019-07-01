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
 *
 * @package    tool_password
 * @copyright  2019 Peter Burnett <peterburnett@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Password Policy Checker';
$string['passwordirapcomplexityname'] = 'Enforce IRAP Complexity Standards';
$string['passwordirapcomplexitydesc'] = 'Enforce ACSC Security Control 0421: Minimum password complexity. Passwords containing only letters must be at least 13 characters. Passwords containing at least 3 of 4: Lowercase letters, Uppercase letters, Numbers, Special characters, must be at least 10 characters.';
$string['passwordirapnumbersname'] = 'Enforce Letters and Characters in Password';
$string['passwordirapnumbersdesc'] = 'Enforce ACSC Security Control 0417: Password cannot be only numbers';
$string['passworddigitsname'] = 'Maximum Sequential Digits';
$string['passworddigitsdesc'] = 'Enforce a maximum number of sequential digits.';
$string['passworddigitsinputname'] = 'Maximum Sequential Digits Input';
$string['passworddigitsinputdesc'] = 'Maximum number of sequential digits.';
$string['passwordcharsname'] = 'Maximum Repeated Characters';
$string['passwordcharsdesc'] = 'Enforce a maximum number of repeated characters';
$string['passwordcharsinputname'] = 'Maximum Repeated Characters Input';
$string['passwordcharsinputdesc'] = 'Maximum number of sequential digits.';
$string['passwordpersonalinfoname'] = 'Personal Information Checker';
$string['passwordpersonalinfodesc'] = 'Ensure no known personal information is contained in the password.';
$string['passwordphrasename'] = 'Enforce Phrase Blacklisting';
$string['passwordphrasedesc'] = 'Enforce blacklisting of chosen phrases such as service names in passwords';
$string['passwordphraseinputname'] = 'Phrase Blacklist Entry';
$string['passwordphraseinputdesc'] = 'Enter words or phrases to blacklist in passwords, such as service names. Put each new word or phrase on a seperate line. Matching is NOT case sensitive. E.g. "moodle" matches to "MOODLE".';
$string['passwordlockoutname'] = 'Password Change Lockout Period';
$string['passwordlockoutdesc'] = 'Enable to enforce a lockout period on password changes.';
$string['passwordlockoutinputname'] = 'Password Change Lockout Period Input';
$string['passwordlockoutinputdesc'] = 'Enter a lockout period in seconds (for unix timestamping). Enter 0 for 24 hours default';
$string['passwordtesterheading'] = 'Password Validation Tester';
$string['passwordtesterheadingdesc'] = 'Enter a password into the box and save changes to test it against the current validation settings';
$string['passwordtestername'] = 'Password Tester Field';
$string['passwordtesterdesc'] = 'Password tester. Enter a password and save changes to see validation.';
$string['passwordtesterpass'] = 'Pass: Tester password passed validation settings. ';
$string['passwordtesterfail'] = 'Fail: Tester password failed validation settings: ';
$string['passwordtesterempty'] = 'No password entered to test.';


/*
 * Privacy provider (GDPR)
 */
$string["privacy:no_data_reason"] = "The Password Policy Checker plugin does not store any personal data.";