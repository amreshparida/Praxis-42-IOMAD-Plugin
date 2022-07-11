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


require_once(__DIR__.'/../../config.php'); //require config file
require_once($CFG->dirroot.'/local/praxis_42/classes/form/manage_form.php'); // Form 1
require_once($CFG->dirroot.'/local/praxis_42/classes/form/manage_form_2.php'); // Form 2





$context = context_system::instance();
$PAGE->set_url(new moodle_url('/local/praxis_42/manage.php')); //page url
$PAGE->requires->css("/local/praxis_42/styles.css"); //page css
$PAGE->set_context($context); //moodle context
$PAGE->set_title('Manage Praxis 42');
$PAGE->set_heading("Praxis 42 - Assessment");

$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/local/praxis_42/script.js'), true);



require_login();
iomad::require_capability('block/iomad_company_admin:user_create', $context);


$companyid = iomad::get_my_companyid($context);
$company = new company($companyid);




$mform = new manage_form(); //Form 1 object

$mform2 = new manage_form_2(); //Form 2 object

echo $OUTPUT->header();



//Form 1 processing is done here
 if ($fromform = $mform->get_data()) {
  //In this case you process validated data. $mform->get_data() returns data posted in form.
 
  // Check if the company has gone over the user quota.
    if (!$company->check_usercount(1)) {
        $maxusers = $company->get('maxusers');
        print_error('maxuserswarning', 'local_praxis_42', new moodle_url('/local/praxis_42/manage.php'), $maxusers);
    }


    // Trimming first and lastnames 
    $fromform->firstname = trim($fromform->firstname);
    $fromform->lastname = trim($fromform->lastname);

    $fromform->userid = $USER->id;
    if ($companyid > 0) {
        $fromform->companyid = $companyid;
    }

    $fromform->due = time();

    if(count($DB->get_records_sql("SELECT * FROM {user} WHERE email = :email OR username = :username", array('email' => $fromform->email, 'username' => $fromform->username))) > 0)
    {
        redirect(new moodle_url('/local/praxis_42/manage.php'), "Duplicate User Entry!!!", null, \core\output\notification::NOTIFY_ERROR);
        die();
    }

    if (!$userid = company_user::create($fromform)) {
        $this->verbose("Error inserting a new user in the database!");
        if (!$this->get('ignore_errors')) {
            die();
        }
    }

    $user = new stdclass();
    $user->id = $userid;
    $fromform->id = $userid;

    $parentlevel = company::get_company_parentnode($company->id);
    company::assign_user_to_department($parentlevel->id, $userid);
   
    redirect(new moodle_url('/local/praxis_42/manage.php'), "User Created Successfully!!!", null, \core\output\notification::NOTIFY_SUCCESS);

} 

//Form 2 processing is done here
if ($fromform2 = $mform2->get_data()) {
      //In this case you process validated data. $mform->get_data() returns data posted in form.
    if($fromform2->company == 0 || $fromform2->course == 0 || $fromform2->course == 0 )
    {
        redirect(new moodle_url('/local/praxis_42/manage.php'), "Please enter all the required fields.", null, \core\output\notification::NOTIFY_ERROR);
        die();
    }
      
    $company_id = $fromform2->company; //fetch company id
   

    $recordtoinsert = new stdClass();
    $recordtoinsert->userid = $fromform2->user; //fetch user id
    $recordtoinsert->course = $fromform2->course; //fetch course id
    $recordtoinsert->gradefinal = $fromform2->finalscore; //fetch final score
    $recordtoinsert->timecompleted = $fromform2->comp_datetime; //fetch completion date time



    $course = $DB->get_record('course', array('id'=>$fromform2->course)); //load course 
    $completioninfo = new completion_info($course); //course completion object

    $criteria = $completioninfo->get_criteria(COMPLETION_CRITERIA_TYPE_GRADE); //fetch complete course by grade criteria for course completion
    if(count($criteria)==0) //check if course complete by grade criteria is not set
    {
        //if course complete by grade criteria is not set
        redirect(new moodle_url('/local/praxis_42/manage.php'), $course->fullname." course completion: Condition: Course grade is not enabled", null, \core\output\notification::NOTIFY_ERROR);
        die();
    }

    foreach ($criteria as $criterion) { //Iterate criteria array having only one element
        $completions = $completioninfo->get_completions($fromform2->user);// get completeion info for this user
        foreach ($completions as $completion) { //Iteriate user completion array having only one element
            if($completion->is_complete()){ //Check if course is completed by this user in this criteria
                redirect(new moodle_url('/local/praxis_42/manage.php'), "Course is already completed for this user", null, \core\output\notification::NOTIFY_INFO);
                die();
            }else{ 
                if ($completion->criteriaid === $criterion->id) { //check if completion criteria id matches with this criteria id
                    //lets mark the course complete
                    $recordtoinsert->criteriaid = $criterion->id;
                    $DB->insert_record('course_completion_crit_compl',$recordtoinsert);
                }
            }
        }
    
    }

    $user = $DB->get_record('user', array('id'=>$fromform2->user));

    redirect(new moodle_url('/local/praxis_42/manage.php'), $course->fullname." course is marked completed for user ".$user->firstname." ".$user->lastname." with final grade ".$fromform2->finalscore." on date ".date("d-m-Y h:i:sa", $fromform2->comp_datetime), null, \core\output\notification::NOTIFY_SUCCESS);

}

echo '<div class="container">';
echo '<div class="row">';
echo '<div class="col-xs-12 col-sm-6 p-0" style="border: 1px solid #c9c9c9;" >';
echo '<div class="alert alert-info">
<strong>Form 1:</strong> Create New User</a>.
</div>';
echo '<div class="p-4">';

$mform->display(); 

echo '</div>';
echo '</div>';


echo '<div class="col-xs-12 col-sm-6 p-0" style="border: 1px solid #c9c9c9;">';
echo '<div class="alert alert-info">
<strong>Form 2:</strong> Mark Course Complete</a>.
</div>';
echo '<div class="p-4">';

$mform2->display();

echo '</div>';
echo '</div>';
echo '</div>';

echo $OUTPUT->footer();

