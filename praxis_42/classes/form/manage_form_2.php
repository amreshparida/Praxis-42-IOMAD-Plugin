<?php
// This is a part of Praxis 42 <https://www.praxis42.com//> Test Assessment
//
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
 * @package   praxis_42
 * @copyright 2022 Amaresh Parida
 * @author    Amaresh Parida
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("$CFG->libdir/formslib.php");

class manage_form_2 extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG, $DB;
       
        $mform = $this->_form; // Don't forget the underscore! 
//Company        
        $companylist = array();
        $companylist[0] = "Select Company";
        if(has_capability('moodle/site:config', context_system::instance())){
            $companies = $DB->get_records('company');
        }else{
            $companies = $DB->get_records('company', ['id' => iomad::get_my_companyid(context_system::instance())]);
        }
       
        foreach ($companies as $company) {
            $companylist[$company->id] = $company->name;
        }
        $mform->addElement('select', 'company', 'Company Name', $companylist, array('onchange' => 'javascript:courseReset()')); // Add elements to your form.
        $mform->addRule('company', 'Company required', 'required', null, 'server');
//Course
        $courselist = array();
        $courselist[0] = "Select Course";
        if(has_capability('moodle/site:config', context_system::instance())){
            $courses = $DB->get_records_sql("SELECT c.* FROM {company_course} cc JOIN {iomad_courses} ic ON (cc.courseid = ic.courseid) JOIN {course} c ON (ic.courseid = c.id) WHERE  ic.shared = 1");
        }else{
            $courses = $DB->get_records_sql("SELECT c.* FROM {company_course} cc JOIN {iomad_courses} ic ON (cc.courseid = ic.courseid) JOIN {course} c ON (ic.courseid = c.id) WHERE cc.companyid = :companyid OR ic.shared = 1",array('companyid' => iomad::get_my_companyid(context_system::instance())));
        }
        foreach ($courses as $course) {
            $courselist[$course->id] = $course->fullname;
        }

        $mform->addElement('select', 'course', 'Course Name', $courselist, array('id'=>'courses_select','onchange' => 'javascript:fetchUsers("'.new moodle_url('/local/praxis_42/ajax.php').'",this.value)')); // Add elements to your form.
        $mform->addRule('course', 'Course required', 'required', null, 'server');
//User
        $mform->addElement('select', 'user', 'User', ['Select User'], array('id' => 'users_select')); // Add elements to your form.
        $mform->addRule('user', 'user required', 'required', null, 'server');

//Final score
        $mform->addElement('float', 'finalscore', 'Final Score'); // Add elements to your form.
        $mform->addRule('finalscore', 'Final score required', 'required', null, 'server');

//Completion date time
        $mform->addElement('date_time_selector', 'comp_datetime', 'Completion Date Time'); // Add elements to your form.
        $mform->addRule('comp_datetime', 'Completion Date Time required', 'required', null, 'server');




        $this->add_action_buttons(false);

    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }

    function get_data(){
        global $DB;
    
        $data = parent::get_data();
    
        if (!empty($data)) {
            $mform =& $this->_form;
    
            // Add the studentid properly to the $data object.
            if(!empty($mform->_submitValues['user'])) {
                $data->user = $mform->_submitValues['user'];
            }
    
        }
    
        return $data;
    }


}