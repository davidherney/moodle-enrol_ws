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
 * Web services user enrolment UI.
 *
 * @package    enrol_ws
 * @copyright  2014 David Herney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require '../../config.php';
require 'actions.php';

$enrolid      = required_param('enrolid', PARAM_INT);
$action       = optional_param('action', '', PARAM_TEXT);

$instance = $DB->get_record('enrol', array('id'=>$enrolid, 'enrol'=>'ws'), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id'=>$instance->courseid), '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

require_login($course);
require_capability('enrol/ws:manage', $context);

if (!$enrol_ws = enrol_get_plugin('ws')) {
    throw new coding_exception('Can not instantiate enrol_ws');
}

$instancename = $enrol_ws->get_instance_name($instance);

$PAGE->set_url('/enrol/ws/manage.php', array('enrolid'=>$instance->id));
$PAGE->set_pagelayout('admin');
$PAGE->set_title($instancename);
$PAGE->set_heading($course->fullname);

navigation_node::override_active_url(new moodle_url('/enrol/instances.php', array('id'=>$course->id)));

// Create the user selector objects.
$options = array('enrolid' => $enrolid, 'accesscontext' => $context);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('title_ws', 'enrol_ws'));

//global $USER;

if (empty($course->idnumber)) {
    echo $OUTPUT->notification(get_string('msg_empty_code', 'enrol_ws'));
    echo $OUTPUT->footer();
    return;
}

$errors = '';

if (!empty($errors)) {
    echo $OUTPUT->notification('<ul>' . $errors . '</ul>');
}

$config = get_config('enrol_ws');

if (empty($action)) {
    echo $OUTPUT->notification(get_string('restart_reminder', 'enrol_ws', $CFG->wwwroot . '/course/reset.php?id=' . $course->id), 'notifymessage');

    echo $OUTPUT->heading(get_string('course_code', 'enrol_ws', $course->idnumber), 2);

    echo $OUTPUT->container_start('buttons');
    echo $OUTPUT->single_button(new moodle_url('manage.php', array('enrolid' => $enrolid, 'action' => 'search')), get_string('search', 'enrol_ws'), 'get');
    echo $OUTPUT->container_end();
}
else if ($action == 'search') {

    if (enrol_ws_action_search()) {
        echo $OUTPUT->container_start('buttons');
        echo $OUTPUT->single_button(new moodle_url('manage.php', array('enrolid' => $enrolid, 'action' => 'enrol')), get_string('enrol', 'enrol_ws'), 'get');
        echo $OUTPUT->single_button(new moodle_url('manage.php', array('enrolid' => $enrolid, 'action' => 'enrol', 'sendmsg' => true)), get_string('send_message', 'enrol_ws'), 'get');
        echo $OUTPUT->container_end();
    }
}
else if ($action == 'enrol') {
    enrol_ws_action_enrol();
}

echo $OUTPUT->footer();
