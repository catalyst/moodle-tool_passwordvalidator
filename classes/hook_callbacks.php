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

namespace tool_passwordvalidator;

use core\hook\check_password_policy;
use core\hook\check_password_compromised;
/**
 * Callbacks for hooks.
 *
 * @package    tool_passwordvalidator
 * @copyright  2025 Dustin Huynh <dustinhuynh@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {
    /**
     * Listener for the check_password_policy hook.
     *
     * @param check_password_policy $hook
     */
    public static function check_password_policy(check_password_policy $hook): void {
        global $CFG;
        if (get_config('tool_passwordvalidator', 'enable_plugin')) {
            require_once($CFG->dirroot . '/admin/tool/passwordvalidator/locallib.php');
            // If plugin is enabled, execute validation.
            $error = tool_passwordvalidator_password_validate($hook->password, $hook->user, $hook->compcheck);
            if ($error) {
                $hook->add_errors($error);
            }
        }
    }

    /**
     * Listener for the check_password_compromised hook.
     *
     * @param check_password_compromised $hook
     */
    public static function check_password_compromised(check_password_compromised $hook): void {
        global $CFG;
        if (get_config('tool_passwordvalidator', 'enable_plugin')) {
            require_once($CFG->dirroot . '/admin/tool/passwordvalidator/locallib.php');
            // If plugin is enabled, execute validation.
            $error = tool_passwordvalidator_password_compromised($hook->password);
            if ($error) {
                $hook->add_error($error);
            }
        }
    }
}
