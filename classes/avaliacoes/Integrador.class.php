<?php

/**
 * FORMULARIO DE AVALIACAO DA CATEGORIA P. INTEGRADOR
 *
 * @author Carlos Eduardo Vieira
 */
require($CFG->dirroot . '/course/moodleform_mod.php');

class Integrador extends moodleform {

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

        $placeholder = '10 pontos';

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
            $mform->setDefault('avaliacao5', $dataavaliacao->avaliacao5);
            $mform->setDefault('avaliacao6', $dataavaliacao->avaliacao6);
            $mform->setDefault('avaliacao7', $dataavaliacao->avaliacao7);
            $mform->setDefault('avaliacao8', $dataavaliacao->avaliacao8);
            $mform->setDefault('avaliacao9', $dataavaliacao->avaliacao9);
            $mform->setDefault('avaliacao10', $dataavaliacao->avaliacao10);
            $mform->setDefault('avaliacao11', $dataavaliacao->avaliacao11);
            $mform->setDefault('avaliacao12', $dataavaliacao->avaliacao12);
            $mform->setDefault('totalavaliacao', $dataavaliacao->totalavaliacao);
            $mform->addElement('hidden', 'update', true);
        }

        $mform->addElement('header', 'header_apresentacao', get_string('header_apresentacao', 'sepex'));

        Avaliacoes::getAlunos($mform, $idprojeto);

        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao1', $placeholder, 'exposicao_apresentacao');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao2', $placeholder, 'tempo_apresentacao');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao3', $placeholder, 'conhecimento_apresentacao');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao4', $placeholder, 'relevancia_apresentacao');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao5', $placeholder, 'interdisc_apresentacao');

        $mform->addElement('header', 'header_banner', get_string('header_banner', 'sepex'));
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao6', $placeholder, 'ortografia');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao7', '5 pontos', 'relacao_tema');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao8', '5 pontos', 'introducao');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao9', $placeholder, 'metodos');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao10', $placeholder, 'resultados_discussao');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao11', '5 pontos', 'consideracoes_finais');
        Avaliacoes::createCampoAvaliacao($mform, 'avaliacao12', '5 pontos', 'referenciais_utilizados');
        $mform->addElement('static', 'totalavaliacao', get_string('total_apresentacao', 'sepex'));
        $mform->setType('totalavaliacao', PARAM_RAW);

        $this->add_action_buttons();
    }

    function validation($data, $files) {
        return array();
    }

}
