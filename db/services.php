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
 * Web service function and service definitions.
 *
 * @package   tool_passwordvalidator
 * @copyright 2026 Jay Oswald <jayoswald@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'tool_passwordvalidator_check_password' => [
        'classname'     => \tool_passwordvalidator\external\check_password::class,
        'methodname'    => 'execute',
        'description'   => 'Validate a password against the configured password validator policy.',
        'type'          => 'read',
        'capabilities'  => 'tool/passwordvalidator:checkpassword',
        'ajax'          => true,
        'loginrequired' => true,
    ],
];

$services = [
    'Password validator' => [
        'functions'   => ['tool_passwordvalidator_check_password'],
        'restrictedusers' => 0,
        'enabled'     => 1,
        'shortname'   => 'tool_passwordvalidator',
    ],
];
