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
 * A form for password validation against custom settings
 *
 * @package   tool_passwordvalidator
 * @copyright 2019 Peter Burnett <peterburnett@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__) . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once(__DIR__.'/validate_form.php');
require_once(__DIR__.'/lib.php');

defined('MOODLE_INTERNAL') || die();

admin_externalpage_setup('tool_passwordvalidator_form');

$prevurl = ($CFG->wwwroot.'/admin/category.php?category=validator');

$form = new validate_form();

if ($form->is_cancelled()) {

    redirect($prevurl);

}
// @codingStandardsIgnoreStart
else if ($fromform = $form->get_data()) {
    // Empty, forces form to run validation to update config
}
// @codingStandardsIgnoreEnd

// PASSWORD VALIDATION TEST
$testpassword = get_config('tool_passwordvalidator', 'password_test_field');
$testerinput = get_config('tool_passwordvalidator', 'test_field');
$testertype = get_config('tool_passwordvalidator', 'test_field_type');

$failedconn = false;
$otheruser = '';

if ($testertype == 'username') {
    // Get record of user from database if username supplied
    try {
        $otheruser = $DB->get_record('user', array('username' => ($testerinput)));
    } catch (Exception $e) {
        $return .= get_string('responsedatabaseerror', 'tool_passwordvalidator').'<br>';
        $failedconn = true;
    } 
} else if ($testertype == 'email') {
    // Get record of user from database if email supplied
    try {
        $otheruser = $DB->get_record('user', array('email' => ($testerinput)));
    } catch (Exception $e) {
        $return .= get_string('responsedatabaseerror', 'tool_passwordvalidator').'<br>';
        $failedconn = true;
    } 
}



// Don't check if testpassword is empty. If record exists for optional user, check pw against that account. Else, against currenlty logged in account
$testervalidation = '';
if ($testpassword != '' && (!empty($otheruser))) {
    $testervalidation = password_validate($testpassword, true, $otheruser);
} else if ($testpassword != '' && (empty($otheruser))) {
    $testervalidation = password_validate($testpassword, true, $USER);
}

// Setup Notification for password validation
if ((trim($testervalidation) == '') && (trim($testpassword) !== '')) {
    // If no validation errors and pass isnt empty
    $message = 'passwordtesterpass';
    $type = 'notifysuccess';
} else if (trim($testpassword) == '') {
    // If password is empty, notify empty
    $message = 'passwordtesterempty';
    $type = 'notifymessage';
} else if ((trim($testervalidation) !== '') && (trim($testpassword) !== '')) {
    $message = 'passwordtesterfail';
    $type = 'notifyerror';
}

// Build the page output.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('validatesettingstring', 'tool_passwordvalidator'));

// Configuration Checker
echo '<br>';
echo '<h4>Moodle Configuration Checker</h4>';
$configcheckdesc = config_checker();
echo $OUTPUT->notification($configcheckdesc[0], $configcheckdesc[1]);
echo '<br>';

// Display password validation form
echo '<h4>Password Validation Tester</h4>';
$form->display();

// Display Validation result
echo '<br>';
echo $OUTPUT->notification(get_string($message, 'tool_passwordvalidator').' '. $testervalidation, $type);

echo $OUTPUT->footer();
