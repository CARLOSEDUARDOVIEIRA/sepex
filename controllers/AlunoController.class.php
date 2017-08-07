<?php

require ('../models/AlunoModel.class.php');

/**
 * Description of AlunoController
 *
 * @author Carlos Eduardo Vieira
 */
class AlunoController extends AlunoModel {

    /** Lista a matricula dos alunos por id de projeto */
    public function getAlunosProjeto($idprojeto) {
        try {
            return parent::getAlunosProjeto($idprojeto);
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function getNameAlunos($idprojeto) {
        try {
            return parent::getNameAlunos($idprojeto);
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

}
