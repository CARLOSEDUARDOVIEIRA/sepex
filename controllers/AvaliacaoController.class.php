<?php

/** AvaliacaoController
 *  @author Carlos Eduardo Vieira
 */
require ('../models/AvaliacaoModel.class.php');

class AvaliacaoController extends AvaliacaoModel {

    /** Lista os dados de avaliacao da apresentacao de um projeto */
    public function getAvaliacao($idprojeto) {
        try {
            return parent::getAvaliacao($idprojeto);
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function save($idprojeto, $notas) {
        try {
            parent::save($idprojeto, $notas);
            return true;
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }
    
    public function update($idprojeto, $notas) {
        try {
            parent::update($idprojeto, $notas);
            return true;
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }
    /**Este metodo espera o id do projeto seguido de um array contendo como indice a matricula do aluno e como valor 
     * a informacao de presenca */
    public function savePresencaAlunos($idprojeto, $alunos) {
        try {
            parent::savePresencaAlunos($idprojeto, $alunos);
            return true;
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }
    
}
