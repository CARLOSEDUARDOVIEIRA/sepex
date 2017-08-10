<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * FORMULARIO DE AVALIACAO DA CATEGORIA ESTAGIO
 *
 * @author Carlos Eduardo Vieira
 */
require("$CFG->libdir/formslib.php");

class Estagio extends moodleform {

    function createFormAvaliacaoResumo($mform) {

        if ($this->_customdata['resumo1']) {
            $mform->setDefault('resumo1', $this->_customdata['resumo1']);
            $mform->setDefault('resumo2', $this->_customdata['resumo2']);
            $mform->setDefault('resumo3', $this->_customdata['resumo3']);
            $mform->setDefault('resumo4', $this->_customdata['resumo4']);
            $mform->setDefault('resumo5', $this->_customdata['resumo5']);
            $mform->setDefault('totalresumo', $this->_customdata['totalresumo']);
        }

        Avaliacoes::createFormAvaliacaoResumo($mform);
    }

    function definition() {

        $mform = $this->_form;

        $this->createFormAvaliacaoResumo($mform);
        $placeholder = '20 pontos';

        $idprojeto = $this->_customdata['idprojeto'];
        if ($this->_customdata['apresentacao1']) {
            $mform->setDefault('apresentacao1', $this->_customdata['apresentacao1']);
            $mform->setDefault('apresentacao2', $this->_customdata['apresentacao2']);
            $mform->setDefault('apresentacao3', $this->_customdata['apresentacao3']);
            $mform->setDefault('apresentacao4', $this->_customdata['apresentacao4']);
            $mform->setDefault('apresentacao5', $this->_customdata['apresentacao5']);
            $mform->setDefault('total_apresentacao', $this->_customdata['total_apresentacao']);
        }

        Avaliacoes::getAlunos($mform, $idprojeto);

        $mform->addElement('header', 'header_apresentacao', get_string('header_apresentacao', 'sepex'));

        Avaliacoes::createCampoAvaliacao($mform, 'apresentacao1', $placeholder, 'exposicao_apresentacao');
        Avaliacoes::createCampoAvaliacao($mform, 'apresentacao2', $placeholder, 'tempo_apresentacao');
        Avaliacoes::createCampoAvaliacao($mform, 'apresentacao3', $placeholder, 'conhecimento_apresentacao');
        Avaliacoes::createCampoAvaliacao($mform, 'apresentacao4', $placeholder, 'relevancia_apresentacao');
        Avaliacoes::createCampoAvaliacao($mform, 'apresentacao5', $placeholder, 'valorizacao_apresentacao');
        $mform->addElement('static', 'total_apresentacao', get_string('total_apresentacao', 'sepex'));
        $mform->setType('total_apresentacao', PARAM_RAW);

        $this->add_action_buttons();
    }

    function validation($data, $files) {
        return array();
    }

}
