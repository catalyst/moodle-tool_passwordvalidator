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
 * Language pack
 *
 * @package    tool_passwordvalidator
 * @copyright  2019 Peter Burnett <peterburnett@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['checkpassword:checkpassword'] = 'Check a password via the web service API';
$string['configpassworddigits'] = 'It appears that the enforced password policy requires at least one number. NIST recommends not enforcing mandatory characters in passwords.';
$string['configpasswordgood'] = 'Moodle configuration appears to be correct.';
$string['configpasswordlowerletter'] = 'It appears that the enforced password policy requires at least one lowercase letter. NIST recommends not enforcing mandatory characters in passwords.';
$string['configpasswordminlength'] = 'It appears that the enforced password policy requires at least one character. It is recommend to set this control to 0, and enforce minimum length using this plugin.';
$string['configpasswordpolicy'] = 'It appears that the "Password Policy" control is disabled. If this control is disabled, new users will not be able to view information about the password policy when setting their password.';
$string['configpasswordrotationempty'] = 'It appears that the current password rotation limit is 0. This plugin relies on this configuration being set to atleast 1 for the password reset lockout period.
 It is recommended to set this value to atleast 1, but higher is better. ACSC recommends not reusing passwords within  8 changes.';
$string['configpasswordspecialchars'] = 'It appears that the enforced password policy requires at least one alphanumeric character. NIST recommends not enforcing mandatory characters in password.';
$string['configpasswordupperletter'] = 'It appears that the enforced password policy requires at least one uppercase letter. NIST recommends not enforcing mandatory characters in passwords.';
$string['passwordbadconfigload'] = 'Unable to load template configuration file. Check variables are correct, and that template is inside the templates folder. - ';
$string['passwordblacklistdesc'] = 'Securely check passwords against the haveibeenpwned.com Breached passwords API.';
$string['passwordblacklistname'] = 'Check password against blacklist';
$string['passwordcharsdesc'] = 'Enforce a maximum number of repeated characters';
$string['passwordcharsinputdesc'] = 'Enforce a maximum number of sequential digits in user passwords. Enter a number to use, or 0 to disable this control.';
$string['passwordcharsinputname'] = 'Maximum repeated characters input';
$string['passwordcharsname'] = 'Maximum repeated characters';
$string['passwordconfigloc'] = ' at location: ';
$string['passwordconfigpath'] = '/config_policies/{$a}.php';
$string['passworddictcheckdesc'] = 'Enforce that passwords must not be based of a single dictionary word. Password will be stripped to letters, then split on spaces.';
$string['passworddictcheckfiledesc'] = 'Enter the name of the dictionary file to search in.';
$string['passworddictcheckfilename'] = 'Dictionary file name';
$string['passworddictcheckname'] = 'Dictionary word count checking';
$string['passworddigitsdesc'] = 'Enforce a maximum number of sequential digits.';
$string['passworddigitsinputdesc'] = 'Enforce a maximum number of sequential digits in user passwords. Enter a number to use, or 0 to disable this control';
$string['passworddigitsinputname'] = 'Maximum sequential digits input';
$string['passworddigitsname'] = 'Maximum sequential digits';
$string['passwordenabledesc'] = 'Check to enable password validation.';
$string['passwordenablename'] = 'Enable plugin';
$string['passwordexpired'] = 'Your password is expired.';
$string['passwordexpiryinputdesc'] = 'Passwords that are older than this period are required to be changed. Set this control to 0 to disable this check.';
$string['passwordexpiryinputname'] = 'Password expiry duration';
$string['passwordforcedconfig'] = 'Settings are read only, configuration is forced in template file: ';
$string['passwordforcedconfigmanual'] = 'Settings are read only, configuration is set in a forced configuration file.';
$string['passwordirapcomplexitycomplex'] = 'Minimum complex complexity length';
$string['passwordirapcomplexitycomplexdesc'] = 'Minimum length for complex passwords with 3 or more character sets.';
$string['passwordirapcomplexitydesc'] = 'Minimum password complexity. This control enforces variable password length based on the complexity of the password characters. Passwords containing at least 3 of 4: Lowercase letters, Uppercase letters, Numbers, Special characters, are classed as complex.';
$string['passwordirapcomplexityname'] = 'Enforce ISM complexity standards';
$string['passwordirapcomplexitysimple'] = 'Minimum simple complexity length';
$string['passwordirapcomplexitysimpledesc'] = 'Minimum length for simple passwords with only letters.';
$string['passwordirapnumbersdesc'] = 'Password cannot be only numbers, or contain only numbers and characters.';
$string['passwordirapnumbersname'] = 'Enforce letters in password';
$string['passwordlockoutdesc'] = 'Enable to enforce a lockout period on password changes.';
$string['passwordlockoutinputdesc'] = 'Enforce a lockout period on users changing passwords. Time entered will be the period during which users cannot change their passwords. Enter a number in seconds, or 0 to disable this control.';
$string['passwordlockoutinputname'] = 'Password change lockout period input';
$string['passwordlockoutname'] = 'Password change lockout period';
$string['passwordpersonalinfodesc'] = 'Ensure no known personal information is contained in the password.';
$string['passwordpersonalinfoname'] = 'Personal information checker';
$string['passwordphrasedesc'] = 'Enforce blacklisting of chosen phrases such as service names in passwords';
$string['passwordphraseinputdesc'] = 'Enter words or phrases to blacklist in passwords, such as service names. Put each new word or phrase on a seperate line. Matching is NOT case sensitive. E.g. "moodle" matches to "MOODLE".';
$string['passwordphraseinputname'] = 'Phrase blacklist entry';
$string['passwordphrasename'] = 'Enforce phrase blacklisting';
$string['passwordpolicyISM0519'] = 'compliance with Australian ISM 2019';
$string['passwordpolicyNIST2018'] = 'compliance with NIST 2018';
$string['passwordpolicyNIST_ISM_2019'] = 'compliance with NIST 2018, and Australian ISM 2019';
$string['passwordpolicynotemplate'] = 'compliance with a custom set of security controls';
$string['passwordsettingsheading'] = 'Moodle configuration checker';
$string['passwordsettingsheadingdesc'] = 'Checks current moodle configuration and alerts users to any conflicts with the plugin, or insecure settings';
$string['passwordtesterdesc'] = 'Password tester. Enter a password and save changes to see validation.';
$string['passwordtesterempty'] = 'No password entered to test.';
$string['passwordtesterfail'] = 'Fail: Tester password failed validation settings: <br>';
$string['passwordtesterheading'] = 'Password validation tester';
$string['passwordtesterheadingdesc'] = 'Enter a password into the box and save changes to test it against the current validation settings';
$string['passwordtestername'] = 'Password tester field';
$string['passwordtesterpass'] = 'Pass: Tester password passed validation settings. ';
$string['passwordvalidator:checkpassword'] = 'Allowed to check password for other users';
$string['pluginname'] = 'Password validator';


