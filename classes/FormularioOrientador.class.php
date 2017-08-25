<?php

/**
 * Formulario de avaliação dos professsores orientadores
 *
 * @author Carlos Eduardo Vieira. Linkedin<>.
 */

require($CFG->dirroot . '/course/moodleform_mod.php');

class FormularioOrientador extends moodleform {

    function definition() {

        $mform = $this->_form;
        $modcontext = $this->_customdata['modcontext'];
        $statusresumo = $this->_customdata['statusresumo'];

        // -------------------- RESUMO -------------------------
        $mform->addElement('header', 'resumo_header', get_string('resumo', 'sepex'));
        $resumo = $this->_customdata['resumo'];
        $mform->addElement('editor', 'resumo', get_string('resumo_orientador', 'sepex'), null, array('context' => $modcontext))->setValue(array('text' => $resumo));
        $mform->addHelpButton('resumo', 'resumo_orientador', 'sepex');
        $mform->setType('resumo', PARAM_RAW);

        //---------------------TAGS--------------------------       
        $mform->addElement('text', 'tags', get_string('tags', 'sepex'), array('size' => '64'));
        $mform->setType('tags', PARAM_RAW);
        $mform->addRule('tags', get_string('tagsvazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('tags', get_string('tags', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('tags', 'tags', 'sepex');
        $mform->setDefault('tags', $this->_customdata['tags']);

        // -------------------- AVALIAÇÃO -------------------------
        $mform->addElement('header', 'avaliacao', get_string('avaliacao', 'sepex'));
        $radioarray = array();
        $radioarray[] = $mform->createElement('radio', 'statusresumo', '', get_string('aprovado', 'sepex'), 1);
        $radioarray[] = $mform->createElement('radio', 'statusresumo', '', get_string('reprovado', 'sepex'), 0);
        $mform->addGroup($radioarray, 'radioar', '', array(' '), false);
        if ($statusresumo != null) {
            $mform->setDefault('statusresumo', $this->_customdata['statusresumo']);
        } else {
            $mform->setDefault('statusresumo', 1);
        }
        $mform->addElement('textarea', 'obsorientador', get_string("comentario", "sepex"), 'wrap="virtual" rows="5" cols="67"');
        $mform->addRule('obsorientador', get_string('comentario', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('comentario', 'obsorientador', 'sepex');
        $mform->setType('obsorientador', PARAM_RAW);
        $mform->setDefault('obsorientador', $this->_customdata['obsorientador']);

        $this->add_action_buttons();
    }

    function validation($data, $files) {
        return array();
    }

}
