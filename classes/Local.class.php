<?php

/**
 * Description of Local
 *
 * @author Carlos Eduardo Vieira
 */
require($CFG->dirroot . '/course/moodleform_mod.php');

class Local extends moodleform {

    function definition() {
        $mform = $this->_form;
        $mform->addElement('text', 'nomelocalapresentacao', get_string('add_locais', 'sepex'), array('placeholder' => get_string('placeholderlocais', 'sepex'), 'size' => '60'));
        $mform->setType('nomelocalapresentacao', PARAM_RAW);
        $mform->addRule('nomelocalapresentacao', get_string('campo_requerido', 'sepex'), 'required', null, 'client');
        $mform->addRule('nomelocalapresentacao', get_string('campo_limite', 'sepex', 255), 'maxlength', 255, 'client');
        $this->add_action_buttons($cancel = true, $submitlabel = get_string('add', 'sepex'));
    }

    function validation($data, $files) {
        return array();
    }

}
