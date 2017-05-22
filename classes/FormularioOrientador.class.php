<?php

/**
 * Formulario de avaliação dos professsores orientadores
 *
 * @author Carlos Eduardo Vieira. Linkedin<>.
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once($CFG->dirroot.'/course/moodleform_mod.php');

class FormularioOrientador extends moodleform {
    
    function definition() {
        global $DB, $PAGE;
        
        $mform = $this->_form;
        $modcontext = $this->_customdata['modcontext'];
        
        // -------------------- RESUMO -------------------------
        $mform->addElement('header', 'resumo', get_string('resumo','sepex'));
        //$resumo = $this->_customdata['resumo'];
        $mform->addElement('editor', 'resumo', get_string('resumo', 'sepex'));//, null, array('context' => $modcontext))->setValue( array('text' => $resumo));        
        $mform->addHelpButton('resumo', 'resumo', 'sepex');
        $mform->setType('resumo', PARAM_RAW);
        
        // -------------------- AVALIAÇÃO -------------------------
        $mform->addElement('header', 'avaliacao', get_string('avaliacao','sepex'));
        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'condicao', '', get_string('aprovado','sepex'), 0);
        $radioarray[] = $mform->createElement('radio', 'condicao', '', get_string('reprovado','sepex'), 1);       
        $mform->addGroup($radioarray, 'radioar', '', array(' '), false);
        $mform->setDefault('condicao', 0);
        
        // -------------------- COMENTARIO SOBRE AVALIAÇÃO -------------------------
        $mform->addElement('header', 'comentarioOrientador', get_string('comentario','sepex'));
        $mform->addHelpButton('comentarioOrientador', 'comentarioOrientador', 'sepex');
        $mform->addElement('textarea', 'comentario', get_string("comentario", "sepex"), 'wrap="virtual" rows="5" cols="60"');
        
        
        $this->add_action_buttons();
                
        
    }
    function validation($data, $files) {
        return array();
    }
}
