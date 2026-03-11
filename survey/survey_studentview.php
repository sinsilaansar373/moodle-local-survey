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
 * Student view for surveys.
 *
 * @package     local_survey
 * @copyright   2023 Your Name <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

$context = context_system::instance();
require_login();
require_capability('local/survey:submit', $context);

$url = new moodle_url('/local/survey/survey_studentview.php');
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('viewsurvey', 'local_survey'));
$PAGE->set_heading(get_string('viewsurvey', 'local_survey'));

echo $OUTPUT->header();

$now = time();
$surveys = $DB->get_records_select('local_survey', 'survey_from <= ? AND survey_to >= ?', [$now, $now]);

if (empty($surveys)) {
    echo $OUTPUT->notification(get_string('nosurveys', 'local_survey'), 'info');
}
else {
    $data = ['surveys' => []];
    foreach ($surveys as $survey) {
        // Check if user already answered.
        $answered = $DB->record_exists_sql("
            SELECT sa.id FROM {local_survey_answers} sa 
            JOIN {local_survey_question} lsq ON lsq.id = sa.q_id 
            WHERE sa.user_id = ? AND lsq.survey_id = ?", [$USER->id, $survey->id]);

        if ($answered) {
            continue;
        }

        $s = new stdClass();
        $s->id = $survey->id;
        $s->name = $survey->survey_name;
        $questions = $DB->get_records('local_survey_question', ['survey_id' => $survey->id]);
        $s->questions = array_values($questions);
        $s->submit_url = new moodle_url('/local/survey/submit.php');

        $data['surveys'][] = $s;
    }

    if (empty($data['surveys'])) {
        echo $OUTPUT->notification(get_string('nosurveys', 'local_survey'), 'info');
    }
    else {
        echo $OUTPUT->render_from_template('local_survey/survey_studentview', $data);
    }
}

echo $OUTPUT->footer();