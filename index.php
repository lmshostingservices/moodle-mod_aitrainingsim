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
 * List all AI Training Simulation instances in a course.
 *
 * @package    mod_aitrainingsim
 * @copyright  2026 LMS Hosting Services
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');

$id = required_param('id', PARAM_INT);

$course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);
require_course_login($course);
$PAGE->set_pagelayout('incourse');

$context = context_course::instance($course->id);
require_capability('mod/aitrainingsim:view', $context);

$PAGE->set_url('/mod/aitrainingsim/index.php', ['id' => $id]);
$PAGE->set_title(format_string($course->shortname) . ': ' . get_string('modulenameplural', 'mod_aitrainingsim'));
$PAGE->set_heading(format_string($course->fullname));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('modulenameplural', 'mod_aitrainingsim'));

$simulations = get_all_instances_in_course('aitrainingsim', $course);
if (!$simulations) {
    notice(get_string('thereareno', 'moodle', get_string('modulenameplural', 'mod_aitrainingsim')),
        new moodle_url('/course/view.php', ['id' => $course->id]));
}

$table = new html_table();
$table->head  = [get_string('name'), get_string('jobrole', 'mod_aitrainingsim')];
$table->align = ['left', 'left'];

foreach ($simulations as $sim) {
    $url  = new moodle_url('/mod/aitrainingsim/view.php', ['id' => $sim->coursemodule]);
    $link = html_writer::link($url, format_string($sim->name));
    $table->data[] = [$link, format_string($sim->jobrole)];
}

echo html_writer::table($table);
echo $OUTPUT->footer();
