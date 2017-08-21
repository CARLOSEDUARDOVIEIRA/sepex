<?php

/**
 * FORMULARIO DE AVALIACAO DA CATEGORIA FOTOGRAFIA
 *
 * @author Carlos Eduardo Vieira
 */
require("$CFG->libdir/formslib.php");

class Fotografia extends moodleform {

    function definition() {

        $mform = $this->_form;

        $placeholder = '20 pontos';
        $placeholder2 = '10 pontos';

        $idprojeto = $this->_customdata['idprojeto'];
        if (!empty($idprojeto)) {
            $avaliacaocontroller = new AvaliacaoController();
            $dataavaliacao = $avaliacaocontroller->getAvaliacao($idprojeto);
        }
        
        if (!empty($dataavaliacao)) {
            $mform->setDefault('avaliacao1', $dataavaliacao->avaliacao1);
            $mform->setDefault('avaliacao2', $dataavaliacao->avaliacao2);
            $mform->setDefault('avaliacao3', $dataavaliacao->avaliacao3);
            $mform->setDefault('avaliacao4', $dataavaliacao->avaliacao4);
            $mform->setDefault('avaliacao5', $dataavaliacao->avaliacao5);
            $mform->setDefault('avaliacao6', $dataavaliacao->avaliacao6);
            $mform->setDefault('totalavaliacao', $dataavaliacao->totalavaliacao);
            $mform->addElement('hidden','update', true);
        }

        $mform->addElement('header', 'header_apresentacao', get_string('header_apresentacao', 'sepex'));

        Avaliacoes::getAlunos($mform, $idprojeto);

        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao1', $placeholder, 'adequacao_foto');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao2', $placeholder2, 'qualidade_foto');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao3', $placeholder2, 'originalidade_video');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao4', $placeholder, 'emocao_foto');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao5', $placeholder, 'coerencia_foto');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao6', $placeholder, 'qualidade_resumo');
        $mform->addElement('static', 'totalavaliacao', get_string('total_apresentacao', 'sepex'));
        $mform->setType('totalavaliacao', PARAM_RAW);

        $this->add_action_buttons();
    }

    function validation($data, $files) {
        return array();
    }

}
