<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once $CFG->dirroot . '/enrol/locallib.php';
require_once $CFG->libdir . '/filelib.php';

function enrol_ws_action_search () {

    global $course, $DB, $context, $config, $OUTPUT;

    //Add to log
    require_once 'classes/event/enrol_search.php';
    $event = \enrol_ws\event\enrol_search::create(array(
        'context' => $context
    ));
    $event->trigger();
    //End log

    $rol = new stdClass();
    $rol->id = $config->roleid;
    $users = get_users_from_role_on_context($rol, $context);

    $enrolled = array();
    if (is_array($users)) {
        foreach ($users as $asignation) {
            $user = $DB->get_record('user', array('id'=> $asignation->userid));
            if ($user) {
                $enrolled[$user->username] = fullname($user, true) . ', ' . $user->email;
            }
        }
    }

    // Search enrolled users.
    $curl = new curl();

    $params = array(
                    'wstoken' => $config->token,
                    'wsfunction' => 'search_enrolled_users',
                    'course' => $course->idnumber
                );

    if ($config->method == 'GET') {
        $response = $curl->get($config->uri, $params);
    }
    else {
        $response = $curl->post($config->uri, $params);
    }

    if ($curl->errno) {
        echo $OUTPUT->notification($response);
        return false;
    }

    $users = json_decode($response);

    if ($users === null || $users === false) {
        if (is_string($response)) {
            echo $OUTPUT->notification($response);
            return false;
        }

        echo $OUTPUT->notification(get_string('msg_bad_response', 'enrol_ws'));
        return false;
    }

    if (!is_array($users)) {
        echo $OUTPUT->notification(get_string('msg_bad_response', 'enrol_ws'));
        return false;
    }

    if (count($users) == 0) {
        echo $OUTPUT->notification(get_string('msg_not_students', 'enrol_ws'));
        return false;
    }

    $new_users = array();
    $old_users = array();
    foreach ($users as $user) {
        if (!isset($enrolled[$user->username])) {
            $new_users[] = $user;
        }
        else {
            $old_users[] = $enrolled[$user->username];
        }
    }

    echo $OUTPUT->heading(get_string('new_users', 'enrol_ws'), 3);

    if (count($new_users) == 0) {
        echo $OUTPUT->notification(get_string('msg_error_not_new_students', 'enrol_ws'));
    }

    echo html_writer::start_tag('ul');
    foreach($new_users as $user) {
        echo html_writer::tag('li', $user->firstname . ' ' . $user->lastname . ', ' . $user->email);
    }
    echo html_writer::end_tag('ul');

    echo $OUTPUT->heading(get_string('old_users', 'enrol_ws'), 3);

    if (count($old_users) == 0) {
        echo $OUTPUT->notification(get_string('msg_error_not_old_students', 'enrol_ws'));
    }

    echo html_writer::start_tag('ul');
    foreach($old_users as $user) {
        echo html_writer::tag('li', $user);
    }

    echo html_writer::end_tag('ul');

    return count($new_users) > 0;
}


function enrol_ws_action_enrol () {

    global $CFG, $PAGE, $course, $DB, $context, $config, $OUTPUT;

    require_once $CFG->dirroot .'/lib/moodlelib.php';

    $send_message = optional_param('sendmsg', false, PARAM_BOOL);

    // Messages configuration is changed temporary if option is not send notification messages.
    if (!$send_message) {
        $CFG->sendcoursewelcomemessage = false;
    }


    $manager = new course_enrolment_manager($PAGE, $course);
    $instances = $manager->get_enrolment_instances();
    $instance = false;
    foreach ($instances as $i) {
        if ($i->enrol == 'manual') {
            $instance = $i;
            break;
        }
    }

    if (!$instance) {
        echo $OUTPUT->notification(get_string('msg_error_manual_not_available', 'enrol_ws'));
        return;
    }

    $enrol_manual = enrol_get_plugin('manual');


    // Current enrolled users
    $rol = new stdClass();
    $rol->id = $config->roleid;
    $users = get_users_from_role_on_context($rol, $context);

    $enrolled = array();
    if (is_array($users)) {
        foreach ($users as $asignation) {
            $user = $DB->get_record('user', array('id'=> $asignation->userid));
            if ($user) {
                $enrolled[$user->username] = $user->email;
            }
        }
    }

    // Get new users.
    $curl = new curl();

    $params = array(
                    'wstoken' => $config->token,
                    'wsfunction' => 'search_enrolled_users',
                    'course' => $course->idnumber
                );

    if ($config->method == 'GET') {
        $response = $curl->get($config->uri, $params);
    }
    else {
        $response = $curl->post($config->uri, $params);
    }

    if ($curl->errno) {
        echo $OUTPUT->notification($response);
        return false;
    }

    $users = json_decode($response);

    if ($users === null || $users === false) {
        if (is_string($response)) {
            echo $OUTPUT->notification($response);
            return false;
        }

        echo $OUTPUT->notification(get_string('msg_bad_response', 'enrol_ws'));
        return false;
    }

    if (!is_array($users)) {
        echo $OUTPUT->notification(get_string('msg_bad_response', 'enrol_ws'));
        return false;
    }

    if (count($users) == 0) {
        echo $OUTPUT->notification(get_string('msg_not_students', 'enrol_ws'));
        return false;
    }

    if ($CFG->authpreventaccountcreation) {
        echo $OUTPUT->notification(get_string('msg_authpreventaccountcreation', 'enrol_ws'), 'notifymessage');
    }

    foreach ($users as $student) {
        if (!isset($enrolled[$student->username])) {
            $user = $DB->get_record('user', array('username'=> $student->username));

            if (!$user) {

                if (!$CFG->authpreventaccountcreation) {
                    $new_user = new stdClass();
                    $new_user->username = $student->username;
                    $new_user->firstname = $student->firstname;
                    $new_user->lastname = $student->lastname;
                    $new_user->institution = $student->institution;
                    $new_user->address = $student->address;
                    $new_user->city = $student->city;
                    $new_user->phone1 = $student->phone1;
                    $new_user->phone2 = $student->phone2;
                    $new_user->email = $student->email;
                    $new_user->url = $student->web;

                    // It is not possible, needed change by Country code
                    //$new_user->county = $student->county;

                    $user = enrol_ws_create_user($new_user);
                }

            }
            else if ($user->deleted) {
                $DB->set_field('user', 'deleted', 0, array('username'=> $student->username));
            }

            if (!$user) {
                echo $OUTPUT->notification(get_string('msg_error_not_create_user', 'enrol_ws', $student->username));
            }
            else {
                $enrol_manual->enrol_user($instance, $user->id, $config->roleid, time());
            }
        }
    }

    echo $OUTPUT->notification(get_string('msg_successful_enrol', 'enrol_ws'), 'notifysuccess');

    return true;
}

// Insert a new user in moodle data base.
// The password field is used to indicate insert method saving ws_enrolment as value. The user need restore the password.
function enrol_ws_create_user ($new_user) {
    global $CFG, $DB;

    if (!$new_user || empty($new_user->username)) {
        return null;
    }

    $new_user->password   = hash_internal_user_password($new_user->username);
    $new_user->modified   = time();
    $new_user->confirmed  = 1;
    $new_user->auth       = 'manual';
    $new_user->mnethostid = $CFG->mnet_localhost_id;
    $new_user->lang       = $CFG->lang;

    if ($id = $DB->insert_record ('user', $new_user)) {
        set_user_preference('auth_forcepasswordchange', 1, $id);

        return $DB->get_record('user', array('id'=> $id));
    }

    return null;
}
