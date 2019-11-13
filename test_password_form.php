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
 * Password Validation Settings form
 *
 * @package     tool_passwordvalidator
 * @copyright   Peter Burnett <peterburnett@catalyst-au.net>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");


class test_password_form extends moodleform {

    public function definition() {

        $mform = $this->_form;

        // Add Password entry form
        $mform->addElement('text', 'testerpassword', get_string('testpasswordpagepasswordbox', 'tool_passwordvalidator'));
        $mform->setType('testerpassword', PARAM_RAW);

        // Add optional username checker
        $mform->addElement('text', 'testerinput', get_string('testpasswordpageusernamebox', 'tool_passwordvalidator'));
        $mform->setType('testerinput', PARAM_RAW);

        $this->add_action_buttons(true, get_string('testpasswordpagetestbutton', 'tool_passwordvalidator'));
    }

    public function validation($data, $files) {
        global $DB;
        global $USER;
        require_once(__DIR__.'/lib.php');
        $errors = parent::validation($data, $files);

        // PASSWORD VALIDATION TEST
        $testpassword = $data['testerpassword'];
        $testerinput = $data['testerinput'];

        $otheruser = '';

        // try input as username first, then email
        $foundusers = $DB->get_records('user', array('username' => ($testerinput)));
        if (!empty($foundusers)) {
            // Get first matching username record
            $otheruser = reset($foundusers);
        } else {
            $foundusers = $DB->get_records('user', array('email' => ($testerinput)));
            if (!empty($foundusers)) {
                // Get first matching email record (should be unique)
                $otheruser = reset($foundusers);
            } else {
                $otheruser = $USER;
            }
        }

        // Don't check if testpassword is empty. If record exists for optional user, check pw against that account. Else, against currenlty logged in account
        $testervalidation = '';
        if ($testpassword != '') {
            $testervalidation = tool_passwordvalidator_check_password_policy($testpassword, $otheruser);
        }

        if (!empty($testervalidation)) {
            $errors['testerpassword'] = $testervalidation;
        }

        return $errors;
    }
}

