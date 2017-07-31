<?php

require '../models/CursoModel.class.php';

/**
 * Description of CursoController
 *
 * @author Carlos Eduardo Vieira
 */
class CursoController extends CursoModel {

    public function save($idcurso) {
        try {
            parent::save($idcurso);
            return true;
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function update($idcurso) {
        try {
            parent::update($idcurso);
            return true;
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function delete($idcurso) {
        try {
            parent::delete($idcurso);
            return true;
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function getCurso() {
        try {
            return parent::getCurso();
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

}
