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
 * Check the number of error in a given table.
 *
 * @package    local_nagios
 * @copyright  2014 University of Strathclyde
 * @author     Michael Aherne
 * @author     2021 Sascha Vogel, Fernfachhochschule Schweiz (FFHS) <sascha.vogel@ffhs.ch>
 * @author     2021 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_nagios\nagios;

use local_nagios\status_result;
use local_nagios\thresholds;
use local_nagios\service;

defined('MOODLE_INTERNAL') || die();

class errors_in_table_service extends service {

    public function check_status(thresholds $thresholds, $params = []): status_result {
        global $DB;

        $table = $params['table'];
        if (!$this->validate_table($table)) {
            $result = new status_result();
            $result->status = service::NAGIOS_STATUS_CRITICAL;
            $result->text = "Invalid table \"$table\"";

            return $result;
        }

        $condition = $params['condition'];
        if (!$this->validate_condition($condition)) {
            $result = new status_result();
            $result->status = service::NAGIOS_STATUS_CRITICAL;
            $result->text = "Invalid condition \"$table\"";

            return $result;
        }

        $count = $DB->count_records_sql('SELECT count(*) FROM {' . $table . '} WHERE ' . $params['condition']);

        $result = new status_result();
        $result->status = $thresholds->check($count);
        $result->text = "$count errors in table \"$table\"";
        $result->perfdata = $count;

        return $result;
    }

    /**
     * Checks if the table name follows the conventions (https://docs.moodle.org/dev/XMLDB_editor#Conventions) and
     * if table exists in the database.
     *
     * @param string $table
     * @return bool
     */
    protected function validate_table(string $table): bool {
        global $DB;

        if (!preg_match('/[a-z0-9_]+/', $table)) {
            return false;
        }

        $dbman = $DB->get_manager();
        if (!$dbman->table_exists($table)) {
            return false;
        }

        return true;
    }

    /**
     * Checks if the condition is a valid where statement.
     *
     * @param $condition
     * @return bool
     */
    private function validate_condition($condition): bool {
        global $DB;

        if (preg_match('/^WHERE/i', $condition)) {
            return false;
        }

        return true;
    }
}
