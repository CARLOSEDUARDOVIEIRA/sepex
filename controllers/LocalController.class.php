<?php

/**
 * Description of LocalController
 *
 * @author Carlos Eduardo Vieira
 */
require '../models/LocalModel.class.php';

class LocalController extends LocalModel {

    public function save($local) {
        try {
            parent::save($local);
            return true;
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

    public function getLocais() {
        try {
            return parent::getLocais();
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }
    
    public function delete($idlocal) {
        try {
            parent::delete($idlocal);
            return true;
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
    }

}
