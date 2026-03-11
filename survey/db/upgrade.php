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
 * Upgrade script for local_survey.
 *
 * @package     local_survey
 * @category    upgrade
 * @copyright   2023 Your Name <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute upgrade steps.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_local_survey_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2023070303) {
        // Renaming old tables to new standard names if they exist.
        $tables_to_rename = [
            'customsurvey' => 'local_survey',
            'customsurvey_question' => 'local_survey_question',
            'student_answers' => 'local_survey_answers',
        ];

        foreach ($tables_to_rename as $oldname => $newname) {
            $table = new xmldb_table($oldname);
            if ($dbman->table_exists($table)) {
                $dbman->rename_table($table, $newname);
            }
        }

        upgrade_plugin_savepoint(true, 2023070303, 'local', 'survey');
    }

    return true;
}