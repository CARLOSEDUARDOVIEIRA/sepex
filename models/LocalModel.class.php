<?php

/**
 * Description of LocalModel
 *
 * @author Carlos Eduardo Vieira
 */
class LocalModel {

    protected function save($local) {
        global $DB;
        $DB->insert_record("sepex_local_apresentacao", $local);
    }

    protected function getLocais() {
        global $DB;
        $listalocais = $DB->get_records("sepex_local_apresentacao", array());
        $locais = array();
        foreach ($listalocais as $local) {
            array_push($locais, $local);
        }
        return $locais;
    }

    protected function delete($idlocal) {
        global $DB;
        $DB->delete_records('sepex_local_apresentacao', array("idlocalapresentacao" => $idlocal));
    }

}
