<?php

require_once ("../../../config.php");
require_once($CFG->dirroot.'/course/moodleform_mod.php');

class FormularioPesquisa extends moodleform {
    function definition(){
        global $DB, $PAGE;

        $mform = $this->_form;

        $categoria = array(
            '' => 'Escolher',
            '1' => 'Egresso',
            '2' => 'Estágio',
            '3' => 'Iniciação Científica',
            '4' => 'Inivação',
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
            '1' => 'Matutino',
            '2' => 'Noturno',
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
        $mform->setDefault('mesa', $this->_customdata['mesa']);

        $alunoPresente = array(
            '' => 'Escolher',
            '1' => 'Sim',
            '2' => 'Não',
        );
        $mform->addElement('select', 'alunoPresente', get_string('aluno_presente', 'sepex'), $alunoPresente);        
        $mform->addRule('alunoPresente', get_string('aluno_presente', 'sepex'), 'maxlength', 255, 'client');
        $mform->addHelpButton('alunoPresente', 'aluno_presente', 'sepex');        
        $mform->setDefault('alunoPresente', $this->_customdata['alunoPresente']);

        $semNota = array(
            '' => 'Escolher',
            '1' => 'Sem nota',
            '2' => 'Com nota',
        );
        $mform->addElement('select', 'semNota', get_string('projeto_nota', 'sepex'), $semNota);        
        $mform->addRule('semNota', get_string('projeto_nota', 'sepex'), 'maxlength', 255, 'client');
        $mform->addHelpButton('semNota', 'projeto_nota', 'sepex');        
        $mform->setDefault('semNota', $this->_customdata['semNota']);

        $this->add_action_buttons($cancel = true, $submitlabel = get_string('listarprojetos', 'sepex'));
    }

    function validation($data, $files) {
        return array();
    }
}