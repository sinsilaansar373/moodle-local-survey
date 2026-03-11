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
 * View answers for a survey.
 *
 * @package     local_survey
 * @copyright   2023 Your Name <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

$id = required_param('id', PARAM_INT);
$context = context_system::instance();
require_login();
require_capability('local/survey:viewresponses', $context);

$url = new moodle_url('/local/survey/viewsurveyanswer.php', ['id' => $id]);
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('viewanswers', 'local_survey'));
$PAGE->set_heading(get_string('viewanswers', 'local_survey'));

echo $OUTPUT->header();

$survey = $DB->get_record('local_survey', ['id' => $id], '*', MUST_EXIST);
$questions = $DB->get_records('local_survey_question', ['survey_id' => $id]);

$data = [
    'survey_name' => $survey->survey_name,
    'questions' => [],
];

foreach ($questions as $q) {
    $questionobj = new stdClass();
    $questionobj->text = $q->survey_question;
    $questionobj->responses = [];

    $responses = $DB->get_records_sql("
        SELECT sa.id, sa.a_id, u.firstname, u.lastname 
        FROM {local_survey_answers} sa 
        JOIN {user} u ON u.id = sa.user_id 
        WHERE sa.q_id = ?", [$q->id]);

    foreach ($responses as $r) {
        $questionobj->responses[] = [
            'username' => fullname($r),
            'answer' => $r->a_id,
        ];
    }

    $data['questions'][] = $questionobj;
}

echo $OUTPUT->render_from_template('local_survey/viewanswer', $data);

echo $OUTPUT->footer();