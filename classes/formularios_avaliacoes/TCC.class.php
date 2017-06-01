<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormularioAvaliador
 *
 * @author Lucas
 */
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.php');
require_once($CFG->dirroot . '/course/moodleform_mod.php');

class TCC extends moodleform {

    function definition() {
        global $DB, $PAGE;

        $mform = $this->_form;
        if($this->_customdata['resumo1']){
            $mform->setDefault('resumo1',$this->_customdata['resumo1']);
            $mform->setDefault('resumo2',$this->_customdata['resumo2']);    
            $mform->setDefault('resumo3',$this->_customdata['resumo3']);    
            $mform->setDefault('resumo4',$this->_customdata['resumo4']);    
            $mform->setDefault('resumo5',$this->_customdata['resumo5']);    
            $mform->setDefault('total_resumo',$this->_customdata['total_resumo']);            
        }
        //CAMPOS DE AVALIAÇÃO DO RESUMO PELO AVALIADOR
        $mform->addElement('header', 'resumo_orientador', get_string('resumo','sepex'),array('size' => '15'));               
             
        //Qualidade da redação e organização do texto        
        $mform->addElement('text', 'resumo1', get_string('qualidade_resumo', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));
        $mform->addRule('resumo1', get_string('qualidade_resumo', 'sepex'), 'required', null, 'client');
        $mform->addRule('resumo1', get_string('qualidade_resumo', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('resumo1', 'qualidade_resumo', 'sepex');
        $mform->setType('resumo1', PARAM_RAW);
                
        //Objetivos claros        
        $mform->addElement('text', 'resumo2', get_string('objetivos_resumo', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));
        $mform->addRule('resumo2', get_string('objetivos_resumo', 'sepex'), 'required', null, 'client');
        $mform->addRule('resumo2', get_string('objetivos_resumo', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('resumo2', 'objetivos_resumo', 'sepex');
        $mform->setType('resumo2', PARAM_RAW);

        //Descrição clara da metodologia        
        $mform->addElement('text', 'resumo3', get_string('metodologia_resumo', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));
        $mform->addRule('resumo3', get_string('metodologia_resumo', 'sepex'), 'required', null, 'client');
        $mform->addRule('resumo3', get_string('metodologia_resumo', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('resumo3', 'metodologia_resumo', 'sepex');
        $mform->setType('resumo3', PARAM_RAW);

        //Qualidade dos resultados
        $mform->addElement('text', 'resumo4', get_string('resultados_resumo', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));
        $mform->addRule('resumo4', get_string('resultados_resumo', 'sepex'), 'required', null, 'client');
        $mform->addRule('resumo4', get_string('resultados_resumo', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('resumo4', 'resultados_resumo', 'sepex');
        $mform->setType('resumo4', PARAM_RAW);

        //Adequação da conclusão aos objetivos propostos
        $mform->addElement('text', 'resumo5', get_string('conclusao_objetivos', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));
        $mform->addRule('resumo5', get_string('conclusao_objetivos', 'sepex'), 'required', null, 'client');
        $mform->addRule('resumo5', get_string('conclusao_objetivos', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('resumo5', 'conclusao_objetivos', 'sepex');
        $mform->setType('resumo5', PARAM_RAW);

        //Total
        $mform->addElement('static', 'total_resumo', get_string('total_resumo', 'sepex'));               
        $mform->setType('total_resumo', PARAM_RAW);
        
        $this->add_action_buttons();
    }

    function validation($data, $files) {
        return array();
    }

}
