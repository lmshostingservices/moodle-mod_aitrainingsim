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
 * Library functions for mod_aitrainingsim.
 *
 * @package    mod_aitrainingsim
 * @copyright  2026 LMS Hosting Services
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Add a new aitrainingsim instance.
 *
 * @param stdClass $data Form data.
 * @param mod_aitrainingsim_mod_form|null $mform The form object (unused).
 * @return int New instance ID.
 */
function aitrainingsim_add_instance(stdClass $data, $mform = null): int {
    global $DB;
    $data->timecreated  = time();
    $data->timemodified = time();
    if (empty($data->language)) {
        $data->language = current_language();
    }
    $id = $DB->insert_record('aitrainingsim', $data);
    aitrainingsim_grade_item_update($data);
    return $id;
}

/**
 * Update an existing aitrainingsim instance.
 *
 * @param stdClass $data Form data.
 * @param mod_aitrainingsim_mod_form|null $mform The form object (unused).
 * @return bool True on success.
 */
function aitrainingsim_update_instance(stdClass $data, $mform = null): bool {
    global $DB;
    $data->timemodified = time();
    $data->id           = $data->instance;
    $DB->update_record('aitrainingsim', $data);
    aitrainingsim_grade_item_update($data);
    return true;
}

/**
 * Delete an aitrainingsim instance.
 *
 * @param int $id Instance ID.
 * @return bool True on success.
 */
function aitrainingsim_delete_instance(int $id): bool {
    global $DB;
    $DB->delete_records('aitrainingsim_attempts', ['aitrainingsimid' => $id]);
    $DB->delete_records('aitrainingsim_scenarios', ['aitrainingsimid' => $id]);
    $DB->delete_records('aitrainingsim', ['id' => $id]);
    aitrainingsim_grade_item_delete((object)['id' => $id]);
    return true;
}

/**
 * Return features supported by this module.
 *
 * @param string $feature FEATURE_* constant.
 * @return bool|null True/false/null for supported/unsupported/unknown.
 */
function aitrainingsim_supports(string $feature): ?bool {
    switch ($feature) {
        case FEATURE_GRADE_HAS_GRADE:
        case FEATURE_COMPLETION_HAS_RULES:
        case FEATURE_MOD_INTRO:
        case FEATURE_BACKUP_MOODLE2:
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        case FEATURE_GROUPS:
        case FEATURE_GROUPINGS:
            return false;
        default:
            return null;
    }
}

/**
 * Create or update the grade item for an aitrainingsim instance.
 *
 * @param stdClass $sim The activity record.
 * @param mixed $grades Optional array of grade objects keyed by userid.
 * @return int GRADE_UPDATE_OK.
 */
function aitrainingsim_grade_item_update(stdClass $sim, $grades = null): int {
    global $CFG;
    require_once($CFG->libdir . '/gradelib.php');

    $params = [
        'itemname' => $sim->name,
        'gradetype' => GRADE_TYPE_VALUE,
        'grademax'  => 100,
        'grademin'  => 0,
    ];
    if ($grades === 'reset') {
        $params['reset'] = true;
        $grades = null;
    }
    return grade_update('mod/aitrainingsim', $sim->course, 'mod', 'aitrainingsim', $sim->id, 0, $grades, $params);
}

/**
 * Delete grade item for an aitrainingsim instance.
 *
 * @param stdClass $sim The activity record.
 * @return int GRADE_UPDATE_OK.
 */
function aitrainingsim_grade_item_delete(stdClass $sim): int {
    global $CFG;
    require_once($CFG->libdir . '/gradelib.php');
    return grade_update('mod/aitrainingsim', $sim->course ?? 0, 'mod', 'aitrainingsim', $sim->id, 0, null, ['deleted' => 1]);
}

/**
 * Update grades in the gradebook from attempt records.
 *
 * @param stdClass $sim   The activity record.
 * @param int      $userid Optional: update only this user. 0 = all users.
 */
function aitrainingsim_update_grades(stdClass $sim, int $userid = 0): void {
    global $DB;

    $where = 'aitrainingsimid = :simid AND state = :state';
    $params = ['simid' => $sim->id, 'state' => 'complete'];
    if ($userid) {
        $where   .= ' AND userid = :userid';
        $params['userid'] = $userid;
    }
    $attempts = $DB->get_records_select('aitrainingsim_attempts', $where, $params, 'timefinished DESC');

    $grades = [];
    foreach ($attempts as $attempt) {
        if (isset($grades[$attempt->userid])) {
            continue; // Use the most recent completed attempt only.
        }
        $grades[$attempt->userid] = (object)[
            'userid'   => $attempt->userid,
            'rawgrade' => (float)$attempt->score,
        ];
    }
    aitrainingsim_grade_item_update($sim, $grades ?: null);
}
