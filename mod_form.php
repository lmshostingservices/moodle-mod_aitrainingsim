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
 * Activity settings form for mod_aitrainingsim.
 *
 * @package    mod_aitrainingsim
 * @copyright  2026 LMS Hosting Services
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

/**
 * Activity module settings form.
 */
class mod_aitrainingsim_mod_form extends moodleform_mod {
    /**
     * Define the form elements.
     */
    public function definition(): void {
        $mform = $this->_form;

        // Section: Scenario configuration.
        $mform->addElement('header', 'scenariohdr', get_string('pluginname', 'mod_aitrainingsim'));

        $mform->addElement('text', 'jobrole', get_string('jobrole', 'mod_aitrainingsim'), ['size' => 80]);
        $mform->setType('jobrole', PARAM_TEXT);
        $mform->addHelpButton('jobrole', 'jobrole', 'mod_aitrainingsim');
        $mform->addRule('jobrole', null, 'required', null, 'client');

        $mform->addElement('text', 'workplacesetting', get_string('workplacesetting', 'mod_aitrainingsim'), ['size' => 80]);
        $mform->setType('workplacesetting', PARAM_TEXT);
        $mform->addHelpButton('workplacesetting', 'workplacesetting', 'mod_aitrainingsim');
        $mform->addRule('workplacesetting', null, 'required', null, 'client');

        $mform->addElement('textarea', 'learningobjective', get_string('learningobjective', 'mod_aitrainingsim'),
            ['rows' => 4, 'cols' => 80]);
        $mform->setType('learningobjective', PARAM_TEXT);
        $mform->addHelpButton('learningobjective', 'learningobjective', 'mod_aitrainingsim');
        $mform->addRule('learningobjective', null, 'required', null, 'client');

        $difficulties = [
            'beginner'     => get_string('difficulty_beginner', 'mod_aitrainingsim'),
            'intermediate' => get_string('difficulty_intermediate', 'mod_aitrainingsim'),
            'advanced'     => get_string('difficulty_advanced', 'mod_aitrainingsim'),
        ];
        $mform->addElement('select', 'difficultylevel', get_string('difficultylevel', 'mod_aitrainingsim'), $difficulties);
        $mform->setDefault('difficultylevel', 'intermediate');
        $mform->addHelpButton('difficultylevel', 'difficultylevel', 'mod_aitrainingsim');

        $mform->addElement('text', 'numsteps', get_string('numsteps', 'mod_aitrainingsim'), ['size' => 5]);
        $mform->setType('numsteps', PARAM_INT);
        $mform->setDefault('numsteps', 6);
        $mform->addHelpButton('numsteps', 'numsteps', 'mod_aitrainingsim');
        $mform->addRule('numsteps', null, 'required', null, 'client');

        // Language: populate with installed Moodle language packs.
        $languages = array_merge(['' => get_string('sitedefault', 'admin')], get_string_manager()->get_list_of_translations());
        $mform->addElement('select', 'language', get_string('language', 'mod_aitrainingsim'), $languages);
        $mform->setDefault('language', '');
        $mform->addHelpButton('language', 'language', 'mod_aitrainingsim');

        $mform->addElement('text', 'passingscore', get_string('passingscore', 'mod_aitrainingsim'), ['size' => 5]);
        $mform->setType('passingscore', PARAM_INT);
        $mform->setDefault('passingscore', 60);
        $mform->addHelpButton('passingscore', 'passingscore', 'mod_aitrainingsim');

        // Standard course module elements (grade, completion, etc.).
        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }

    /**
     * Validate form data.
     *
     * @param array $data   Submitted form data.
     * @param array $files  Uploaded files (unused).
     * @return array Errors keyed by field name.
     */
    public function validation($data, $files): array {
        $errors = parent::validation($data, $files);
        if (isset($data['numsteps']) && ($data['numsteps'] < 1 || $data['numsteps'] > 20)) {
            $errors['numsteps'] = get_string('error', 'core');
        }
        if (isset($data['passingscore']) && ($data['passingscore'] < 0 || $data['passingscore'] > 100)) {
            $errors['passingscore'] = get_string('error', 'core');
        }
        return $errors;
    }
}
