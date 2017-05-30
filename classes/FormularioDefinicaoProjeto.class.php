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
        $course = $this->_customdata['course'];
        if(isset($this->_customdata['localapresentacao'])){
            $mform->setDefault('localapresentacao',$this->_customdata['localapresentacao']);
            $mform->setDefault('avaliador', $this->_customdata['avaliador']);
            $mform->setDefault('avaliador2', $this->_customdata['avaliador2']);
        }
        
        
        // -------------------- DATA PARA APRESENTAÇÃO DO PROJETO -------------------------
        $mform->addElement('header', 'apresentacao', get_string('dataapresentacao','sepex'));
        $mform->addElement('date_time_selector', 'data_apresentacao', get_string('data_definida', 'sepex'));        
        $mform->addHelpButton('data_apresentacao', 'dataapresentacao', 'sepex');
        $mform->setDefault('data_apresentacao',$this->_customdata['data_apresentacao']);
        
                        
        // -------------------- LOCAL PARA APRESENTAÇÃO DO PROJETO -------------------------
        $locais = $DB->get_records('sepex_local_apresentacao');        
        $locais_apresentacao = array(''=>'Escolher',);
        foreach($locais as $local){                    
            $locais_apresentacao[$local->id_local_apresentacao] =  $local->nome_local_apresentacao;
        }
        $mform->addElement('header', 'loc_apresentacao', get_string('localapresentacao','sepex'));
        $mform->addElement('select', 'localapresentacao', get_string('localapresentacao', 'sepex'), $locais_apresentacao);        
        $mform->addRule('localapresentacao', get_string('localapresentacaovazio', 'sepex'), 'required', null, 'client');        
        $mform->addHelpButton('localapresentacao', 'localapresentacao', 'sepex');
        
        
        
        //------------------------ SELEÇÃO AVALIADORES ------------------------------------
        $mform->addElement('header', 'avaliadores', get_string('avaliadores','sepex'));        
            
        $teacher = 'editingteacher';
        $avaliadores = listar_usuarios_por_curso($teacher,$course);        
        $professores = array(''=>'Escolher',);
        foreach($avaliadores as $professor){
            $professores[$professor->username] =  $professor->name;
        }
        
        $mform->addElement('select', 'avaliador', get_string('avaliador', 'sepex'), $professores);        
        $mform->addElement('select', 'avaliador2', get_string('orientador2', 'sepex'), $professores);
        $mform->addRule('avaliador', get_string('avaliadorvazio', 'sepex'), 'required', null, 'client');        
        $mform->addHelpButton('avaliador', 'avaliador', 'sepex');        
                           
        $this->add_action_buttons();
    }
    function validation($data, $files) {
        return array();
    }
}