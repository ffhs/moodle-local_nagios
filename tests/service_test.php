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
 * Service API test class.
 *
 * @package    local_nagios
 * @copyright  2014 University of Strathclyde
 * @author     Michael Aherne
 * @author     2020 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use \local_nagios\service;

defined('MOODLE_INTERNAL') || die();

class local_nagios_service_testcase extends advanced_testcase {

    public function test_service_list() {
        $list = service::service_list();
        $this->assertArrayHasKey('local_nagios', $list);
        $coreservices = $list['local_nagios'];
        $this->assertArrayHasKey('scheduled_task', $coreservices);
    }

    public function test_get_services() {
        $services = service::get_services('local_nagios');
        $this->assertNotNull($services);
        $this->assertArrayHasKey('scheduled_task', $services);

        $services = service::get_services('non_existant');
        $this->assertNull($services);

    }

    public function test_get_service() {
        $service = service::get_service('local_nagios', 'scheduled_task');
        $this->assertInstanceOf('local_nagios\service', $service);

        $this->expectException('local_nagios\invalid_service_exception');
        service::get_service('local_nagios', 'non_existent');
    }

}
