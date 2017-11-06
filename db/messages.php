<?php

/* Enviar mensagem a usuarios disponivel em <https://docs.moodle.org/dev/Message_API> */

defined('MOODLE_INTERNAL') || die();

$messageproviders = array (
    // Notify teacher that a student has submitted a quiz attempt
    'submission' => array (
        'capability'  => 'mod/sepex:emailnotifysubmission'
    ),
    // Confirm a student's quiz attempt
    'confirmation' => array (
        'capability'  => 'mod/sepex:emailconfirmsubmission'
    )
);