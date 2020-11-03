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
 * Check the number of event handlers in the old events API event queue.
 *
 * Buggy event handler code can cause the event queue to become blocked, so
 * this service is intended to check that the size of the queue stays within
 * reasonable limits.
 *
 * @package    local_nagios
 * @copyright  2014 University of Strathclyde
 * @author     Michael Aherne
 * @author     2020 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_nagios\nagios;

use local_nagios\service;
use local_nagios\status_result;
use local_nagios\thresholds;

defined('MOODLE_INTERNAL') || die();

class event_queue_service extends service {

    public function check_status(thresholds $thresholds, $params = array()) {
        global $DB;
        $count = $DB->count_records('events_queue_handlers');
        $result = new status_result();
        $result->status = $thresholds->check($count);
        $result->text = "$count events in queue";
        $result->perfdata = $count;

        return $result;
    }

}