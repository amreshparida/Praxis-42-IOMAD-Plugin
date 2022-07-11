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

define('AJAX_SCRIPT', true);

require_once(__DIR__.'/../../config.php'); //require config file
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/lib/completionlib.php');


$courseid = required_param('id', PARAM_INT); // course id

$context = context_system::instance();
$PAGE->set_url(new moodle_url('/local/praxis_42/ajax.php')); //page url
$PAGE->set_context($context); //moodle context

require_login();
iomad::require_capability('block/iomad_company_admin:user_create', $context);

$html = "";

$users = $DB->get_records_sql("SELECT u.id, u.firstname, u.lastname, u.email, ue.status FROM {user} u LEFT JOIN {user_enrolments} ue ON ue.userid = u.id JOIN {enrol} e ON e.id = ue.enrolid AND e.courseid = :courseid", array('courseid' => $courseid) );
    
    foreach($users as $u)
    {
        $user_company = $DB->get_record('company_users', array('userid' => $u->id));
        
        if(iomad::get_my_companyid(context_system::instance()) == $user_company->companyid)
        {
            $html .= '<option value="'.$u->id.'">'.$u->firstname.' '.$u->lastname.' ('.$u->email.')</option>';
        }
    }



$outcome = new stdClass();
$outcome->success = true;
$outcome->response = new stdClass();
$outcome->error = '';
$outcome->response->html = $html;

echo json_encode($outcome);
die();