<?php

/**
 * FORMULARIO DE AVALIACAO DA CATEGORIA TCC
 *
 * @author Carlos Eduardo Vieira
 */
require("$CFG->libdir/formslib.php");

class TCC extends moodleform {

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
        $idprojeto = $this->_customdata['idprojeto'];
        if (!empty($idprojeto)) {
            $avaliacaocontroller = new AvaliacaoController();
            $dataavaliacao = $avaliacaocontroller->getAvaliacao($idprojeto);
        }

        $this->createFormAvaliacaoResumo($mform, $dataavaliacao);

        $this->add_action_buttons();
    }

    function validation($data, $files) {
        return array();
    }

}
