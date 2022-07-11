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




 function local_praxis_42_extend_navigation(global_navigation $navigation) {
  
    
    if(!has_capability('block/iomad_company_admin:company_view', context_system::instance())){
        return;
    }


        $strfoo = "Praxis 42 - Assessment";
        $url = new moodle_url('/local/praxis_42/manage.php');
        $foonode = $navigation->add(
            $strfoo,
            $url,
            navigation_node::TYPE_CUSTOM,
            'praxis_42',
            'praxis_42',
            new pix_icon('praxis42', 'randomIcon', 'local_praxis_42')
        )->showinflatnavigation = true;

      
    
}