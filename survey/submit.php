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
 * Handle survey submission.
 *
 * @package     local_survey
 * @copyright   2023 Your Name <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

$context = context_system::instance();
require_login();
require_capability('local/survey:submit', $context);

$answers = optional_param_array('answer', [], PARAM_TEXT);

if (!empty($answers)) {
    foreach ($answers as $qid => $value) {
        $qid = clean_param($qid, PARAM_INT);

        // Skip if already answered.
        $existing = $DB->record_exists('local_survey_answers', [
            'q_id' => $qid,
            'user_id' => $USER->id
        ]);

        if (!$existing) {
            $record = new stdClass();
            $record->q_id = $qid;
            $record->a_id = $value;
            $record->user_id = $USER->id;
            $record->timecreated = time();
            $DB->insert_record('local_survey_answers', $record);
        }
    }
    redirect(new moodle_url('/local/survey/survey_studentview.php'), get_string('savesuccess', 'local_survey'), null, \core\output\notification::NOTIFY_SUCCESS);
}
else {
    redirect(new moodle_url('/local/survey/survey_studentview.php'));
}