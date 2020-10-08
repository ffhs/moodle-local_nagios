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
 * Runs service check from Nagios command.
 *
 * @package    local_nagios
 * @copyright  2014 University of Strathclyde
 * @author     Michael Aherne
 * @author     2020 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_nagios\thresholds;
use local_nagios\threshold;
use local_nagios\service;
use local_nagios\nagios;

define('CLI_SCRIPT', 1);

require_once(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/clilib.php');

defined('MOODLE_INTERNAL') || die();

// Get cli options.
list($options, $unrecognized) = cli_get_params(
        array(
                'plugin'           => false,
                'service'          => false,
                'task'             => false,
                'warning'          => false,
                'critical'         => false,
                'help'             => false
        ),
        array(
                'h' => 'help',
                'p' => 'plugin',
                's' => 'service',
                't' => 'task',
                'w' => 'warning',
                'c' => 'critical'
        )
);

if ($options['help']) {
    print_help();
    exit(0);
}

if (empty($options['plugin']) || empty($options['service'])) {
    print_help();
    exit(0);
}

$plugin = $options['plugin'];
$service = $options['service'];
$task = $options['task'];
$warning = $options['warning'];
$critical = $options['critical'];

$nagios = new nagios();
$thresholds = new thresholds();

if ($options['warning']) {
    $thresholds->warning = threshold::from_string($options['warning']);
}
if ($options['critical']) {
    $thresholds->critical = threshold::from_string($options['critical']);
}

if (empty($thresholds->critical) && empty($thresholds->warning)) {
    $nagios->send_unknown('No valid thresholds given');
}

try {
    $service = service::get_service($plugin, $service);

    if (empty($service)) {
        $nagios->send_unknown('Unable to get service ' . $service . ' from ' . $plugin);
    }

    $params = cli_get_params($service->get_param_defs());
    if ($options['task']) {
        // We pass the task classname with forward slashes in the Nagios 'check_nrpe' arguments.
        $options['task'] = str_replace('/', '\\', $options['task']);
        $params[0]['task'] = $options['task'];
    }

    $status = $service->check_status($thresholds, $params[0]);

    if (is_null($status)) {
        $nagios->send_unknown('Service check returned no status');
    }

    switch ($status->status) {
        case 0:
            $nagios->send_good($status->text, $status->perfdata);
            break;
        case 1:
            $nagios->send_warning($status->text, $status->perfdata);
            break;
        case 2:
            $nagios->send_critical($status->text, $status->perfdata);
            break;
        case 3:
            $nagios->send_unknown($status->text);
            break;
        default:
            $nagios->send_unknown($status->text);
    }
} catch (Exception $e) {
    $nagios->send_unknown($e->getMessage());
}

function print_help() {
    echo get_string('clicheck_help', 'local_nagios') . get_string('parameters_help', 'local_nagios');
}