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
 * External function to check a password against the password validator policy.
 *
 * @package   tool_passwordvalidator
 * @copyright 2026 Jay Oswald <jayoswald@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_passwordvalidator\external;

defined('MOODLE_INTERNAL') || die();

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;

/**
 * External function check_password.
 */
class check_password extends external_api {
    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'password' => new external_value(PARAM_RAW, 'The password to validate', VALUE_REQUIRED),
            'userid'   => new external_value(PARAM_INT, 'Optional user ID to validate against personal info checks', VALUE_DEFAULT, 0),
        ]);
    }

    /**
     * Check whether the given password passes the configured password policy.
     *
     * @param string $password The password to validate.
     * @param int $userid Optional user ID.
     * @return array Result containing passed flag and any errors.
     */
    public static function execute(string $password, int $userid = 0): array {
        global $CFG;
        require_once($CFG->dirroot . '/admin/tool/passwordvalidator/locallib.php');

        $params = self::validate_parameters(self::execute_parameters(), [
            'password' => $password,
            'userid'   => $userid,
        ]);

        if ($params['userid'] > 0) {
            $context = \context_user::instance($params['userid']);
        } else {
            $context = \context_system::instance();
        }
        self::validate_context($context);
        require_capability('tool/passwordvalidator:checkpassword', $context);

        $user = new \stdClass();
        if ($params['userid'] > 0) {
            $user = \core_user::get_user($params['userid'], '*', MUST_EXIST);
        }

        $errors = tool_passwordvalidator_password_validate($params['password'], $user);

        return [
            'valid' => ($errors === ''),
            'errors' => $errors,
        ];
    }

    /**
     * Returns description of method return value.
     *
     * @return external_single_structure
     */
    public static function execute_returns(): external_single_structure {
        return new external_single_structure([
            'valid' => new external_value(PARAM_BOOL, 'True if the password passed all checks'),
            'errors' => new external_value(PARAM_RAW, 'Validation error messages, empty string if passed'),
        ]);
    }
}
