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
 *  Password Policy Checker Settings Page
 *
 * @package    tool_password
 * @copyright     Peter Burnett <peterburnett@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {

    $settings = new admin_settingpage('tool_password', get_string('pluginname', 'tool_password'));

    $ADMIN->add('tools', $settings);
    if (!during_initial_install()) {

        // Sequential digits settings.
        $settings->add(new admin_setting_configcheckbox('repeated_digits', get_string('passworddigitsname', 'tool_password'),
                    get_string('passworddigitsdesc', 'tool_password'), 1));
        
        $settings->add(new admin_setting_configtext('repeated_digits_input', get_string('passworddigitsinputname', 'tool_password'),
                    get_string('passworddigitsinputdesc', 'tool_password'), 2, PARAM_INT)); 
        
        // Repeated characters settings
        $settings->add(new admin_setting_configcheckbox('repeated_chars', get_string('passwordcharsname', 'tool_password'),
                    get_string('passwordcharsdesc', 'tool_password'), 1));

        $settings->add(new admin_setting_configtext('repeated_chars_input', get_string('passwordcharsinputname', 'tool_password'),
                    get_string('passwordcharsinputdesc', 'tool_password'), 1, PARAM_INT));
    }
}


