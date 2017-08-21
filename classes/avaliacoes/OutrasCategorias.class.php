<?php

/**
 * FORMULARIO DE AVALIACAO DA CATEGORIA O.CATEGORIAS
 *
 * @author Carlos Eduardo Vieira
 */
require("$CFG->libdir/formslib.php");

class OutrasCategorias extends moodleform {

    function createFormAvaliacaoResumo($mform, $dataavaliacao = NULL) {

        if ($dataavaliacao) {
            $mform->setDefault('resumo1', $dataavaliacao->resumo1);
            $mform->setDefault('resumo2', $dataavaliacao->resumo2);
            $mform->setDefault('resumo3', $dataavaliacao->resumo3);
            $mform->setDefault('resumo4', $dataavaliacao->resumo4);
            $mform->setDefault('resumo5', $dataavaliacao->resumo5);
            $mform->setDefault('totalresumo', $dataavaliacao->totalresumo);
        }
        Avaliacoes::createFormAvaliacaoResumo($mform);
    }

    function definition() {

        $mform = $this->_form;

        $placeholder = '25 pontos';

        $idprojeto = $this->_customdata['idprojeto'];
        if (!empty($idprojeto)) {
            $avaliacaocontroller = new AvaliacaoController();
            $dataavaliacao = $avaliacaocontroller->getAvaliacao($idprojeto);
        }

        $this->createFormAvaliacaoResumo($mform, $dataavaliacao);

        if (!empty($dataavaliacao)) {
            $mform->setDefault('avaliacao1', $dataavaliacao->avaliacao1);
            $mform->setDefault('avaliacao2', $dataavaliacao->avaliacao2);
            $mform->setDefault('avaliacao3', $dataavaliacao->avaliacao3);
            $mform->setDefault('avaliacao4', $dataavaliacao->avaliacao4);
            $mform->setDefault('totalavaliacao', $dataavaliacao->totalavaliacao);
            $mform->addElement('hidden', 'update', true);
        }

        $mform->addElement('header', 'header_apresentacao', get_string('header_apresentacao', 'sepex'));

        Avaliacoes::getAlunos($mform, $idprojeto);

        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao1', $placeholder, 'exposicao_apresentacao');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao2', $placeholder, 'tempo_apresentacao');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao3', $placeholder, 'conhecimento_apresentacao');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao4', $placeholder, 'relevancia_apresentacao');
        $mform->addElement('static', 'totalavaliacao', get_string('total_apresentacao', 'sepex'));
        $mform->setType('totalavaliacao', PARAM_RAW);

        $this->add_action_buttons();
    }

    function validation($data, $files) {
        return array();
    }

}
