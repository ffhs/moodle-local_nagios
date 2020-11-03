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
 * Version details.
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

class cron_service extends service {

    public function check_status(thresholds $thresholds, $params = array()) {
        global $CFG, $DB;

        if ($CFG->branch < 27) {
            $lastcron = $DB->get_field_sql('SELECT MAX(lastcron) FROM {modules}');
        } else {
            $lastcron = $DB->get_field_sql('SELECT MAX(lastruntime) FROM {task_scheduled}');
        }
        $currenttime = time();
        $difference = $currenttime - $lastcron;

        $result = new status_result();
        $result->status = $thresholds->check($difference);

        if (!$lastcron) {
            $result->text = "Cron has never run";
        } else {
            $result->text = "Cron last ran at " . date(DATE_RSS, $lastcron) . ", $difference seconds ago";
            $result->perfdata = 1;
        }

        return $result;
    }

}