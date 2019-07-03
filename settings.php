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
 * @copyright  Peter Burnett <peterburnett@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;
// Require validation library.
require_once(__DIR__.'/../../../config.php');
require_once('lib.php');
global $CFG;

if ($hassiteconfig) {

    $settings = new admin_settingpage('tool_password', get_string('pluginname', 'tool_password'));

    $ADMIN->add('tools', $settings);
    if (!during_initial_install()) {

        // IRAP Complexity Minimum
        $settings->add(new admin_setting_configcheckbox('tool_password/irap_complexity', get_string('passwordirapcomplexityname', 'tool_password'),
                    get_string('passwordirapcomplexitydesc', 'tool_password'), 1));

        $settings->add(new admin_setting_configtext('tool_password/simple_length_input', get_string('passwordirapcomplexitysimple', 'tool_password'),
                    get_string('passwordirapcomplexitysimpledesc', 'tool_password'), 13, PARAM_INT));

        $settings->add(new admin_setting_configtext('tool_password/complex_length_input', get_string('passwordirapcomplexitycomplex', 'tool_password'),
                    get_string('passwordirapcomplexitycomplexdesc', 'tool_password'), 10, PARAM_INT));

        // IRAP Not only numbers
        $settings->add(new admin_setting_configcheckbox('tool_password/irap_numbers', get_string('passwordirapnumbersname', 'tool_password'),
                    get_string('passwordirapnumbersdesc', 'tool_password'), 1));

        // Sequential digits settings.
        $settings->add(new admin_setting_configcheckbox('tool_password/sequential_digits', get_string('passworddigitsname', 'tool_password'),
                    get_string('passworddigitsdesc', 'tool_password'), 1));

        $settings->add(new admin_setting_configtext('tool_password/sequential_digits_input', get_string('passworddigitsinputname', 'tool_password'),
                    get_string('passworddigitsinputdesc', 'tool_password'), 2, PARAM_INT));

        // Repeated characters settings
        $settings->add(new admin_setting_configcheckbox('tool_password/repeated_chars', get_string('passwordcharsname', 'tool_password'),
                    get_string('passwordcharsdesc', 'tool_password'), 1));

        $settings->add(new admin_setting_configtext('tool_password/repeated_chars_input', get_string('passwordcharsinputname', 'tool_password'),
                    get_string('passwordcharsinputdesc', 'tool_password'), 2, PARAM_INT));

        // Personal information control
        $settings->add(new admin_setting_configcheckbox('tool_password/personal_info', get_string('passwordpersonalinfoname', 'tool_password'),
                    get_string('passwordpersonalinfodesc', 'tool_password'), 1));

        // Phrase blacklisting
        $settings->add(new admin_setting_configcheckbox('tool_password/phrase_blacklist', get_string('passwordphrasename', 'tool_password'),
                    get_string('passwordphrasedesc', 'tool_password'), 1));

        $settings->add(new admin_setting_configtextarea('tool_password/phrase_blacklist_input', get_string('passwordphraseinputname', 'tool_password'),
                    get_string('passwordphraseinputdesc', 'tool_password'), 'moodle', PARAM_RAW));

        // Password Change lockout period
        $settings->add(new admin_setting_configcheckbox('tool_password/time_lockout', get_string('passwordlockoutname', 'tool_password'),
                    get_string('passwordlockoutdesc', 'tool_password'), 1));

        $settings->add(new admin_setting_configtext('tool_password/time_lockout_input', get_string('passwordlockoutinputname', 'tool_password'),
                    get_string('passwordlockoutinputdesc', 'tool_password'), 0, PARAM_INT));

        // Check against HaveIBeenPwned.com API
        $settings->add(new admin_setting_configcheckbox('tool_password/password_blacklist', get_string('passwordblacklistname', 'tool_password'),
                    get_string('passwordblacklistdesc', 'tool_password'), 1));

        // Panel for Displaying controls that are incorrect/misconfigured
        $configcheckdesc = config_checker();
        $configdesc .= $OUTPUT->notification($configcheckdesc[0], $configcheckdesc[1]);
        $settings->add(new admin_setting_heading('tool_password/settings_heading', get_string('passwordsettingsheading', 'tool_password'), $configdesc));

        // Testing panel
        // Heading
        $settings->add(new admin_setting_heading('tool_password/testing_heading', get_string('passwordtesterheading', 'tool_password'),
                    get_string('passwordtesterheadingdesc', 'tool_password')));

        // Get current password configuration
        $testpassword = get_config('tool_password', 'password_test_field');
        $testerdesc = '';
        // Only check if not empty
        if ($testpassword != '') {
            $testervalidation = password_validate($testpassword, true);
        }

        if ((trim($testervalidation) == '') && (trim($testpassword) !== '')) {
            // If no validation errors and pass isnt empty
            $message = 'passwordtesterpass';
            $type = 'notifysuccess';
        } else if (trim($testpassword) == '') {
            // If password is empty, notify empty
            $message = 'passwordtesterempty';
            $type = 'notifymessage';
        } else if (trim($testervalidation) !== '') {
            $message = 'passwordtesterfail';
            $type = 'notifyerror';
        }
        $testerdesc .= $OUTPUT->notification(get_string($message, 'tool_password').' '. $testervalidation, $type);

        $settings->add(new admin_setting_configtext('tool_password/password_test_field', get_string('passwordtestername', 'tool_password'),
                    $testerdesc, '', PARAM_RAW));
    }
}


