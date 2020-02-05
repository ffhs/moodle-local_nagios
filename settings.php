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
 * Settings and admin page details.
 *
 * @package    local_nagios
 * @copyright  2014 University of Strathclyde
 * @author     Michael Aherne
 * @author     2020 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree && $ADMIN->locate('localplugins')) {
    $ADMIN->add('localplugins', new admin_externalpage('local_nagios', get_string('pluginname', 'local_nagios'),
        new moodle_url('/local/nagios/admin.php')));
} else {
    if ($ADMIN->locate('localplugins') && !$ADMIN->locate('local_nagios')) {
        $ADMIN->add('localplugins', new admin_externalpage('local_nagios', get_string('pluginname', 'local_nagios'),
            new moodle_url('/local/nagios/admin.php')));
    }
}