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
 * Thresholds test class.
 *
 * @package    local_nagios
 * @copyright  2014 University of Strathclyde
 * @author     Michael Aherne
 * @author     2020 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_nagios\threshold;
use local_nagios\thresholds;
use local_nagios\service;
class thresholds_testcase extends advanced_testcase {

    public function test_check() {
        $t1 = new thresholds();
        $t1->warning = new threshold(0, 10);
        $t1->critical = new threshold(0, 20);
        $this->assertEquals(service::NAGIOS_STATUS_CRITICAL, $t1->check(30));
        $this->assertEquals(service::NAGIOS_STATUS_WARNING, $t1->check(15));
        $this->assertEquals(service::NAGIOS_STATUS_OK, $t1->check(5));
    }

}