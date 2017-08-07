<?php

require_once('../../../message/lib.php');
$userid = 2;
$userto = $DB->get_record('user', array('id' => $userid));
 
message_messenger_requirejs();
$url = new moodle_url('message/index.php', array('id' => $userto->id));
$attributes = message_messenger_sendmessage_link_params($userto);
echo html_writer::link($url, 'Send a message', $attributes);

