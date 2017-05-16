<?php

/**
 * CLASSE RESPONSÁVEL POR IMPLEMENTAR UM FORMULÁRIO PARA DEFINIÇÃO DOS PROJETOS
 *
 * @author Carlos Eduardo Vieira. Linkedin<>.
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once($CFG->dirroot.'/course/moodleform_mod.php');
    
class FormularioDefinicaoProjeto extends moodleform {
   
    function definition() {
        global $DB, $PAGE;
        
        $mform = $this->_form;         
                         
        
        // -------------------- DATA PARA APRESENTAÇÃO DO PROJETO -------------------------
        $mform->addElement('header', 'apresentacao', get_string('data_apresentacao','sepex'));
        $mform->addElement('date_time_selector', 'data_apresentacao', get_string('data_definida', 'sepex'));
        
        $mform->addElement('submit', 'btnEnviar', get_string("btnEnviar", 'sepex'));
        
        
        
    }
    
    function validation($data, $files) {
        return array();
    }
}