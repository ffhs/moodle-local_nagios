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
 * Check the time since the given scheduled task last completed.
 * Parameter "task" is full classname of task class.
 *
 * @package    local_nagios
 * @copyright  2014 University of Strathclyde
 * @author     Michael Aherne
 * @author     2020 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_nagios\nagios;

use local_nagios\invalid_service_exception;
use local_nagios\status_result;
use local_nagios\thresholds;
use local_nagios\service;

defined('MOODLE_INTERNAL') || die();

class scheduled_task_service extends \local_nagios\service {

    public function check_status(thresholds $thresholds, $params = array()) {
        if (empty($params['task'])) {
            throw new invalid_service_exception("Task parameter required");
        }

        if (! $task = \core\task\manager::get_scheduled_task($params['task'])) {
            throw new invalid_service_exception("Task not found");
        }

        $result = new status_result();

        $lastrun = $task->get_last_run_time();

        if (!$lastrun) {
            $result->text = 'Task has never run';
            $result->status = service::NAGIOS_STATUS_UNKNOWN;
        } else {
            $timeelapsed = time() - $lastrun;
            $result->status = $thresholds->check($timeelapsed);
            $result->text = "Last ran at " . date(DATE_RSS, $lastrun) . ", $timeelapsed seconds ago";
            $result->perfdata = 1;
        }

        return $result;
    }

}