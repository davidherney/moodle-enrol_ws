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
 * Web service enrolment plugin settings and presets.
 *
 * @package    enrol_ws
 * @copyright  2017 David Herney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_configtext('enrol_ws/uri', get_string('uri', 'enrol_ws'),
                       get_string('uri_key', 'enrol_ws'), ''));

    $options = array('POST'=>'POST', 'GET'=>'GET');
    $settings->add(new admin_setting_configselect('enrol_ws/method', get_string('method', 'enrol_ws'),
                       get_string('method_key', 'enrol_ws'), 'POST', $options));

    $settings->add(new admin_setting_configpasswordunmask('enrol_ws/token', get_string('token', 'enrol_ws'),
                       get_string('token_key', 'enrol_ws'), ''));


    if (!during_initial_install()) {
        $options = get_default_enrol_roles(context_system::instance());
        $student = get_archetype_roles('student');
        $student = reset($student);
        $settings->add(new admin_setting_configselect('enrol_ws/roleid',
            get_string('defaultrole', 'role'), '', $student->id, $options));
    }

    $settings->add(new admin_setting_configcheckbox('enrol_ws/defaultenrol',
        get_string('defaultenrol', 'enrol'), get_string('defaultenrol_desc', 'enrol'), 1));


}
