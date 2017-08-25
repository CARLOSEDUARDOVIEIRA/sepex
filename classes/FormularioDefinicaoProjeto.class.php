<?php

/**
 * CLASSE RESPONSÁVEL POR IMPLEMENTAR UM FORMULÁRIO PARA DEFINIÇÃO DOS PROJETOS
 *
 * @author Carlos Eduardo Vieira. Linkedin<>.
 */
require($CFG->dirroot . '/course/moodleform_mod.php');
require '../controllers/LocalController.class.php';

class FormularioDefinicaoProjeto extends moodleform {

    function definition() {

        $localcontroller = new LocalController();
        $projetocontroller = new ProjetoController();

        $mform = $this->_form;
        $course = $this->_customdata['course'];
        if (isset($this->_customdata['idlocalapresentacao'])) {
            $mform->setDefault('idlocalapresentacao', $this->_customdata['idlocalapresentacao']);
            $mform->setDefault('dtapresentacao', $this->_customdata['dtapresentacao']);
            $mform->setDefault('avaliador', $this->_customdata['avaliador']);
            $mform->setDefault('avaliador2', $this->_customdata['avaliador2']);
            $mform->addElement('hidden', 'update', true);
        }


        // -------------------- DATA PARA APRESENTAÇÃO DO PROJETO -------------------------
        $mform->addElement('header', 'apresentacao', get_string('dataapresentacao', 'sepex'));
        $mform->addElement('date_time_selector', 'dtapresentacao', get_string('data_definida', 'sepex'));
        $mform->addHelpButton('dtapresentacao', 'dataapresentacao', 'sepex');

        // -------------------- LOCAL PARA APRESENTAÇÃO DO PROJETO -------------------------
        $locais = $localcontroller->getLocais();
        $locaisapres = array('' => 'Escolher',);
        foreach ($locais as $local) {
            $locaisapres[$local->idlocalapresentacao] = $local->nomelocalapresentacao;
        }
        $mform->addElement('header', 'loc_apresentacao', get_string('localapresentacao', 'sepex'));
        $mform->addElement('select', 'idlocalapresentacao', get_string('localapresentacao', 'sepex'), $locaisapres);
        $mform->addRule('idlocalapresentacao', get_string('localapresentacaovazio', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('idlocalapresentacao', 'localapresentacao', 'sepex');


        //------------------------ SELEÇÃO AVALIADORES ------------------------------------
        $mform->addElement('header', 'avaliadores', get_string('avaliadores', 'sepex'));

        $orientadores = $projetocontroller->getUsuarioPorCurso('editingteacher', $course);
        $professores = array('' => 'Escolher',);
        foreach ($orientadores as $professor) {
            $professores[$professor->username] = $professor->name;
        }

        $mform->addElement('select', 'avaliador', get_string('avaliador', 'sepex'), $professores);
        $mform->addElement('select', 'avaliador2', get_string('avaliador', 'sepex'), $professores);
        $mform->addRule('avaliador', get_string('avaliadorvazio', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('avaliador', 'avaliador', 'sepex');

        $this->add_action_buttons();
    }

    function validation($data, $files) {
        return array();
    }

}
