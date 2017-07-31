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

    public function update($dados) {
        try {
            parent::update($dados);
            return true;
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function delete($dados) {
        try {
            parent::delete($dados);
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
    
    public function getProfessorProjeto($idprojeto) {
        try {
            return parent::getProfessorProjeto($idprojeto);
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }
    
    public function getAlunoProjeto($idprojeto) {
        try {
            return parent::getAlunoProjeto($idprojeto);
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

}
