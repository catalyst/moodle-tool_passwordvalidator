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
 * @package     tool_trigger
 * @copyright   Matt Porritt <mattp@catalyst-au.net>
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
        $errors = parent::validation($data, $files);

        // PASSWORD VALIDATION TEST
        $testpassword = $data['testerpassword'];
        $testerinput = $data['testerinput'];

        $otheruser = '';

        // try input as username first, then email
        $otheruser = $DB->get_record('user', array('username' => ($testerinput)));
        if (empty($otheruser)) {
            // if not found, try username
            $otheruser = $DB->get_record('user', array('email' => ($testerinput)));
            if (empty($otheruser)) {
                $otheruser = $USER;
            }
        }

        // Don't check if testpassword is empty. If record exists for optional user, check pw against that account. Else, against currenlty logged in account
        $testervalidation = '';
        if ($testpassword != '') {
            $testervalidation = tool_passwordvalidator_password_validate($testpassword, true, $otheruser);
        }

        $errors['testerpassword'] = $testervalidation;

        return $errors;
    }
}

