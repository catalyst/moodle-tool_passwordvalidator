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
$string['pluginname'] = 'Password validator';
$string['passwordenablename'] = 'Enable plugin';
$string['passwordenabledesc'] = 'Check to enable password validation.';
$string['passwordforcedconfig'] = 'Settings are read only, configuration is forced in template file: ';
$string['passwordbadconfigload'] = 'Unable to load template configuration file. Check variables are correct, and that template is inside the templates folder. - ';
$string['passwordconfigloc'] = ' at location: ';
$string['passwordconfigpath'] = '/config_policies/{$a}.php';
$string['passwordirapcomplexityname'] = 'Enforce ISM complexity standards';
$string['passwordirapcomplexitydesc'] = 'Enforce ACSC Security Control 0421: Minimum password complexity. Passwords containing only letters must be at least 13 characters. Passwords containing at least 3 of 4: Lowercase letters, Uppercase letters, Numbers, Special characters, must be at least 10 characters.';
$string['passwordirapcomplexitysimple'] = 'Minimum simple complexity length';
$string['passwordirapcomplexitysimpledesc'] = 'Minimum length for simple passwords with only letters.';
$string['passwordirapcomplexitycomplex'] = 'Minimum complex complexity length';
$string['passwordirapcomplexitycomplexdesc'] = 'Minimum length for complex passwords with 3 or more character sets.';
$string['passwordirapnumbersname'] = 'Enforce letters in password';
$string['passwordirapnumbersdesc'] = 'Enforce ACSC Security Control 0417: Password cannot be only numbers, or contain only numbers and characters.';
$string['passworddictcheckname'] = 'Dictionary word count checking';
$string['passworddictcheckdesc'] = 'Enforce that passwords must not be based of a single dictionary word. Password will be stripped to letters, then split on spaces.';
$string['passworddictcheckfilename'] = 'Dictionary file name';
$string['passworddictcheckfiledesc'] = 'Enter the name of the dictionary file to search in.';
$string['passworddigitsname'] = 'Maximum sequential digits';
$string['passworddigitsdesc'] = 'Enforce a maximum number of sequential digits.';
$string['passworddigitsinputname'] = 'Maximum sequential digits input';
$string['passworddigitsinputdesc'] = 'Enforce a maximum number of sequential digits in user passwords. Enter a number to use, or 0 to disable this control';
$string['passwordcharsname'] = 'Maximum repeated characters';
$string['passwordcharsdesc'] = 'Enforce a maximum number of repeated characters';
$string['passwordcharsinputname'] = 'Maximum repeated characters input';
$string['passwordcharsinputdesc'] = 'Enforce a maximum number of sequential digits in user passwords. Enter a number to use, or 0 to disable this control.';
$string['passwordpersonalinfoname'] = 'Personal information checker';
$string['passwordpersonalinfodesc'] = 'Ensure no known personal information is contained in the password.';
$string['passwordphrasename'] = 'Enforce phrase blacklisting';
$string['passwordphrasedesc'] = 'Enforce blacklisting of chosen phrases such as service names in passwords';
$string['passwordphraseinputname'] = 'Phrase blacklist entry';
$string['passwordphraseinputdesc'] = 'Enter words or phrases to blacklist in passwords, such as service names. Put each new word or phrase on a seperate line. Matching is NOT case sensitive. E.g. "moodle" matches to "MOODLE".';
$string['passwordlockoutname'] = 'Password change lockout period';
$string['passwordlockoutdesc'] = 'Enable to enforce a lockout period on password changes.';
$string['passwordlockoutinputname'] = 'Password change lockout period input';
$string['passwordlockoutinputdesc'] = 'Enforce a lockout period on users changing passwords. Time entered will be the period during which users cannot change their passwords. Enter a number in seconds, or 0 to disable this control.';
$string['passwordblacklistname'] = 'Check password against blacklist';
$string['passwordblacklistdesc'] = 'Securely check passwords against the haveibeenpwned.com Breached passwords API.';
$string['passwordsettingsheading'] = 'Moodle configuration checker';
$string['passwordsettingsheadingdesc'] = 'Checks current moodle configuration and alerts users to any conflicts with the plugin, or insecure settings';
$string['passwordtesterheading'] = 'Password validation tester';
$string['passwordtesterheadingdesc'] = 'Enter a password into the box and save changes to test it against the current validation settings';
$string['passwordtestername'] = 'Password tester field';
$string['passwordtesterdesc'] = 'Password tester. Enter a password and save changes to see validation.';
$string['passwordtesterpass'] = 'Pass: Tester password passed validation settings. ';
$string['passwordtesterfail'] = 'Fail: Tester password failed validation settings: <br>';
$string['passwordtesterempty'] = 'No password entered to test.';

