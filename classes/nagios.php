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
 * Returns a Nagios status code, used for automated monitoring
 *
 * Numeric value:   |   Status  |   Description
 * 0                |    OK     |   The plugin was able to check the service and it appeared to be functioning properly.
 * 1                |  Warning  |   The plugin was able to check the service, but it appeared to be above some "warning" threshold or did not appear to be working properly.
 * 2                |  Critical |   The plugin detected that either the service was not running or it was above some "critical" threshold.
 * 3                |  Unknown  |   The plugin was unable to determine the status of the service.
 *
 * @package    local_nagios
 * @copyright  2019 Peter Burnett <peterburnett@catalyst-au.net>
 * @author     2020 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_nagios;

defined('MOODLE_INTERNAL') || die();

class nagios {

    protected $now;

    public function __construct() {
        $format = '%b %d %H:%M:%S';
        $this->now = userdate(time(), $format);
    }
    /**
     * Sends a good Nagios response, with message.
     *
     * @param string  $msg the message to append the Nagios response.
     * @param int $status the status to append the Nagios response
     */
    public function send_good($msg, $status = service::NAGIOS_STATUS_OK) {
        printf ("OK: $msg (Checked $this->now) | status=$status \n");
        exit($status);
    }

    /**
     * Sends a warning Nagios response, with message.
     *
     * @param string $msg the message to append the Nagios response.
     * @param int $status the status to append the Nagios response
     */
    public function send_warning($msg, $status = service::NAGIOS_STATUS_WARNING) {
        printf ("WARNING: $msg (Checked $this->now) | status=$status \n");
        exit($status);
    }

    /**
     * Sends a critical Nagios response, with message.
     *
     * @param string $msg the message to append the Nagios response.
     * @param int $status the status to append the Nagios response
     */
    public function send_critical($msg, $status = service::NAGIOS_STATUS_CRITICAL) {
        printf ("CRITICAL: $msg (Checked $this->now) | status=$status \n");
        exit($status);
    }

    /**
     * Sends an unknown Nagios response, with message.
     *
     * @param string  $msg the message to append the Nagios response.
     * @param int $status the status to append the Nagios response
     */
    public function send_unknown($msg, $status = service::NAGIOS_STATUS_UNKNOWN) {
        printf("UNKNOWN: $msg | status=$status \n");
        exit($status);
    }
}