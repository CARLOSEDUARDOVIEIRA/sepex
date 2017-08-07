<?php

/**
 * Chama a api de mensagens do moodle
 *
 * @author Carlos Eduardo Vieira
 */
require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');

$user = $DB->get_record('user', array('id' => 3));

$message = new \core\message\message();
$message->component = 'moodle';
$message->name = 'instantmessage';
$message->userfrom = $USER;
$message->userto = $user;
$message->subject = 'message subject 1';
$message->fullmessage = 'message body';
$message->fullmessageformat = FORMAT_MARKDOWN;
$message->fullmessagehtml = '<p>message body</p>';
$message->smallmessage = 'small message';
$message->notification = '0';
$message->contexturl = 'http://GalaxyFarFarAway.com';
$message->contexturlname = 'Context name';
$message->replyto = "random@example.com";
$content = array('*' => array('header' => ' test ', 'footer' => ' test ')); // Extra content for specific processor
$message->set_additional_content('email', $content);
 
 
$messageid = message_send($message);