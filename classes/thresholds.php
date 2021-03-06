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
 * Helper class to set Nagios statuses depending on thresholds.
 *
 * @package    local_nagios
 * @copyright  2014 University of Strathclyde
 * @author     Michael Aherne
 * @author     2020 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_nagios;

defined('MOODLE_INTERNAL') || die();

class thresholds {

    public $warning;
    public $critical;

    public function __construct(threshold $warning = null, threshold $critical = null) {
        $this->warning = $warning;
        $this->critical = $critical;
    }

    public function check($value) {
        if (!empty($this->critical) && $this->critical->check($value)) {
            return service::NAGIOS_STATUS_CRITICAL;
        }
        if (!empty($this->warning) && $this->warning->check($value)) {
            return service::NAGIOS_STATUS_WARNING;
        }
        return service::NAGIOS_STATUS_OK;
    }
}