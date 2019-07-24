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

global $CFG;

if ($hassiteconfig) {

    // Create validator category for page and external page
    $ADMIN->add('tools', new admin_category('validator', get_string('pluginname', 'tool_passwordvalidator')));

    // Add External admin page for validation
    $ADMIN->add('validator', new admin_externalpage('tool_passwordvalidator_form',
    get_string('testpasswordpagestring', 'tool_passwordvalidator'),
    new moodle_url('/admin/tool/passwordvalidator/test_password.php')));

    // Add main plugin configuration page
    $settings = new admin_settingpage('validatorsettings', get_string('testpasswordpage', 'tool_passwordvalidator'));
    $ADMIN->add('validator', $settings);

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

        // Plugin on/off control
        $settings->add(new admin_setting_configcheckbox('tool_passwordvalidator/enable_plugin', get_string('passwordenablename', 'tool_passwordvalidator'),
                    get_string('passwordenabledesc', 'tool_passwordvalidator'), 0));

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
                    get_string('passworddictcheckfiledesc', 'tool_passwordvalidator'), 'google-10000-english.txt', PARAM_FILE));

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
                    get_string('passwordphraseinputdesc', 'tool_passwordvalidator'), 'moodle', PARAM_TEXT));

        // Password Change lockout period
        $settings->add(new admin_setting_configduration('tool_passwordvalidator/time_lockout_input', get_string('passwordlockoutinputname', 'tool_passwordvalidator'),
                    get_string('passwordlockoutinputdesc', 'tool_passwordvalidator'), DAYSECS, MINSECS));

        // Check against HaveIBeenPwned.com API
        $settings->add(new admin_setting_configcheckbox('tool_passwordvalidator/password_blacklist', get_string('passwordblacklistname', 'tool_passwordvalidator'),
                    get_string('passwordblacklistdesc', 'tool_passwordvalidator'), 1));
    }
}


