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
 * Renderer for component 'local_nagios'.
 *
 * @package    local_nagios
 * @copyright  2014 University of Strathclyde
 * @author     Michael Aherne
 * @author     2020 Adrian Perez, Fernfachhochschule Schweiz (FFHS) <adrian.perez@ffhs.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class local_nagios_renderer extends plugin_renderer_base {

    public function render_servicelist($servicelist) {
        $table = new html_table();
        $table->head = array('Plugin', 'Service name', 'Class', 'Description', 'Variable');
        foreach ($servicelist as $plugin => $pluginservices) {
            foreach ($pluginservices as $name => $pluginservice) {
                $row = new html_table_row(array($plugin, $name, $pluginservice['classname']));
                $row->cells[] = new html_table_cell(get_string("local_nagios:$name:description", $plugin));
                $row->cells[] = new html_table_cell(get_string("local_nagios:$name:variable", $plugin));
                $table->data[] = $row;
            }
        }
        return html_writer::table($table);
    }

}