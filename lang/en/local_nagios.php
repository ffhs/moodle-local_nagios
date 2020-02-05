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

$string['local_nagios:scheduled_task:description'] = 'Check the time since the given scheduled task last completed. Parameter "task" is full classname of task class.';
$string['local_nagios:scheduled_task:variable'] = 'Number of seconds since last run';
$string['local_nagios:event_queue:description'] = 'Check the number of event handlers in the old events system.';
$string['local_nagios:event_queue:variable'] = 'Number of event handlers';
$string['local_nagios:adhoc_task:description'] = 'Check the number of ad-hoc tasks waiting to be processed.';
$string['local_nagios:adhoc_task:variable'] = 'The number of ad-hoc tasks.';

$string['servicelist_help'] = 'To monitor one of these services, create a new command in your Nagios configuration, e.g.

    define command {
        command_name    check_moodle_myplugin_my_service
        command_line    /usr/lib/nagios/plugins/check_moodle -p=local_myplugin -s=my_service -w=10000 -c=20000
    }
    
    Examples:
    define command {
        command_name    check_moodle_local_nagios_scheduled_task_updates
        command_line    /usr/lib/nagios/plugins/check_moodle -p=local_nagios -s=scheduled_task -t=\\core\\task\\check_for_updates_task -w=10800 -c=14400
    }

with the following parameters:

* -p: the frankenstyle name of the plugin, shown in the first column
* -s: the name of the service, shown in the second column
* -t: the name of the scheduled task (only needed in scheduled_task service)
* -w: the warning threshold
* -c: the critical threshold

The last column describes the variable quantity that will be compared against the warning and critical
thresholds to determine the service status.

This command can then be used in Nagios service definitions.';