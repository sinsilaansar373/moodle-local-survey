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
 * Admin view for surveys.
 *
 * @package     local_survey
 * @copyright   2023 Your Name <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

$context = context_system::instance();
require_login();
require_capability('local/survey:manage', $context);

$url = new moodle_url('/local/survey/survey_adminview.php');
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('viewsurvey', 'local_survey'));
$PAGE->set_heading(get_string('viewsurvey', 'local_survey'));

echo $OUTPUT->header();

$schoolid = $SESSION->schoolid ?? 0;
$surveys = $DB->get_records('local_survey', null, 'id DESC');

$data = [
  'surveys' => [],
  'create_url' => new moodle_url('/local/survey/survey.php'),
];

foreach ($surveys as $survey) {
  $s = new stdClass();
  $s->id = $survey->id;
  $s->name = $survey->survey_name;
  $s->from = userdate($survey->survey_from, '%d-%m-%Y');
  $s->to = userdate($survey->survey_to, '%d-%m-%Y');
  $s->edit_url = new moodle_url('/local/survey/editsurvey.php', ['id' => $survey->id]);
  $s->delete_url = new moodle_url('/local/survey/deletesurvey.php', ['id' => $survey->id]);
  $s->answers_url = new moodle_url('/local/survey/viewsurveyanswer.php', ['id' => $survey->id]);

  $questions = $DB->get_records('local_survey_question', ['survey_id' => $survey->id]);
  $s->questions = array_values($questions);

  $data['surveys'][] = $s;
}

echo $OUTPUT->render_from_template('local_survey/survey_adminview', $data);

echo $OUTPUT->footer();