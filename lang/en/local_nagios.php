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
 * Strings for component 'local_nagios'.
 *
 * @package    local_nagios
 * @copyright  2014 University of Strathclyde
 * @author     Michael Aherne
 * @author     2020 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Nagios monitoring';

$string['local_nagios:scheduled_task:description'] =
    'Check the time since the given scheduled task last completed. Parameter "task" is full classname of task class.';
$string['local_nagios:scheduled_task:variable'] = 'Number of seconds since last run';
$string['local_nagios:event_queue:description'] = 'Check the number of event handlers in the old events system.';
$string['local_nagios:event_queue:variable'] = 'Number of event handlers';
$string['local_nagios:adhoc_task:description'] = 'Check the number of ad-hoc tasks waiting to be processed.';
$string['local_nagios:adhoc_task:variable'] = 'The number of ad-hoc tasks.';
$string['local_nagios:cron:description'] = 'Checks that the cron job is running properly by checking the last time it was run.';
$string['local_nagios:cron:variable'] = 'Number of seconds since last run';
$string['local_nagios:log_task:description'] =
    'Checks the number of failed tasks (scheduled/ad-hoc) in the last 5 minutes (default). Parameter "strtotime" is the textual datetime to be used instead of the default.';
$string['local_nagios:log_task:variable'] = 'Number of failed tasks';

$string['servicelist_help'] = 'To monitor one of these services, create a new command in your Nagios (NRPE) configuration, e.g.

    // CentOS
    command[check_moodle]=/usr/lib64/nagios/plugins/check_moodle -p=$ARG1$ -s=$ARG2$ -w=$ARG3$ -c=$ARG4$ -t=$ARG5$ -x=$ARG6$

    // Ubuntu
    define command {
        command_name    check_moodle
        command_line    /usr/lib/nagios/plugins/check_moodle -p=$ARG1$ -s=$ARG2$ -w=$ARG3$ -c=$ARG4$ -t=$ARG5$ -x=$ARG6$
    }

This command can then be used in Nagios service definitions';
$string['clicheck_help'] = 'This scripts runs a Nagios status check';
$string['parameters_help'] = ' with the following parameters:

    -p : the frankenstyle name of the plugin, shown in the first column
    -s : the name of the service, shown in the second column
    -w : the warning threshold
    -c : the critical threshold
    -t : the name of the scheduled task (only needed in scheduled_task service)
    -x : the textual datetime description (only needed in task_log service)

The last column "Variable" describes the quantity that will be compared against the warning and critical
thresholds to determine the service status.';
