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
 * @package    tool_passwordvalidator
 * @copyright  2019 Peter Burnett <peterburnett@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 // Settings menu strings
$string['pluginname'] = 'Password Validator';
$string['passwordforcedconfig'] = 'Settings are read only, configuration is forced in template file: ';
$string['passwordbadconfigload'] = 'Unable to load template configuration file. Check variables are correct, and that template is inside the templates folder. - ';
$string['passwordirapcomplexityname'] = 'Enforce ISM Complexity Standards';
$string['passwordirapcomplexitydesc'] = 'Enforce ACSC Security Control 0421: Minimum password complexity. Passwords containing only letters must be at least 13 characters. Passwords containing at least 3 of 4: Lowercase letters, Uppercase letters, Numbers, Special characters, must be at least 10 characters.';
$string['passwordirapcomplexitysimple'] = 'Minimum Simple Complexity Length';
$string['passwordirapcomplexitysimpledesc'] = 'Minimum length for simple passwords with only letters.';
$string['passwordirapcomplexitycomplex'] = 'Minimum Complex Complexity Length';
$string['passwordirapcomplexitycomplexdesc'] = 'Minimum length for complex passwords with 3 or more character sets.';
$string['passwordirapnumbersname'] = 'Enforce Letters in Password';
$string['passwordirapnumbersdesc'] = 'Enforce ACSC Security Control 0417: Password cannot be only numbers, or contain only numbers and characters.';
$string['passworddictcheckname'] = 'Dictionary Word Count Checking';
$string['passworddictcheckdesc'] = 'Enforce that passwords must not be based of a single dictionary word. Password will be stripped to letters, then split on spaces.';
$string['passworddigitsname'] = 'Maximum Sequential Digits';
$string['passworddigitsdesc'] = 'Enforce a maximum number of sequential digits.';
$string['passworddigitsinputname'] = 'Maximum Sequential Digits Input';
$string['passworddigitsinputdesc'] = 'Enforce a maximum number of sequential digits in user passwords. Enter a number to use, or 0 to disable this control';
$string['passwordcharsname'] = 'Maximum Repeated Characters';
$string['passwordcharsdesc'] = 'Enforce a maximum number of repeated characters';
$string['passwordcharsinputname'] = 'Maximum Repeated Characters Input';
$string['passwordcharsinputdesc'] = 'Enforce a maximum number of sequential digits in user passwords. Enter a number to use, or 0 to disable this control.';
$string['passwordpersonalinfoname'] = 'Personal Information Checker';
$string['passwordpersonalinfodesc'] = 'Ensure no known personal information is contained in the password.';
$string['passwordphrasename'] = 'Enforce Phrase Blacklisting';
$string['passwordphrasedesc'] = 'Enforce blacklisting of chosen phrases such as service names in passwords';
$string['passwordphraseinputname'] = 'Phrase Blacklist Entry';
$string['passwordphraseinputdesc'] = 'Enter words or phrases to blacklist in passwords, such as service names. Put each new word or phrase on a seperate line. Matching is NOT case sensitive. E.g. "moodle" matches to "MOODLE".';
$string['passwordlockoutname'] = 'Password Change Lockout Period';
$string['passwordlockoutdesc'] = 'Enable to enforce a lockout period on password changes.';
$string['passwordlockoutinputname'] = 'Password Change Lockout Period Input';
$string['passwordlockoutinputdesc'] = 'Enforce a lockout period on users changing passwords. Time entered will be the period during which users cannot change their passwords. Enter a number in seconds, or 0 to disable this control.';
$string['passwordblacklistname'] = 'Check Password Against Blacklist';
$string['passwordblacklistdesc'] = 'Securely check passwords against the haveibeenpwned.com Breached passwords API.';
$string['passwordsettingsheading'] = 'Moodle Configuration Checker';
$string['passwordsettingsheadingdesc'] = 'Checks current moodle configuration and alerts users to any conflicts with the plugin, or insecure settings';
$string['passwordtesterheading'] = 'Password Validation Tester';
$string['passwordtesterheadingdesc'] = 'Enter a password into the box and save changes to test it against the current validation settings';
$string['passwordtestername'] = 'Password Tester Field';
$string['passwordtesterdesc'] = 'Password tester. Enter a password and save changes to see validation.';
$string['passwordtesterpass'] = 'Pass: Tester password passed validation settings. ';
$string['passwordtesterfail'] = 'Fail: Tester password failed validation settings: ';
$string['passwordtesterempty'] = 'No password entered to test.';
$string['passwordquestion'] = 'Prompt Security Questions';
$string['passwordquestiondesc'] = 'Prompt for security questions on password change.';

// Password validation responses
$string['responseminimumlength'] = 'Password does not meet minimum length requirements. Passwords of only letters and numbers must be length 13. Adding numbers and special characters must be length 10.<br>';
$string['responsenoletters'] = 'Password cannot consist of only numbers and/or special characters, or contain no letters.<br>';
$string['responsedictionaryfail'] = 'Password cannot be based of a single dictionary word. Add more words.';
$string['responseidentifyinginformation'] = 'Password contains identifying information.<br>';
$string['responsenumericsequence'] = 'Password contains numeric sequence.<br>';
$string['responserepeatedcharacters'] = 'Password contains repeated characters.<br>';
$string['responseblacklistphrase'] = 'Password contains blacklisted phrase such as service name.<br>';
$string['responsedatabaseerror'] = 'Error retrieving information from database.<br>';
$string['responselockoutperiod'] = 'Password already changed recently. Please try again later.<br>';
$string['responsebreachedpassword'] = 'Password found in online breached passwords collection.<br>';

// Moodle config checker strings
$string['configpasswordpolicy'] = 'It appears that a password policy is in place. Consider disabling this and enforcing a policy using the plugin.<br>';
$string['configpasswordrotationempty'] = 'It appears that the current password rotation limit is 0. This plugin relies on this configuration being set to atleast 1 for the password reset lockout period.
 It is recommended to set this value to atleast 1, but higher is better. ACSC recommends not reusing passwords within  8 changes. <br>';
$string['configpassworddigits'] = 'It appears that the enforced password policy requires at least one number. NIST recommends not enforcing mandatory characters in passwords.<br>';
$string['configpasswordspecialchars'] = 'It appears that the enforced password policy requires at least one alphanumeric character. NIST recommends not enforcing mandatory characters in password.<br>';
$string['configpasswordlowerletter'] = 'It appears that the enforced password policy requires at least one lowercase letter. NIST recommends not enforcing mandatory characters in passwords.<br>';
$string['configpasswordupperletter'] = 'It appears that the enforced password policy requires at least one uppercase letter. NIST recommends not enforcing mandatory characters in passwords.<br>';
$string['configpasswordgood'] = 'No incorrect moodle password configurations found.<br>';

/*
 * Privacy provider (GDPR)
 */
$string["privacy:no_data_reason"] = "The Password Policy Checker plugin does not store any personal data.";