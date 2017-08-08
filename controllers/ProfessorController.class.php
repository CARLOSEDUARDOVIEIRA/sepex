<?php

require ('../models/ProfessorModel.class.php');

/**
 * Description of ProfessorController
 *
 * @author Carlos Eduardo Vieira
 */
class ProfessorController extends ProfessorModel {

    /** Lista a matricula dos professores por id de projeto */
    public function getProfessorProjeto($idprojeto, $tipo) {
        try {
            return parent::getProfessorProjeto($idprojeto, $tipo);
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function getProjetosProfessor($professor) {
        try {
            return parent::getProjetosProfessor($professor);
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function saveAvaliacaoOrientador($avaliacao, $idprojeto, $professor) {
        try {
            parent::saveAvaliacaoOrientador($avaliacao, $idprojeto, $professor);
            return true;
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

}
