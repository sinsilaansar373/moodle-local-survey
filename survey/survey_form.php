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
 * Survey creation form.
 *
 * @package     local_survey
 * @copyright   2023 Your Name <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');

/**
 * Form for creating a survey.
 */
class survey_form extends moodleform
{

    /**
     * Define the form.
     */
    public function definition()
    {
        global $DB, $SESSION;

        $mform = $this->_form;

        // Academic Year Selection.
        $schoolid = $SESSION->schoolid ?? 0;
        $academicrecords = $DB->get_records('academic_year', ['school' => $schoolid]);
        $options = ['' => get_string('selectacademic', 'local_survey')];

        foreach ($academicrecords as $record) {
            $start = userdate($record->start_year, '%d/%m/%Y');
            $end = userdate($record->end_year, '%d/%m/%Y');
            $options[$record->id] = "$start - $end";
        }

        $mform->addElement('select', 'academicyear', get_string('academicyear', 'local_survey'), $options);
        $mform->addRule('academicyear', get_string('academicmissing', 'local_survey'), 'required', null, 'client');

        // Survey Name.
        $mform->addElement('text', 'surname', get_string('surveyname', 'local_survey'), ['size' => '30']);
        $mform->setType('surname', PARAM_TEXT);
        $mform->addRule('surname', get_string('surveyname_missing', 'local_survey'), 'required', null, 'client');

        // Date Selectors.
        $mform->addElement('date_selector', 'surveyfrom', get_string('surveyfrom', 'local_survey'));
        $mform->addElement('date_selector', 'surveyto', get_string('surveyto', 'local_survey'));

        // Questions.
        $mform->addElement('header', 'questionsheader', get_string('question', 'local_survey'));
        $mform->addElement('text', 'question1', get_string('question', 'local_survey') . ' 1', ['placeholder' => get_string('enterquestion', 'local_survey')]);
        $mform->setType('question1', PARAM_TEXT);
        $mform->addRule('question1', get_string('question_missing', 'local_survey'), 'required', null, 'client');

        // Dynamic questions handled via JS - keeping the hidden template element.
        $mform->addElement('hidden', 'question[]', '');
        $mform->setType('question', PARAM_TEXT);

        $mform->addElement('button', 'addquestionbtn', get_string('addquestion', 'local_survey'), ['onclick' => 'showAlert();']);

        $this->add_action_buttons();

        // Footer links.
        $mform->addElement('html', html_writer::start_div('mt-3'));
        $mform->addElement('html', html_writer::link(new moodle_url('/local/survey/survey_adminview.php'), get_string('viewsurvey', 'local_survey'), ['class' => 'btn btn-secondary']));
        $mform->addElement('html', html_writer::end_div());
    }
}