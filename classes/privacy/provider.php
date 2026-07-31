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
 * Privacy provider for mod_aitrainingsim.
 *
 * @package    mod_aitrainingsim
 * @copyright  2026 LMS Hosting Services
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_aitrainingsim\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\helper;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;

/**
 * Privacy provider implementation.
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\plugin\provider,
    \core_privacy\local\request\core_userlist_provider {

    /**
     * Return metadata about data stored for users.
     *
     * @param collection $collection
     * @return collection
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table('aitrainingsim_attempts', [
            'userid'      => 'privacy:metadata:mod_aitrainingsim_attempts:userid',
            'timecreated' => 'privacy:metadata:mod_aitrainingsim_attempts:timecreated',
            'timefinished' => 'privacy:metadata:mod_aitrainingsim_attempts:timefinished',
            'score'       => 'privacy:metadata:mod_aitrainingsim_attempts:score',
            'responses'   => 'privacy:metadata:mod_aitrainingsim_attempts:responses',
        ], 'privacy:metadata:mod_aitrainingsim_attempts');

        $collection->add_external_location_link('lmsportal', [
            'siteid' => 'privacy:metadata:lmsportal',
        ], 'privacy:metadata:lmsportal');

        return $collection;
    }

    /**
     * Get the contexts containing data for the specified user.
     *
     * @param int $userid
     * @return contextlist
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        $contextlist = new contextlist();
        $contextlist->add_from_sql("
            SELECT ctx.id
              FROM {context} ctx
              JOIN {course_modules} cm ON cm.id = ctx.instanceid AND ctx.contextlevel = :ctxlevel
              JOIN {aitrainingsim} s   ON s.id  = cm.instance
              JOIN {aitrainingsim_attempts} a ON a.aitrainingsimid = s.id
             WHERE a.userid = :userid
        ", ['ctxlevel' => CONTEXT_MODULE, 'userid' => $userid]);
        return $contextlist;
    }

    /**
     * Get users in context.
     *
     * @param userlist $userlist
     */
    public static function get_users_in_context(userlist $userlist): void {
        $context = $userlist->get_context();
        if (!$context instanceof \context_module) {
            return;
        }
        $userlist->add_from_sql('userid', "
            SELECT a.userid
              FROM {aitrainingsim_attempts} a
              JOIN {aitrainingsim} s ON s.id = a.aitrainingsimid
              JOIN {course_modules} cm ON cm.instance = s.id
             WHERE cm.id = :cmid
        ", ['cmid' => $context->instanceid]);
    }

    /**
     * Export user data.
     *
     * @param approved_contextlist $contextlist
     */
    public static function export_user_data(approved_contextlist $contextlist): void {
        global $DB;
        foreach ($contextlist->get_contexts() as $context) {
            if (!$context instanceof \context_module) {
                continue;
            }
            $cm = get_coursemodule_from_id('aitrainingsim', $context->instanceid, 0, false, MUST_EXIST);
            $attempts = $DB->get_records('aitrainingsim_attempts', [
                'aitrainingsimid' => $cm->instance,
                'userid'          => $contextlist->get_user()->id,
            ]);
            writer::with_context($context)->export_data([], (object)['attempts' => array_values($attempts)]);
        }
    }

    /**
     * Delete all user data in listed contexts.
     *
     * @param approved_contextlist $contextlist
     */
    public static function delete_data_for_user(approved_contextlist $contextlist): void {
        global $DB;
        foreach ($contextlist->get_contexts() as $context) {
            if (!$context instanceof \context_module) {
                continue;
            }
            $cm = get_coursemodule_from_id('aitrainingsim', $context->instanceid, 0, false, MUST_EXIST);
            $DB->delete_records('aitrainingsim_attempts', [
                'aitrainingsimid' => $cm->instance,
                'userid'          => $contextlist->get_user()->id,
            ]);
        }
    }

    /**
     * Delete data for all users in a context.
     *
     * @param \context $context
     */
    public static function delete_data_for_all_users_in_context(\context $context): void {
        global $DB;
        if (!$context instanceof \context_module) {
            return;
        }
        $cm = get_coursemodule_from_id('aitrainingsim', $context->instanceid, 0, false, MUST_EXIST);
        $DB->delete_records('aitrainingsim_attempts', ['aitrainingsimid' => $cm->instance]);
    }

    /**
     * Delete data for a list of users in a context.
     *
     * @param approved_userlist $userlist
     */
    public static function delete_data_for_users(approved_userlist $userlist): void {
        global $DB;
        $context = $userlist->get_context();
        if (!$context instanceof \context_module) {
            return;
        }
        $cm = get_coursemodule_from_id('aitrainingsim', $context->instanceid, 0, false, MUST_EXIST);
        [$insql, $params] = $DB->get_in_or_equal($userlist->get_userids(), SQL_PARAMS_NAMED);
        $params['simid'] = $cm->instance;
        $DB->delete_records_select('aitrainingsim_attempts', "aitrainingsimid = :simid AND userid {$insql}", $params);
    }
}
