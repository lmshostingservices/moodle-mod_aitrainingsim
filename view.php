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
 * View page for mod_aitrainingsim (student and teacher entry point).
 *
 * @package    mod_aitrainingsim
 * @copyright  2026 LMS Hosting Services
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_login();
require_once($CFG->dirroot . '/mod/aitrainingsim/lib.php');

$id     = optional_param('id', 0, PARAM_INT);      // Course module ID.
$simid  = optional_param('s', 0, PARAM_INT);        // Activity instance ID.

if ($id) {
    $cm     = get_coursemodule_from_id('aitrainingsim', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
    $sim    = $DB->get_record('aitrainingsim', ['id' => $cm->instance], '*', MUST_EXIST);
} else {
    $sim    = $DB->get_record('aitrainingsim', ['id' => $simid], '*', MUST_EXIST);
    $course = $DB->get_record('course', ['id' => $sim->course], '*', MUST_EXIST);
    $cm     = get_coursemodule_from_instance('aitrainingsim', $sim->id, $course->id, false, MUST_EXIST);
}

require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/aitrainingsim:view', $context);

// Log the view.
$event = \mod_aitrainingsim\event\course_module_viewed::create([
    'objectid' => $sim->id,
    'context'  => $context,
]);
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('aitrainingsim', $sim);
$event->trigger();

$PAGE->set_url('/mod/aitrainingsim/view.php', ['id' => $cm->id]);
$PAGE->set_title(format_string($sim->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($sim->name));

// Check for generated scenario.
$scenario = $DB->get_record('aitrainingsim_scenarios', ['aitrainingsimid' => $sim->id, 'status' => 'ready']);

if (!$scenario) {
    echo $OUTPUT->notification(get_string('scenarionogenerated', 'mod_aitrainingsim'), 'info');
    if (has_capability('mod/aitrainingsim:regenerate', $context)) {
        $regenerateurl = new moodle_url('/mod/aitrainingsim/generate.php', ['id' => $cm->id, 'sesskey' => sesskey()]);
        echo html_writer::link($regenerateurl, get_string('regeneratescenario', 'mod_aitrainingsim'),
            ['class' => 'btn btn-primary']);
    }
    echo $OUTPUT->footer();
    exit;
}

// Check for an in-progress or completed attempt.
$attempt = $DB->get_record_select('aitrainingsim_attempts',
    'aitrainingsimid = :simid AND userid = :userid',
    ['simid' => $sim->id, 'userid' => $USER->id],
    '*', IGNORE_MULTIPLE);

if ($attempt && $attempt->state === 'complete') {
    // Show debrief.
    echo html_writer::div(get_string('simulationcomplete', 'mod_aitrainingsim'), 'alert alert-success');
    $debriefurl = new moodle_url('/mod/aitrainingsim/debrief.php', ['attemptid' => $attempt->id]);
    echo html_writer::link($debriefurl, get_string('viewdebrief', 'mod_aitrainingsim'), ['class' => 'btn btn-primary']);
} else {
    // Start or resume.
    $simurl = new moodle_url('/mod/aitrainingsim/sim.php', ['id' => $cm->id]);
    $label  = $attempt ? get_string('resumesimulation', 'mod_aitrainingsim') : get_string('startsimlulation', 'mod_aitrainingsim');
    echo html_writer::link($simurl, $label, ['class' => 'btn btn-primary btn-lg']);
}

if (has_capability('mod/aitrainingsim:viewreports', $context)) {
    $reporturl = new moodle_url('/mod/aitrainingsim/report.php', ['id' => $cm->id]);
    echo ' ' . html_writer::link($reporturl, get_string('viewreports', 'mod_aitrainingsim'), ['class' => 'btn btn-secondary']);
}

echo $OUTPUT->footer();
