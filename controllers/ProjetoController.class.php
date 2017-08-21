<?php

require '../models/ProjetoModel.class.php';
//require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');

/**
 * Description of ProjetoController
 *
 * @author Carlos Eduardo Vieira
 */
class ProjetoController extends ProjetoModel {

    public function save($projeto) {
        try {
            parent::save($projeto);  // chama da classe pai: ProjetoModel::save()
            return true;
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function update($idprojeto) {
        try {
            parent::update($idprojeto);
            return true;
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function delete($idprojeto) {
        try {
            parent::delete($idprojeto);
            return true;
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function detail($idprojeto) {
        try {
            return parent::detail($idprojeto);
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function getDefinicaoProjeto($idprojeto) {
        try {
            return parent::getDefinicaoProjeto($idprojeto);
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function getAvaliacaoProjeto($professores) {
        try {
            return parent::getAvaliacaoProjeto($professores);
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function getProjetosDoUsuario() {
        try {
            return parent::getProjetosDoUsuario();
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function getUsuarioPorCurso($typeuser, $course) {
        try {
            return parent::getUsuarioPorCurso($typeuser, $course);
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }
    
    public function getProjetosFiltrados($filtro) {
        try {
            return parent::getProjetosFiltrados($filtro);
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }
}
