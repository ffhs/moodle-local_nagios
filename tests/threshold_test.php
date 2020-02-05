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
 * Threshold test class.
 *
 * @package    local_nagios
 * @copyright  2014 University of Strathclyde
 * @author     Michael Aherne
 * @author     2020 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_nagios\threshold;

defined('MOODLE_INTERNAL') || die();

class threshold_testcase extends advanced_testcase {

    public function test_defaults() {
        $threshold1 = new threshold();
        $this->assertEquals(null, $threshold1->start);
        $this->assertFalse($threshold1->startinfinity);
        $this->assertEquals(null, $threshold1->end);
        $this->assertFalse($threshold1->endinfinity);
        $this->assertEquals(threshold::OUTSIDE, $threshold1->alerton);

        $threshold2 = new threshold(1, 10);
        $this->assertEquals(1, $threshold2->start);
        $this->assertEquals(10, $threshold2->end);

        $threshold3 = new threshold(-INF, 10);
        $this->assertTrue($threshold3->startinfinity);
        $this->assertEquals(10, $threshold3->end);
    }

    public function test_check() {
        $threshold1 = new threshold(0, 10);
        $this->assertTrue($threshold1->check(50));
        $this->assertFalse($threshold1->check(5));

        $threshold2 = new threshold(-INF, 500, threshold::INSIDE);
        $this->assertTrue($threshold2->check(0));
    }

    public function test_parse_range() {
        $t1 = threshold::from_string('10');
        $this->assertEquals(10, $t1->end);
        $this->assertFalse($t1->startinfinity);

        $t2 = threshold::from_string('~:10');
        $this->assertEquals(10, $t2->end);
        $this->assertTrue($t2->startinfinity);

        $t3 = threshold::from_string('@~:10');
        $this->assertEquals(10, $t3->end);
        $this->assertTrue($t3->startinfinity);
        $this->assertEquals(threshold::INSIDE, $t3->alerton);

    }

}