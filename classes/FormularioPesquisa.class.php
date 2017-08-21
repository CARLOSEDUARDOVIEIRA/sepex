<?php

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class FormularioPesquisa extends moodleform {

    function definition() {

        $constantes = new Constantes();

        $mform = $this->_form;
        
        $cursos = $constantes->getCursos();
        $mform->addElement('select', 'idcurso', get_string('curso', 'sepex'), $cursos);
        $mform->addHelpButton('idcurso', 'curso', 'sepex');
        
        $area = $constantes->getAreas();
        $mform->addElement('select', 'areacurso', get_string('area', 'sepex'), $area);
        $mform->addHelpButton('areacurso', 'area', 'sepex');
        
        $categoria = $constantes->getCategorias();
        $mform->addElement('select', 'idcategoria', get_string('categoria', 'sepex'), $categoria);
        $mform->addHelpButton('idcategoria', 'categoria', 'sepex');
        $mform->setDefault('idcategoria', $this->_customdata['idcategoria']);

        $turno = $constantes->getTurnos();
        $mform->addElement('select', 'turno', get_string('turno', 'sepex'), $turno);
        $mform->addHelpButton('turno', 'turno', 'sepex');
        $mform->setDefault('turno', $this->_customdata['turno']);

        $mesa = array(
            '' => 'Escolher',
            '1' => 'Sim',
            '0' => 'Não'
        );
        $mform->addElement('select', 'alocamesa', get_string('solicita_mesa', 'sepex'), $mesa);
        $mform->addHelpButton('alocamesa', 'solicita_mesa', 'sepex');

        $resumo = array(
            '' => 'Escolher',
            '2' => 'Não Avaliado',
            '1' => 'Aprovado',
            '0' => 'Reprovado'
        );
        $mform->addElement('select', 'statusresumo', get_string('situacao', 'sepex'), $resumo);
        $mform->addHelpButton('statusresumo', 'situacao', 'sepex');
        
        $nota = array(
            '' => 'Escolher',
            '1' => 'Sim',
            '0' => 'Não',
        );
        $mform->addElement('select', 'nota', get_string('exibir_notas', 'sepex'), $nota);

        $this->add_action_buttons($cancel = true, get_string('listarprojetos', 'sepex'));
    }

}
