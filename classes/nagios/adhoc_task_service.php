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
 * Check the number of ad-hoc tasks waiting to be processed.
 *
 * @package    local_nagios
 * @copyright  2014 University of Strathclyde
 * @author     Michael Aherne
 * @author     2020 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_nagios\nagios;

use local_nagios\status_result;
use local_nagios\thresholds;
use local_nagios\service;

defined('MOODLE_INTERNAL') || die();

class adhoc_task_service extends service {

    public function check_status(thresholds $thresholds, $params = array()) {
        global $DB;
        $count = $DB->count_records('task_adhoc');
        $result = new status_result();
        $result->status = $thresholds->check($count);
        $result->text = "$count ad-hoc tasks in queue";

        return $result;
    }

}