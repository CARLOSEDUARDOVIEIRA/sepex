<?php

/**
 * FORMULARIO DE AVALIACAO DA CATEGORIA VIDEOS
 *
 * @author Carlos Eduardo Vieira
 */
require($CFG->dirroot . '/course/moodleform_mod.php');

class Video extends moodleform {

    function definition() {

        $mform = $this->_form;

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
            $mform->setDefault('totalavaliacao', $dataavaliacao->totalavaliacao);
            $mform->addElement('hidden', 'update', true);
        }

        $mform->addElement('header', 'header_apresentacao', get_string('header_apresentacao', 'sepex'));

        Avaliacoes::getAlunos($mform, $idprojeto);

        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao1', '30 pontos', 'qualidade_video');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao2', '20 pontos', 'originalidade_video');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao3', '15 pontos', 'exposicao_apresentacao');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao4', '20 pontos', 'coerencia_video');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao5', '15 pontos', 'qualidade_resumo');
        $mform->addElement('static', 'totalavaliacao', get_string('total_apresentacao', 'sepex'));
        $mform->setType('totalavaliacao', PARAM_RAW);

        $this->add_action_buttons();
    }

    function validation($data, $files) {
        return array();
    }

}
