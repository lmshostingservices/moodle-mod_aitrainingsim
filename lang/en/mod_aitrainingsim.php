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
 * Language strings for mod_aitrainingsim.
 *
 * @package    mod_aitrainingsim
 * @copyright  2026 LMS Hosting Services
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname']           = 'AI Training Simulation';
$string['modulename']           = 'AI Training Simulation';
$string['modulenameplural']     = 'AI Training Simulations';
$string['modulename_help']      = 'Create immersive AI-generated workplace scenario simulations. ' .
    'GPT-4o builds branching decision steps; DALL-E 3 paints each scene. ' .
    'Students navigate a split-screen simulation and receive a personalised skill-radar debrief.';
$string['pluginadministration'] = 'AI Training Simulation administration';

// Activity settings.
$string['scenariotitle']        = 'Scenario title';
$string['scenariotitle_help']   = 'Displayed to students as the activity name.';
$string['jobrole']              = 'Job role';
$string['jobrole_help']         = 'The workplace role the student will inhabit (e.g. Customer Service Officer).';
$string['workplacesetting']     = 'Workplace setting';
$string['workplacesetting_help'] = 'Physical or digital environment — office, call centre, warehouse, etc.';
$string['learningobjective']    = 'Learning objective';
$string['learningobjective_help'] = 'Core skill or competency the simulation should develop.';
$string['difficultylevel']      = 'Difficulty level';
$string['difficultylevel_help'] = 'Adjusts scenario complexity and response option subtlety.';
$string['numsteps']             = 'Number of steps';
$string['numsteps_help']        = 'Decision points in the simulation. Recommended: 3–12.';
$string['language']             = 'Language';
$string['language_help']        = 'Locale for generated dialogue. Defaults to the Moodle site language.';
$string['passingscore']         = 'Passing score (%)';
$string['passingscore_help']    = 'Minimum percentage of correct choices to mark activity completion.';

// Difficulty levels.
$string['difficulty_beginner']     = 'Beginner';
$string['difficulty_intermediate'] = 'Intermediate';
$string['difficulty_advanced']     = 'Advanced';

// Views.
$string['startsimlulation']     = 'Start simulation';
$string['resumesimulation']     = 'Resume simulation';
$string['viewdebrief']          = 'View debrief';
$string['regeneratescenario']   = 'Regenerate scenario';
$string['scenariogenerating']   = 'Generating scenario — this may take up to 30 seconds…';
$string['scenarionogenerated']  = 'No scenario has been generated yet. Save the activity settings to trigger generation.';
$string['stepof']               = 'Step {$a->current} of {$a->total}';
$string['yourscore']            = 'Your score: {$a}%';
$string['debrieftitle']         = 'Simulation Debrief';
$string['debriefskillradar']    = 'Skill Radar';
$string['simulationcomplete']   = 'Simulation complete';

// Credits.
$string['creditusage']          = 'Credit usage';
$string['creditcost']           = 'Approx. {$a} credits per simulation generation';
$string['nocredits']            = 'Insufficient LMS Labs credits. Please top up via the LMS Labs portal.';

// Privacy.
$string['privacy:metadata:mod_aitrainingsim_attempts'] = 'Stores each student\'s simulation attempt.';
$string['privacy:metadata:mod_aitrainingsim_attempts:userid'] = 'The user who made the attempt.';
$string['privacy:metadata:mod_aitrainingsim_attempts:timecreated'] = 'When the attempt was started.';
$string['privacy:metadata:mod_aitrainingsim_attempts:timefinished'] = 'When the attempt was completed.';
$string['privacy:metadata:mod_aitrainingsim_attempts:score'] = 'The percentage score achieved.';
$string['privacy:metadata:mod_aitrainingsim_attempts:responses'] = 'JSON-encoded student responses for each simulation step.';
$string['privacy:metadata:lmsportal'] = 'Simulation generation requests are sent to the LMS Labs portal API. ' .
    'Only the site ID and credit count are transmitted; no personal student data leaves the site.';

// Capabilities.
$string['aitrainingsim:addinstance']  = 'Add a new AI Training Simulation activity';
$string['aitrainingsim:view']         = 'View AI Training Simulation';
$string['aitrainingsim:attempt']      = 'Attempt an AI Training Simulation';
$string['aitrainingsim:viewreports']  = 'View simulation attempt reports';
$string['aitrainingsim:regenerate']   = 'Regenerate simulation scenarios';
