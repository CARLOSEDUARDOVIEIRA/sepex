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

}
