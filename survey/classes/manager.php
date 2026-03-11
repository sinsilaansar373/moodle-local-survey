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

namespace local_survey;

defined('MOODLE_INTERNAL') || die();

/**
 * Manager class for local_survey plugin.
 *
 * @package     local_survey
 * @copyright   2023 Your Name <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class manager
{

    /**
     * Get all active surveys for a student.
     *
     * @return array
     */
    public static function get_active_surveys()
    {
        global $DB;
        $now = time();
        // Simplified raw SQL for clarity during refactor, will improve later.
        return $DB->get_records_select('local_survey', 'survey_from <= ? AND survey_to >= ?', [$now, $now]);
    }

    /**
     * Get questions for a specific survey.
     *
     * @param int $surveyid
     * @return array
     */
    public static function get_questions($surveyid)
    {
        global $DB;
        return $DB->get_records('local_survey_question', ['survey_id' => $surveyid]);
    }

    /**
     * Save a survey response.
     *
     * @param int $userid
     * @param int $qid
     * @param string $answer
     * @return int The new record ID
     */
    public static function save_response($userid, $qid, $answer)
    {
        global $DB;
        $record = new \stdClass();
        $record->user_id = $userid;
        $record->q_id = $qid;
        $record->a_id = $answer;
        $record->timecreated = time();
        return $DB->insert_record('local_survey_answers', $record);
    }
}