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
 * List of services defined in 'local_nagios'.
 *
 * @package    local_nagios
 * @copyright  2014 University of Strathclyde
 * @author     Michael Aherne
 * @author     2020 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$services = [
    'scheduled_task' => [
        'classname' => 'local_nagios\nagios\scheduled_task_service',
        'params' => [
            'task' => false,
        ],
    ],
    'adhoc_task' => [
        'classname' => 'local_nagios\nagios\adhoc_task_service',
    ],
    'event_queue' => [
        'classname' => 'local_nagios\nagios\event_queue_service',
    ],
    'cron' => [
        'classname' => 'local_nagios\nagios\cron_service',
    ],
    'log_task' => [
        'classname' => 'local_nagios\nagios\log_task_service',
        'params' => [
            'strtotime' => false,
        ],
    ],
    'errors_in_table' => [
        'classname' => 'local_nagios\nagios\errors_in_table_service',
        'params' => [
            'table' => false,
            'condition' => false,
        ],
    ],
];
