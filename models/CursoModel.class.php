<?php

/**
 * Description of CursoModel
 *
 * @author Carlos Eduardo Vieira
 */
class CursoModel {

    protected function save($curso) {
        global $DB;
        $DB->insert_record("sepex_curso", $curso);
    }

    protected function update($idcurso, $curso) {
        global $DB;

        $DB->execute("
            UPDATE mdl_sepex_curso
                   nomecurso = ?,
                   areacurso = ?
                WHERE idcurso = {$idcurso}", array(
            $curso->nomecurso,
            $curso->areacurso
                )
        );
    }

    protected function delete($idcurso) {
        global $DB;
        $DB->delete_records('sepex_curso', array("idcurso" => $idcurso));
    }

    protected function getCurso() {
        global $DB;
        return $DB->get_records('sepex_curso');
    }

}