$string["privacy:metadata"] = "The Password policy checker plugin does not store any personal data.";
$string['responseapierror'] = 'Service HaveIBeenPwned.com password API was not responsive.';
$string['responseblacklistphrase'] = 'Password contains blacklisted phrase: {$a}.';
$string['responsebreachedpassword'] = 'Password found in online breached passwords collection.';
$string['responsedatabaseerror'] = 'Error retrieving information from database.';
$string['responsedictionaryfailoneword'] = 'Password cannot be based of a single dictionary word: {$a}, consider adding more words.';
$string['responseidentifyinginformation'] = 'Password contains identifying information: {$a}';
$string['responselockoutperiod'] = 'Password already changed recently. Please try again in: {$a}.';
$string['responseminimumlength'] = 'Password must have at least {$a} characters.';
$string['responsenoletters'] = 'Password cannot consist of only numbers and/or special characters, or contain no letters.';
$string['responsenouser'] = 'No user account found for password personal information query.';
$string['responsenumericsequence'] = 'Password contains numeric sequence longer than: {$a}.';
$string['responserepeatedcharacters'] = 'Password contains repeated characters longer than: {$a}.';
$string['templateISM0519'] = 'This template enforces recommendations from the Australian ISM May 2019. Enforces minimum lengths for different password complexities, and character sets.';
$string['templateNIST2018'] = 'This template enforces recommendations from the NIST password recommendations. Does not include complexity requirements on the password composition.';
$string['templateNIST_ISM_2019'] = 'This template enforces recommendations from both the Australian ISM, as well as the NIST password recommendations. The strongest template implemented by default.';
$string['testpasswordconfigchecker'] = 'Moodle configuration checker';
$string['testpasswordpage'] = 'Password validation configuration';
$string['testpasswordpagepasswordbox'] = 'Enter a password to test:';
$string['testpasswordpagestring'] = 'Configuration and validation tester';
$string['testpasswordpagetestbutton'] = 'Test password';
$string['testpasswordpageusernamebox'] = 'Enter user account email or username to test configured password against:';
$string['testpasswordvalidationpassed'] = 'Password successfully passed validation testing.';
$string['testpasswordvalidationtester'] = 'Password validation tester';
