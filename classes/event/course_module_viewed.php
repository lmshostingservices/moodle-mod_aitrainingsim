<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <https://www.gnu.org/licenses/>.

/**
 * Event: course module viewed for mod_aitrainingsim.
 *
 * @package    mod_aitrainingsim
 * @copyright  2026 LMS Hosting Services
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_aitrainingsim\event;

/**
 * Course module viewed event.
 */
class course_module_viewed extends \core\event\course_module_viewed {
    /**
     * Set default properties for this event.
     */
    protected function init(): void {
        $this->data['objecttable'] = 'aitrainingsim';
        parent::init();
    }
}
