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

}
