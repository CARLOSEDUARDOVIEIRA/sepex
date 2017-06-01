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

class Video extends moodleform {

    function definition() {
        global $DB, $PAGE;

        $mform = $this->_form;
        $id_projeto = $this->_customdata['id_projeto'];
        if($this->_customdata['apresentacao1']){
            $mform->setDefault('apresentacao1',$this->_customdata['apresentacao1']);
            $mform->setDefault('apresentacao2',$this->_customdata['apresentacao2']);
            $mform->setDefault('apresentacao3',$this->_customdata['apresentacao3']);
            $mform->setDefault('apresentacao4',$this->_customdata['apresentacao4']);
            $mform->setDefault('apresentacao5',$this->_customdata['apresentacao5']);
            $mform->setDefault('total_apresentacao',$this->_customdata['total_apresentacao']);
        }
        $mform->addElement('header', 'header_apresentacao', get_string('header_apresentacao','sepex'));
        //CAMPOS DE ALUNOS POR PROJETO               
        $alunos = listar_nome_alunos($id_projeto);
        $lista_aluno = array();
        foreach($alunos as $aluno){
            $lista_aluno[$aluno->username] =  $aluno->name;
        }    
        $typeitem = array();
        foreach ($lista_aluno as $key => $value) {
            $aluno = listar_presenca_aluno_matricula($id_projeto, $key);                                    
         $typeitem[] = &$mform->createElement('advcheckbox',$key, '', $value, array('name' => $key,'group'=>1), array(0,1));
         $mform->setDefault("types[$key]", $aluno[$id_projeto]->presenca);         
        }
        $mform->addGroup($typeitem, 'types',get_string('presenca_integrantes','sepex'));
        $mform->addHelpButton('types', 'presenca_integrantes', 'sepex');
        
        $mform->addElement('text', 'apresentacao1', get_string('qualidade_video', 'sepex'), array('placeholder'=> '30 pontos', 'size' => '15'));        
        $mform->addRule('apresentacao1', get_string('qualidade_video', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('apresentacao1', 'qualidade_video', 'sepex');
        $mform->setType('apresentacao1', PARAM_RAW);

        $mform->addElement('text', 'apresentacao2', get_string('originalidade_video', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));        
        $mform->addRule('apresentacao2', get_string('originalidade_video', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('apresentacao2', 'originalidade_video', 'sepex');
        $mform->setType('apresentacao2', PARAM_RAW);

        $mform->addElement('text', 'apresentacao3', get_string('exposicao_apresentacao', 'sepex'), array('placeholder'=> '15 pontos', 'size' => '15'));        
        $mform->addRule('apresentacao3', get_string('exposicao_apresentacao', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('apresentacao3', 'exposicao_apresentacao', 'sepex');
        $mform->setType('apresentacao3', PARAM_RAW);

        $mform->addElement('text', 'apresentacao4', get_string('coerencia_video', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));        
        $mform->addRule('apresentacao4', get_string('coerencia_video', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('apresentacao4', 'coerencia_video', 'sepex');
        $mform->setType('apresentacao4', PARAM_RAW);
        
        $mform->addElement('text', 'apresentacao5', get_string('qualidade_resumo', 'sepex'), array('placeholder'=> '15 pontos', 'size' => '15'));        
        $mform->addRule('apresentacao5', get_string('qualidade_resumo', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('apresentacao5', 'qualidade_resumo', 'sepex');
        $mform->setType('apresentacao5', PARAM_RAW);
                      
        //total
        $mform->addElement('static', 'total_apresentacao', get_string('total_apresentacao', 'sepex'));               
        $mform->setType('total_apresentacao', PARAM_RAW);
        
        $this->add_action_buttons();
    }

    function validation($data, $files) {
        return array();
    }

}
