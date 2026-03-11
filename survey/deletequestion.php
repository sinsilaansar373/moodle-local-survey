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
 * Delete a specific survey question.
 *
 * @package     local_survey
 * @copyright   2023 Your Name <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

$context = context_system::instance();
require_login();
require_capability('local_survey:manage', $context);

$id = required_param('id', PARAM_INT);

// Get survey ID before deletion so we can redirect back.
$question = $DB->get_record('local_survey_question', ['id' => $id], 'survey_id', MUST_EXIST);
$surveyid = $question->survey_id;

$DB->delete_records('local_survey_question', ['id' => $id]);

redirect(new moodle_url('/local/survey/editsurvey.php', ['id' => $surveyid]), get_string('deletequestion', 'local_survey'));