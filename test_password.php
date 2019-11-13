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
require_once(__DIR__.'/test_password_form.php');
require_once(__DIR__.'/locallib.php');

admin_externalpage_setup('tool_passwordvalidator_form');

$prevurl = ($CFG->wwwroot.'/admin/category.php?category=validator');
$success = false;
$configcheckdesc = tool_passwordvalidator_config_checker();

$form = new test_password_form();

if ($form->is_cancelled()) {
    redirect($prevurl);
} else if ($fromform = $form->get_data()) {
    $success = true;
}

// Build the page output.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('testpasswordpagestring', 'tool_passwordvalidator'));

// Configuration Checker
echo '<br>';
echo $OUTPUT->heading(get_string('testpasswordconfigchecker', 'tool_passwordvalidator'), 4);
echo $OUTPUT->notification($configcheckdesc[0], $configcheckdesc[1]);
echo '<br>';

// Display password validation form
echo $OUTPUT->heading(get_string('testpasswordvalidationtester', 'tool_passwordvalidator'), 4);
if ($success) {
    echo $OUTPUT->notification(get_string('testpasswordvalidationpassed', 'tool_passwordvalidator'), 'notifysuccess');
}

$form->display();

echo $OUTPUT->footer();
