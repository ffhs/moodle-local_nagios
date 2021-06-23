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
 * Helper class to get string and check thresholds.
 *
 * @package    local_nagios
 * @copyright  2014 University of Strathclyde
 * @author     Michael Aherne
 * @author     2020 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_nagios;

defined('MOODLE_INTERNAL') || die();

class threshold {

    const OUTSIDE = 0;
    const INSIDE = 1;

    public $start;
    public $startinfinity = false;
    public $end;
    public $endinfinity = false;
    public $alerton = self::OUTSIDE;

    public function __construct($start = 0, $end = 0, $alerton = self::OUTSIDE) {
        $this->start = $start;
        $this->end = $end;
        $this->alerton = $alerton;

        if ($start == -INF) {
            $this->startinfinity = true;
        }

        if ($end == INF) {
            $this->endinfinity = true;
        }

    }

    /**
     * Should the given value raise an alert?
     *
     * This is ported from https://github.com/Elbandi/nagios-plugins/blob/master/lib/utils_base.c
     *
     * @param float $value
     * @return bool
     */
    public function check($value) {
        $no = false;
        $yes = true;

        if ($this->alerton == self::INSIDE) {
            $no = true;
            $yes = false;
        }

        if (!$this->endinfinity && !$this->startinfinity) {
            if ($this->start <= $value && $value < $this->end) {
                return $no;
            } else {
                return $yes;
            }
        }

        if (!$this->startinfinity && $this->endinfinity) {
            if ($this->start <= $value) {
                return $no;
            } else {
                return $yes;
            }
        }

        if ($this->startinfinity && !$this->endinfinity) {
            if ($value <= $this->end) {
                return $no;
            } else {
                return $yes;
            }
        } else {
            return $no;
        }
    }

    public static function from_string($string) {
        $result = new threshold();

        if ($string[0] == '@') {
            $result->alerton = self::INSIDE;
            $string = substr($string, 1);
        }

        $endstr = strstr($string, ':');
        if ($endstr !== false) {
            if ($string[0] == '~') {
                $result->startinfinity = true;
            } else {
                $start = floatval($string);
                $result->start = $start;
            }
            $endstr = substr($endstr, 1);
        } else {
            $endstr = $string;
        }

        $end = floatval($endstr);
        if (!empty($endstr)) {
            $result->end = $end;
        }

        if ($result->startinfinity || $result->endinfinity || $result->start <= $result->end) {
            return $result;
        }

        return;
    }

}
