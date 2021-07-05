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
 * Check the number of failed tasks.
 *
 * @package    local_nagios
 * @copyright  2014 University of Strathclyde
 * @author     Michael Aherne
 * @author     2021 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_nagios\nagios;

use local_nagios\status_result;
use local_nagios\thresholds;
use local_nagios\service;

defined('MOODLE_INTERNAL') || die();

class log_task_service extends service {

    public function check_status(thresholds $thresholds, $params = array()) {
        global $DB;

        $count = 0;
        $records = $DB->get_records('task_log', ['result' => 1]);

        $timediff = strtotime('-5 minutes');
        if (!empty($params['strtotime'])) {
            $timediff = strtotime($params['strtotime']);
        }

        foreach ($records as $record) {
            if ($record->timeend > $timediff) {
                $count++;
            }
        }

        $result = new status_result();
        $result->status = $thresholds->check($count);
        $result->text = "$count tasks has failed";
        $result->perfdata = $count;

        return $result;
    }
}
