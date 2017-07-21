<?php

require_once '../models/ProjetoModel.class.php';
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');

/**
 * Description of ProjetoController
 *
 * @author Carlos Eduardo Vieira
 */

class ProjetoController extends ProjetoModel {

    public function save($dados) {
        try {
           return parent::save($dados);  // chama da classe pai: ProjetoModel::save()
        } catch (Exception $e) {
            echo 'Exceção capturada: ', $e->getMessage(), "\n";
        }
       // return $dados;
    }

}