// Password validation responses
$string['responseminimumlength'] = 'Password must have at least {$a} characters.';
$string['responsenoletters'] = 'Password cannot consist of only numbers and/or special characters, or contain no letters.';
$string['responsedictionaryfailoneword'] = 'Password cannot be based of a single dictionary word: {$a}, consider adding more words.';
$string['responseidentifyinginformation'] = 'Password contains identifying information: {$a}';
$string['responsenumericsequence'] = 'Password contains numeric sequence longer than: {$a}.';
$string['responserepeatedcharacters'] = 'Password contains repeated characters longer than: {$a}.';
$string['responseblacklistphrase'] = 'Password contains blacklisted phrase: {$a}.';
$string['responsedatabaseerror'] = 'Error retrieving information from database.';
$string['responselockoutperiod'] = 'Password already changed recently. Please try again in: {$a}.';
$string['responsebreachedpassword'] = 'Password found in online breached passwords collection.';
$string['responsenouser'] = 'No user account found for password personal information query.';
$string['responseapierror'] = 'Service HaveIBeenPwned.com password API was not responsive.';

// Moodle config checker strings
$string['configpasswordpolicy'] = 'It appears that the "Password Policy" control is disabled. If this control is disabled, new users will not be able to view information about the password policy when setting their password.';
$string['configpasswordrotationempty'] = 'It appears that the current password rotation limit is 0. This plugin relies on this configuration being set to atleast 1 for the password reset lockout period.
 It is recommended to set this value to atleast 1, but higher is better. ACSC recommends not reusing passwords within  8 changes.';
$string['configpassworddigits'] = 'It appears that the enforced password policy requires at least one number. NIST recommends not enforcing mandatory characters in passwords.';
$string['configpasswordspecialchars'] = 'It appears that the enforced password policy requires at least one alphanumeric character. NIST recommends not enforcing mandatory characters in password.';
$string['configpasswordminlength'] = 'It appears that the enforced password policy requires at least one character. It is recommend to set this control to 0, and enforce minimum length using this plugin.';
$string['configpasswordlowerletter'] = 'It appears that the enforced password policy requires at least one lowercase letter. NIST recommends not enforcing mandatory characters in passwords.';
$string['configpasswordupperletter'] = 'It appears that the enforced password policy requires at least one uppercase letter. NIST recommends not enforcing mandatory characters in passwords.';
$string['configpasswordgood'] = 'Moodle configuration appears to be correct.';

// Template Description Strings
$string['templateNIST_ISM_2019'] = 'This template enforces recommendations from both the Australian ISM, as well as the NIST password recommendations. The strongest template implemented by default.';
$string['templateNIST2018'] = 'This template enforces recommendations from the NIST password recommendations. Does not include complexity requirements on the password composition.';
$string['templateISM0519'] = 'This template enforces recommendations from the Australian ISM May 2019. Enforces minimum lengths for different password complexities, and character sets.';

// Password Test Form Strings
$string['testpasswordpagestring'] = 'Configuration and validation tester';
$string['testpasswordpage'] = 'Password validation configuration';
$string['testpasswordpagepasswordbox'] = 'Enter a password to test:';
$string['testpasswordpageusernamebox'] = 'Enter user account email or username to test configured password against:';
$string['testpasswordpagetestbutton'] = 'Test password';
$string['testpasswordconfigchecker'] = 'Moodle configuration checker';
$string['testpasswordvalidationtester'] = 'Password validation tester';
$string['testpasswordvalidationpassed'] = 'Password successfully passed validation testing.';

// Password Policy Strings
$string['passwordpolicynotemplate'] = 'compliance with a custom set of security controls';
$string['passwordpolicyNIST_ISM_2019'] = 'compliance with NIST 2018, and Australian ISM 2019';
$string['passwordpolicyNIST2018'] = 'compliance with NIST 2018';
$string['passwordpolicyISM0519'] = 'compliance with Australian ISM 2019';
/*
 * Privacy provider (GDPR)
 */
$string["privacy:metadata"] = "The Password policy checker plugin does not store any personal data.";
