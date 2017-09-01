<?php

/**
 * Description of ApresentacaoController
 *
 * @author Carlos Eduardo Vieira
 */
require '../models/ApresentacaoModel.class.php';

class ApresentacaoController extends ApresentacaoModel {

    /** Lista os dados de apresentacao de um projeto */
    public function detailApresentacao($idprojeto) {
        try {
            return parent::detailApresentacao($idprojeto);
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    /** Lista os dados de apresentacao de um projeto filtrado por AREA - CURSO - CATEGORIA */
    public function detailProjetosFiltrados($filtro) {
        try {
            return parent::detailProjetosFiltrados($filtro);
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function save($definicao) {
        try {
            parent::save($definicao);
            return true;
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function update($definicao) {
        try {
            parent::update($definicao);
            return true;
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

}
