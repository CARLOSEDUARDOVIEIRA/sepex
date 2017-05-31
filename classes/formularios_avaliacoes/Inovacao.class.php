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

class Inovacao extends moodleform {

    function definition() {
        global $DB, $PAGE;

        $mform = $this->_form;
        $id_projeto = $this->_customdata['id_projeto'];        
        $placeholder = '20 pontos';
                
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
        
        
        $mform->addElement('header', 'header_apresentacao', get_string('header_apresentacao','sepex'));
        //CAMPOS DE ALUNOS POR PROJETO               
        $alunos = listar_nome_alunos($id_projeto);
        $lista_aluno = array();
        foreach($alunos as $aluno){
            $lista_aluno[$aluno->username] =  $aluno->name;
        }    
        $typeitem = array();
        foreach ($lista_aluno as $key => $value) {
         $typeitem[] = &$mform->createElement('advcheckbox',$key, '', $value, array('name' => $key,'group'=>1), $key);
         $mform->setDefault("types[$key]", false);
        }
        $mform->addGroup($typeitem, 'types',get_string('presenca_integrantes','sepex'));
        $mform->addHelpButton('types', 'presenca_integrantes', 'sepex');                    
        
        $mform->addElement('text', 'apresentacao1', get_string('exposicao_apresentacao', 'sepex'), array('placeholder'=> $placeholder, 'size' => '15'));        
        $mform->addRule('apresentacao1', get_string('exposicao_apresentacao', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('apresentacao1', 'exposicao_apresentacao', 'sepex');
        $mform->setType('apresentacao1', PARAM_RAW);

        $mform->addElement('text', 'apresentacao2', get_string('tempo_apresentacao', 'sepex'), array('placeholder'=> $placeholder, 'size' => '15'));        
        $mform->addRule('apresentacao2', get_string('tempo_apresentacao', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('apresentacao2', 'tempo_apresentacao', 'sepex');
        $mform->setType('apresentacao2', PARAM_RAW);

        $mform->addElement('text', 'apresentacao3', get_string('conhecimento_apresentacao', 'sepex'), array('placeholder'=> $placeholder, 'size' => '15'));        
        $mform->addRule('apresentacao3', get_string('conhecimento_apresentacao', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('apresentacao3', 'conhecimento_apresentacao', 'sepex');
        $mform->setType('apresentacao3', PARAM_RAW);

        $mform->addElement('text', 'apresentacao4', get_string('relevancia_apresentacao', 'sepex'), array('placeholder'=> $placeholder, 'size' => '15'));        
        $mform->addRule('apresentacao4', get_string('relevancia_apresentacao', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('apresentacao4', 'relevancia_apresentacao', 'sepex');
        $mform->setType('apresentacao4', PARAM_RAW);
        
        $mform->addElement('text', 'apresentacao5', get_string('viabilidade_apresentacao', 'sepex'), array('placeholder'=> $placeholder, 'size' => '15'));            
        $mform->addRule('apresentacao5', get_string('viabilidade_apresentacao', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('apresentacao5', 'viabilidade_apresentacao', 'sepex');
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
