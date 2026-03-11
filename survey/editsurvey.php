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
 * Edit an existing survey.
 *
 * @package     local_survey
 * @copyright   2023 Your Name <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/survey/editsurvey_form.php');

$id = required_param('id', PARAM_INT);
$context = context_system::instance();
require_login();
require_capability('local_survey:manage', $context);

$url = new moodle_url('/local/survey/editsurvey.php', ['id' => $id]);
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('editsurvey', 'local_survey'));
$PAGE->set_heading(get_string('editsurvey', 'local_survey'));

$survey = $DB->get_record('local_survey', ['id' => $id], '*', MUST_EXIST);
$questions = $DB->get_records('local_survey_question', ['survey_id' => $id]);

$mform = new editsurvey_form(null, ['survey' => $survey, 'questions' => $questions]);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/survey/survey_adminview.php'));
}
else if ($data = $mform->get_data()) {
    $survey->survey_name = $data->surname;
    $survey->survey_from = $data->surveyfrom;
    $survey->survey_to = $data->surveyto;
    $survey->academic_id = $data->academicyear;
    $survey->timemodified = time();

    $DB->update_record('local_survey', $survey);

    // Update existing questions.
    foreach ($questions as $index => $question) {
        $fieldname = 'question' . ($index + 1);
        if (isset($data->{ $fieldname})) {
            $question->survey_question = $data->{ $fieldname};
            $DB->update_record('local_survey_question', $question);
        }
    }

    // Add new questions.
    if (!empty($data->question) && is_array($data->question)) {
        foreach ($data->question as $newq) {
            if (!empty($newq)) {
                $qrecord = new stdClass();
                $qrecord->survey_id = $id;
                $qrecord->survey_question = $newq;
                $DB->insert_record('local_survey_question', $qrecord);
            }
        }
    }

    redirect(new moodle_url('/local/survey/survey_adminview.php'), get_string('savesuccess', 'local_survey'), null, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();