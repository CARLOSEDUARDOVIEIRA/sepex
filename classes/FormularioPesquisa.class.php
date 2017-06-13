<?php

require_once ("../../../config.php");
require_once($CFG->dirroot.'/course/moodleform_mod.php');

class FormularioPesquisa extends moodleform {
    function definition(){
        global $DB, $PAGE;

        $mform = $this->_form;

        $area = array(
            '' => 'Escolher',
            '1' => 'Ciências Sociais e Aplicadas',
            '2' => 'Exatas',
            '3' => 'Saúde'            
        );
        $mform->addElement('select', 'area_curso', get_string('area', 'sepex'), $area);       
        $mform->addRule('area_curso', get_string('area', 'sepex'), 'maxlength', 255, 'client');
        $mform->addHelpButton('area_curso', 'area', 'sepex');
                
        $categoria = array(
            '' => 'Escolher',
            '1' => 'Egresso',
            '2' => 'Estágio',
            '3' => 'Iniciação Científica',
            '4' => 'Inovação',
            '5' => 'Projeto de Extensão',
            '6' => 'Projeto Integrador',
            '7' => 'Responsabilidade Social',
            '8' => 'Tema Livre',
            '9' => 'Trabalho de Conclusão de Curso',
            '10' => 'Mostra Vídeo',
            '11' => 'Concurso de Fotografia',
        );
        $mform->addElement('select', 'categoria', get_string('categoria', 'sepex'), $categoria);
        $mform->addRule('categoria', get_string('categoria', 'sepex'), 'maxlength', 255, 'client');
        $mform->addHelpButton('categoria', 'categoria', 'sepex');
        $mform->setDefault('categoria', $this->_customdata['categoria']);

        $turno = array(
            '' => 'Escolher',
            'Matutino' => 'Matutino',
            'Noturno' => 'Noturno',
        );
        $mform->addElement('select', 'turno', get_string('turno', 'sepex'), $turno);        
        $mform->addRule('turno', get_string('turno', 'sepex'), 'maxlength', 255, 'client');
        $mform->addHelpButton('turno', 'turno', 'sepex');        
        $mform->setDefault('turno', $this->_customdata['turno']);

        $mesa = array(
            '' => 'Escolher',
            '1' => 'Sim',
            '0' => 'Não',
        );
        $mform->addElement('select', 'mesa', get_string('solicita_mesa', 'sepex'), $mesa);        
        $mform->addRule('mesa', get_string('solicita_mesa', 'sepex'), 'maxlength', 255, 'client');
        $mform->addHelpButton('mesa', 'solicita_mesa', 'sepex');                

        $this->add_action_buttons($cancel = true, $submitlabel = get_string('listarprojetos', 'sepex'));
    }

    function validation($data, $files) {
        return array();
    }
}