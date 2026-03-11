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
 * Page for creating and managing surveys.
 *
 * @package     local_survey
 * @copyright   2023 Your Name <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/survey/survey_form.php');

$context = context_system::instance();
require_login();
require_capability('local/survey:manage', $context);

$url = new moodle_url('/local/survey/survey.php');
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'local_survey'));
$PAGE->set_heading(get_string('pluginname', 'local_survey'));
$PAGE->navbar->add(get_string('pluginname', 'local_survey'), $url);

$mform = new survey_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/survey/survey_adminview.php'));
}
else if ($data = $mform->get_data()) {
    $survey = new stdClass();
    $survey->survey_name = $data->surname;
    $survey->survey_from = $data->surveyfrom;
    $survey->survey_to = $data->surveyto;
    $survey->academic_id = $data->academicyear;
    $survey->timecreated = time();
    $survey->timemodified = time();

    $surveyid = $DB->insert_record('local_survey', $survey);

    $questions = [$data->question1];
    if (!empty($data->question) && is_array($data->question)) {
        foreach ($data->question as $q) {
            if (!empty($q)) {
                $questions[] = $q;
            }
        }
    }

    foreach ($questions as $qtext) {
        $question = new stdClass();
        $question->survey_id = $surveyid;
        $question->survey_question = $qtext;
        $DB->insert_record('local_survey_question', $question);
    }

    redirect($url, get_string('savesuccess', 'local_survey'), null, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();