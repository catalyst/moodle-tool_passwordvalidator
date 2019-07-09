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
 * @package    tool_passwordvalidator
 * @copyright  Peter Burnett <peterburnett@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;
// Require validation library.
require_once(__DIR__.'/../../../config.php');
require_once('lib.php');

global $CFG;

if ($hassiteconfig) {

    $settings = new admin_settingpage('tool_passwordvalidator', get_string('pluginname', 'tool_passwordvalidator'));

    $ADMIN->add('tools', $settings);
    if (!during_initial_install()) {

        // Alert if using config template
        $name = get_config('tool_passwordvalidator', 'chosen_template');
        if (trim($name) != '') {
            // Construct the display text
            $text = get_string('passwordforcedconfig', 'tool_passwordvalidator') . $name;
            $text .= get_string('passwordconfigloc', 'tool_passwordvalidator');
            $text .= (__DIR__ . get_string('passwordconfigpath', 'tool_passwordvalidator', $name).'<br>');
            $text .= get_string("template$name", 'tool_passwordvalidator');
            
            // Add the control
            $templatedesc = $OUTPUT->notification($text, 'notifymessage');
            $settings->add(new admin_setting_heading('tool_passwordvalidator/template_heading', '', $templatedesc));
        }

        // IRAP Complexity Minimum
        $settings->add(new admin_setting_configcheckbox('tool_passwordvalidator/irap_complexity', get_string('passwordirapcomplexityname', 'tool_passwordvalidator'),
                    get_string('passwordirapcomplexitydesc', 'tool_passwordvalidator'), 1));

        $settings->add(new admin_setting_configtext('tool_passwordvalidator/simple_length_input', get_string('passwordirapcomplexitysimple', 'tool_passwordvalidator'),
                    get_string('passwordirapcomplexitysimpledesc', 'tool_passwordvalidator'), 13, PARAM_INT));

        $settings->add(new admin_setting_configtext('tool_passwordvalidator/complex_length_input', get_string('passwordirapcomplexitycomplex', 'tool_passwordvalidator'),
                    get_string('passwordirapcomplexitycomplexdesc', 'tool_passwordvalidator'), 10, PARAM_INT));

        // IRAP Not only numbers
        $settings->add(new admin_setting_configcheckbox('tool_passwordvalidator/irap_numbers', get_string('passwordirapnumbersname', 'tool_passwordvalidator'),
                    get_string('passwordirapnumbersdesc', 'tool_passwordvalidator'), 1));

        // Minimum dictionary word settings
        $settings->add(new admin_setting_configcheckbox('tool_passwordvalidator/dictionary_check', get_string('passworddictcheckname', 'tool_passwordvalidator'),
                    get_string('passworddictcheckdesc', 'tool_passwordvalidator'), 1));

        $settings->add(new admin_setting_configtext('tool_passwordvalidator/dictionary_check_file', get_string('passworddictcheckfilename', 'tool_passwordvalidator'),
                    get_string('passworddictcheckfiledesc', 'tool_passwordvalidator'), 'google-10000-english.txt', PARAM_RAW));

        // Sequential digits settings
        $settings->add(new admin_setting_configtext('tool_passwordvalidator/sequential_digits_input', get_string('passworddigitsinputname', 'tool_passwordvalidator'),
                    get_string('passworddigitsinputdesc', 'tool_passwordvalidator'), 2, PARAM_INT));

        // Repeated characters settings
        $settings->add(new admin_setting_configtext('tool_passwordvalidator/repeated_chars_input', get_string('passwordcharsinputname', 'tool_passwordvalidator'),
                    get_string('passwordcharsinputdesc', 'tool_passwordvalidator'), 2, PARAM_INT));

        // Personal information control
        $settings->add(new admin_setting_configcheckbox('tool_passwordvalidator/personal_info', get_string('passwordpersonalinfoname', 'tool_passwordvalidator'),
                    get_string('passwordpersonalinfodesc', 'tool_passwordvalidator'), 1));

        // Phrase blacklisting
        $settings->add(new admin_setting_configcheckbox('tool_passwordvalidator/phrase_blacklist', get_string('passwordphrasename', 'tool_passwordvalidator'),
                    get_string('passwordphrasedesc', 'tool_passwordvalidator'), 1));

        $settings->add(new admin_setting_configtextarea('tool_passwordvalidator/phrase_blacklist_input', get_string('passwordphraseinputname', 'tool_passwordvalidator'),
                    get_string('passwordphraseinputdesc', 'tool_passwordvalidator'), 'moodle', PARAM_RAW));

        // Password Change lockout period
        $settings->add(new admin_setting_configtext('tool_passwordvalidator/time_lockout_input', get_string('passwordlockoutinputname', 'tool_passwordvalidator'),
                    get_string('passwordlockoutinputdesc', 'tool_passwordvalidator'), 0, PARAM_INT));

        // Check against HaveIBeenPwned.com API
        $settings->add(new admin_setting_configcheckbox('tool_passwordvalidator/password_blacklist', get_string('passwordblacklistname', 'tool_passwordvalidator'),
                    get_string('passwordblacklistdesc', 'tool_passwordvalidator'), 1));

        // Panel for Displaying controls that are incorrect/misconfigured
        $configcheckdesc = config_checker();
        $configdesc = $OUTPUT->notification($configcheckdesc[0], $configcheckdesc[1]);
        $settings->add(new admin_setting_heading('tool_passwordvalidator/settings_heading', get_string('passwordsettingsheading', 'tool_passwordvalidator'), $configdesc));

        // Testing panel
        // Heading
        $settings->add(new admin_setting_heading('tool_passwordvalidator/testing_heading', get_string('passwordtesterheading', 'tool_passwordvalidator'),
                    get_string('passwordtesterheadingdesc', 'tool_passwordvalidator')));

        // Get current password configuration
        $testpassword = get_config('tool_passwordvalidator', 'password_test_field');
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
        $testerdesc = $OUTPUT->notification(get_string($message, 'tool_passwordvalidator').' '. $testervalidation, $type);

        $settings->add(new admin_setting_configtext('tool_passwordvalidator/password_test_field', get_string('passwordtestername', 'tool_passwordvalidator'),
                    $testerdesc, '', PARAM_RAW));
    }
}


