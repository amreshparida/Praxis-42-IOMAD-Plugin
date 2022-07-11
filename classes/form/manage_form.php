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

class manage_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;
       
        $mform = $this->_form; // Don't forget the underscore! 

        $mform->addElement('text', 'firstname', 'First Name'); // Add elements to your form.
        $mform->setType('firstname', PARAM_NOTAGS);                   // Set type of element.
        $mform->addRule('firstname', 'Firstname required', 'required', null, 'server');
        $mform->setDefault('firstname', '');        // Default value.

        $mform->addElement('text', 'lastname', 'Last Name'); // Add elements to your form.
        $mform->setType('lastname', PARAM_NOTAGS);                   // Set type of element.
        $mform->addRule('lastname', 'Lastname required', 'required', null, 'server');
        $mform->setDefault('lastname', '');        // Default value.

        $mform->addElement('text', 'username', 'Username', 'maxlength="100" size="25" ');
        $mform->setType('username', PARAM_NOTAGS);
        $mform->addRule('username', 'Username required', 'required', null, 'server');
        // Set default value by using a passed parameter
        $mform->setDefault('username','');


        
    $mform->addElement('passwordunmask', 'newpassword', get_string('newpassword'));
    $mform->setType('newpassword', PARAM_RAW);
    $mform->addRule('newpassword', 'Password required', 'required', null, 'server');




    $mform->addElement('text', 'email', 'Email', 'maxlength="100" size="25" ');
    $mform->setType('email', PARAM_EMAIL);
    $mform->addRule('email', 'Email required', 'required', null, 'server');
    // Set default value by using a passed parameter
    $mform->setDefault('email','');


        $this->add_action_buttons(false);

    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}