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


class validate_form extends moodleform {

    public function definition() {
        global $CFG;

        // Get default text from database
        $defpassword = get_config('tool_passwordvalidator', 'password_test_field');
        $defusername = get_config('tool_passwordvalidator', 'username_test_field');

        $mform = $this->_form;

        // Add Password entry form
        $mform->addElement('text', 'testerpassword', get_string('validatesettingspasswordbox', 'tool_passwordvalidator'));
        $mform->setType('testerpassword', PARAM_RAW);
        $mform->setDefault('testerpassword', $defpassword);

        // Get default for radio buttons
        $type = get_config('tool_passwordvalidator', 'test_field_type');
        if ($type == 'email') {
            $mform->setDefault('yesno', 1);
        } else if ($type == 'username') {
            $mform->setDefault('yesno', 0);
        }

        // Add radio buttons for user account checking
        $radioarray = array();
        $attributes = '';
        $radioarray[] = $mform->createElement('radio', 'yesno', '', get_string('validatesettingsradioemail', 'tool_passwordvalidator'), 1);
        $radioarray[] = $mform->createElement('radio', 'yesno', '', get_string('validatesettingsradiousername', 'tool_passwordvalidator'), 0);
        $mform->addGroup($radioarray, 'userdataradio', get_string('validatesettingsradiodesc', 'tool_passwordvalidator'), array(' '), false);

        // Add optional username checker
        $mform->addElement('text', 'testerinput', get_string('validatesettingsusernamebox', 'tool_passwordvalidator'));
        $mform->setType('testerinput', PARAM_RAW);
        $mform->setDefault('testerinput', $defusername);

        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        // Set tester password for validation check
        set_config('password_test_field', $data['testerpassword'], 'tool_passwordvalidator');
        if ($data['yesno'] == 1) {
            // Set type and value
            set_config('test_field', $data['testerinput'], 'tool_passwordvalidator');
            set_config('test_field_type', 'email', 'tool_passwordvalidator');
        } else {
            set_config('test_field', $data['testerinput'], 'tool_passwordvalidator');
            set_config('test_field_type', 'username', 'tool_passwordvalidator');
        }

        return $errors;
    }
}

